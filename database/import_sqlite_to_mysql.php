<?php

declare(strict_types=1);

/**
 * Importa los datos de una copia SQLite ya normalizada a la base MySQL de EBIM.
 *
 * Uso:
 *   php database/import_sqlite_to_mysql.php --force
 *   php database/import_sqlite_to_mysql.php --source="ruta/a/database.normalized.sqlite" --force
 *
 * El destino debe ser la base configurada en .env (MySQL). El script:
 *  - Exige que el origen ya tenga el esquema de 3FN.
 *  - Vacía solo las tablas del dominio (no toca migraciones, sesiones ni caché).
 *  - Conserva IDs, fechas, contraseñas, estados de horario y notas.
 *  - Verifica conteos, claves foráneas y duplicados antes de terminar.
 *
 * No se ejecuta automáticamente desde la aplicación.
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$opciones = getopt('', ['source::', 'force']);
$origen = $opciones['source'] ?? __DIR__ . '/database.normalized.sqlite';
$forzar = array_key_exists('force', $opciones);

$tablas = [
    'especialidades',
    'asignaturas',
    'cursos',
    'users',
    'docentes',
    'contenidos',
    'noticias',
    'horarios',
    'notas',
];

function abortar(string $mensaje): never
{
    fwrite(STDERR, "ERROR: {$mensaje}\n");
    exit(1);
}

if (! is_file($origen)) {
    abortar("No existe el archivo SQLite de origen: {$origen}");
}

if (DB::connection()->getDriverName() !== 'mysql') {
    abortar('La conexión configurada no es MySQL. Revisa DB_CONNECTION en .env.');
}

if (! Schema::hasTable('especialidades') || ! Schema::hasColumn('users', 'especialidad_id')) {
    abortar('El destino no tiene el esquema normalizado. Ejecuta primero: php artisan migrate --force');
}

$sqlite = new PDO('sqlite:' . $origen, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$sqlite->exec('PRAGMA foreign_keys = OFF');

$columnasSqlite = static function (string $tabla) use ($sqlite): array {
    $columnas = [];

    foreach ($sqlite->query("PRAGMA table_info(\"{$tabla}\")") as $columna) {
        $columnas[] = $columna['name'];
    }

    return $columnas;
};

// Comprobación de seguridad: no importar el esquema antiguo por accidente.
$columnasUsuarios = $columnasSqlite('users');
$columnasNotas = $columnasSqlite('notas');
$columnasHorarios = $columnasSqlite('horarios');

if (
    ! in_array('especialidad_id', $columnasUsuarios, true)
    || in_array('especialidad', $columnasUsuarios, true)
    || ! in_array('asignatura_id', $columnasNotas, true)
    || ! in_array('parcial', $columnasNotas, true)
    || in_array('bimestre', $columnasNotas, true)
    || ! in_array('asignatura_id', $columnasHorarios, true)
) {
    abortar('El SQLite de origen no está normalizado. Usa database.normalized.sqlite o migra una copia primero.');
}

/**
 * Une nombres de catálogo que solo difieren en mayúsculas o espacios. MySQL
 * usa una colación que no distingue mayúsculas, así que dos filas como
 * "Lenguaje Musical" y "Lenguaje musical" chocarían con el índice único.
 *
 * @param  array<string, string>  $referencias  tabla => columna que apunta al catálogo
 * @return array<int, int>  IDs antiguos => ID canónico
 */
function deduplicarCatalogo(PDO $sqlite, string $tabla, array $referencias): array
{
    $filas = $sqlite->query("SELECT id, nombre FROM \"{$tabla}\" ORDER BY id")->fetchAll();
    $canonicos = [];
    $remaps = [];
    $eliminar = [];

    foreach ($filas as $fila) {
        $clave = mb_strtolower(trim((string) $fila['nombre']), 'UTF-8');

        if (isset($canonicos[$clave])) {
            $remaps[(int) $fila['id']] = $canonicos[$clave];
            $eliminar[] = (int) $fila['id'];
        } else {
            $canonicos[$clave] = (int) $fila['id'];
        }
    }

    if ($remaps === []) {
        return [];
    }

    $sqlite->beginTransaction();

    foreach ($referencias as $tablaReferencia => $columna) {
        $actualizar = $sqlite->prepare(
            "UPDATE \"{$tablaReferencia}\" SET \"{$columna}\" = ? WHERE \"{$columna}\" = ?"
        );

        foreach ($remaps as $antiguo => $nuevo) {
            $actualizar->execute([$nuevo, $antiguo]);
        }
    }

    $borrar = $sqlite->prepare("DELETE FROM \"{$tabla}\" WHERE id = ?");

    foreach ($eliminar as $id) {
        $borrar->execute([$id]);
    }

    $sqlite->commit();

    return $remaps;
}

if (! $forzar) {
    foreach ($tablas as $tabla) {
        if (DB::table($tabla)->exists()) {
            abortar("La tabla {$tabla} ya tiene datos. Usa --force solo si deseas reemplazarlos.");
        }
    }
}

$remapsAsignaturas = deduplicarCatalogo($sqlite, 'asignaturas', [
    'horarios' => 'asignatura_id',
    'notas' => 'asignatura_id',
]);
$remapsEspecialidades = deduplicarCatalogo($sqlite, 'especialidades', [
    'users' => 'especialidad_id',
]);

if ($remapsAsignaturas !== []) {
    echo 'Asignaturas unificadas: ' . count($remapsAsignaturas) . "\n";
}

if ($remapsEspecialidades !== []) {
    echo 'Especialidades unificadas: ' . count($remapsEspecialidades) . "\n";
}

$notasDuplicadas = (int) $sqlite->query(
    'SELECT COUNT(*) FROM (
        SELECT user_id, asignatura_id, gestion, parcial
        FROM notas
        GROUP BY user_id, asignatura_id, gestion, parcial
        HAVING COUNT(*) > 1
    ) duplicadas'
)->fetchColumn();

if ($notasDuplicadas > 0) {
    abortar('Al unificar asignaturas quedarían notas repetidas para el mismo parcial. Revisa el origen.');
}

$pdo = DB::connection()->getPdo();
$pdo->exec('SET NAMES utf8mb4');

$conteosOrigen = [];
$conteosDestino = [];

try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    DB::beginTransaction();

    // Vaciar en orden inverso a las dependencias.
    foreach (array_reverse($tablas) as $tabla) {
        DB::table($tabla)->delete();
    }

    foreach ($tablas as $tabla) {
        if (! Schema::hasTable($tabla)) {
            abortar("Falta la tabla {$tabla} en MySQL.");
        }

        $columnas = array_values(array_intersect(
            Schema::getColumnListing($tabla),
            $columnasSqlite($tabla),
        ));

        if (! in_array('id', $columnas, true)) {
            abortar("La tabla {$tabla} no tiene la columna id en ambos esquemas.");
        }

        $filas = $sqlite
            ->query("SELECT * FROM \"{$tabla}\" ORDER BY id")
            ->fetchAll();

        $conteosOrigen[$tabla] = count($filas);

        if ($filas === []) {
            continue;
        }

        $listaColumnas = '`' . implode('`, `', $columnas) . '`';
        $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
        $insertar = $pdo->prepare("INSERT INTO `{$tabla}` ({$listaColumnas}) VALUES ({$marcadores})");

        foreach ($filas as $fila) {
            $insertar->execute(array_map(
                static fn (string $columna) => $fila[$columna],
                $columnas,
            ));
        }
    }

    DB::commit();
} catch (Throwable $excepcion) {
    DB::rollBack();
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    abortar('Falló la importación: ' . $excepcion->getMessage());
} finally {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

// Dejar el siguiente AUTO_INCREMENT pegado al último ID importado, sin
// conservar los saltos que dejó la carga anterior.
foreach ($tablas as $tabla) {
    $siguiente = max(1, ((int) DB::table($tabla)->max('id')) + 1);
    DB::statement("ALTER TABLE `{$tabla}` AUTO_INCREMENT = {$siguiente}");
}

foreach ($tablas as $tabla) {
    $conteosDestino[$tabla] = DB::table($tabla)->count();

    if ($conteosDestino[$tabla] !== $conteosOrigen[$tabla]) {
        abortar(
            "Conteo distinto en {$tabla}: origen {$conteosOrigen[$tabla]}, destino {$conteosDestino[$tabla]}."
        );
    }
}

$integridad = [
    'usuarios con curso inexistente' => 'SELECT COUNT(*) AS total FROM users u LEFT JOIN cursos c ON c.id = u.curso_id WHERE u.curso_id IS NOT NULL AND c.id IS NULL',
    'usuarios con especialidad inexistente' => 'SELECT COUNT(*) AS total FROM users u LEFT JOIN especialidades e ON e.id = u.especialidad_id WHERE u.especialidad_id IS NOT NULL AND e.id IS NULL',
    'cursos con usuario remitente inexistente' => 'SELECT COUNT(*) AS total FROM cursos c LEFT JOIN users u ON u.id = c.horario_enviado_por WHERE c.horario_enviado_por IS NOT NULL AND u.id IS NULL',
    'cursos con usuario revisor inexistente' => 'SELECT COUNT(*) AS total FROM cursos c LEFT JOIN users u ON u.id = c.horario_revisado_por WHERE c.horario_revisado_por IS NOT NULL AND u.id IS NULL',
    'horarios con curso inexistente' => 'SELECT COUNT(*) AS total FROM horarios h LEFT JOIN cursos c ON c.id = h.curso_id WHERE c.id IS NULL',
    'horarios con asignatura inexistente' => 'SELECT COUNT(*) AS total FROM horarios h LEFT JOIN asignaturas a ON a.id = h.asignatura_id WHERE a.id IS NULL',
    'notas con estudiante inexistente' => 'SELECT COUNT(*) AS total FROM notas n LEFT JOIN users u ON u.id = n.user_id WHERE u.id IS NULL',
    'notas con asignatura inexistente' => 'SELECT COUNT(*) AS total FROM notas n LEFT JOIN asignaturas a ON a.id = n.asignatura_id WHERE a.id IS NULL',
    'franjas duplicadas por curso/día/inicio' => 'SELECT COUNT(*) AS total FROM (SELECT curso_id, dia, hora_inicio FROM horarios GROUP BY curso_id, dia, hora_inicio HAVING COUNT(*) > 1) d',
    'notas duplicadas por estudiante/asignatura/gestión/parcial' => 'SELECT COUNT(*) AS total FROM (SELECT user_id, asignatura_id, gestion, parcial FROM notas GROUP BY user_id, asignatura_id, gestion, parcial HAVING COUNT(*) > 1) d',
];

foreach ($integridad as $descripcion => $consulta) {
    $total = (int) DB::selectOne($consulta)->total;

    if ($total !== 0) {
        abortar("Falló la verificación de integridad: {$descripcion} ({$total}).");
    }
}

$promedio = DB::selectOne('SELECT ROUND(AVG(nota), 2) AS promedio FROM notas')->promedio;
$claves = DB::selectOne("SELECT COUNT(*) AS total FROM users WHERE password LIKE '\$2y\$%'")->total;

echo "Importación completada.\n\n";
echo str_pad('Tabla', 18) . str_pad('Origen', 10) . "Destino\n";
echo str_repeat('-', 40) . "\n";

foreach ($tablas as $tabla) {
    echo str_pad($tabla, 18) . str_pad((string) $conteosOrigen[$tabla], 10) . $conteosDestino[$tabla] . "\n";
}

echo "\nIntegridad referencial: OK\n";
echo "Contraseñas con hash bcrypt: {$claves}\n";
echo "Promedio general de notas: {$promedio}\n";
