# Base de datos de EBIM

La aplicación usa **MySQL/MariaDB** como base de datos principal. La instancia local
verificada es **MariaDB 10.4.32**, puerto `3306`, base `ebim`, juego de caracteres
`utf8mb4` y colación `utf8mb4_unicode_ci`.

El diseño está normalizado hasta **tercera forma normal (3FN)**: los datos que se repetían
en varias filas (especialidades y asignaturas) viven en catálogos y las tablas operativas
solo guardan sus claves foráneas. Los nombres de asignaturas y especialidades se siguen
leyendo igual en las vistas mediante accesores de Eloquent, por lo que el sistema no cambia
su forma de uso.

## Archivos de respaldo

| Archivo | Uso |
|---------|-----|
| `database/ebim_mysql.sql` | Respaldo completo y restaurable de MySQL: esquema, datos y tabla de migraciones. |
| `database/database.sqlite` | Archivo SQLite original tal como estaba antes de la migración. Se conserva sin cambios. |
| `database/database.sqlite.original` | Copia de seguridad adicional e idéntica del SQLite antiguo. No se debe borrar. |
| `database/database.normalized.sqlite` | Copia SQLite ya normalizada, usada como origen del importador y como referencia. |
| `database/import_sqlite_to_mysql.php` | Importador único de SQLite normalizado a MySQL. Conserva IDs y datos. |

## Modelo de datos

### Catálogos

| Tabla | Campos principales | Propósito |
|-------|--------------------|-----------|
| `especialidades` | `id`, `nombre`, `orden` | Las 12 especialidades oficiales. `nombre` es único. |
| `asignaturas` | `id`, `nombre` | Catálogo único de materias usado por horarios y notas. |

### Personas y organización

| Tabla | Campos principales | Relaciones |
|-------|--------------------|------------|
| `users` | `name`, `email`, `password`, `role`, `codigo`, `curso_id`, `especialidad_id` | `curso_id → cursos.id`; `especialidad_id → especialidades.id`. |
| `cursos` | `nombre`, `orden`, `estado_horario`, datos de envío/revisión | `horario_enviado_por` y `horario_revisado_por → users.id`. |
| `docentes` | `nombre`, `cargo`, `especialidad`, `formacion`, `experiencia`, `foto`, `encuadre`, `orden`, `activo` | Catálogo del plantel docente. |
| `contenidos` | `clave`, `titulo`, `cuerpo`, `foto` | Textos institucionales editables. |
| `noticias` | `titulo`, `resumen`, `cuerpo`, `fecha`, `tipo`, `enlace`, `imagen`, `activo`, `orden` | Noticias y comunicados. |

### Datos operativos

| Tabla | Campos principales | Restricciones |
|-------|--------------------|---------------|
| `horarios` | `curso_id`, `asignatura_id`, `dia`, `hora_inicio`, `hora_fin`, `docente`, `ambiente` | `curso_id → cursos.id`; `asignatura_id → asignaturas.id`; único por `(curso_id, dia, hora_inicio)`. |
| `notas` | `user_id`, `asignatura_id`, `gestion`, `parcial`, `nota` | `user_id → users.id`; `asignatura_id → asignaturas.id`; único por `(user_id, asignatura_id, gestion, parcial)`. |

Las horas se guardan como `TIME` y las notas como `DECIMAL(5,2)`, porque `100.00` es una
nota válida. El campo que antes se llamaba `bimestre` ahora se llama `parcial`, que es el
término usado en el portal.

En `horarios`, la columna `docente` guarda el **área responsable** de la materia (por
ejemplo, «Docente de la especialidad»), no el nombre de una persona. Por eso se mantiene
como texto y no como clave foránea: todavía no existe una asignación nominal de materias
por docente. El estado de aprobación del horario vive en `cursos`, porque cada curso tiene
un único horario compuesto por varias franjas.

### Tablas de infraestructura

`migrations`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` y
`password_reset_tokens` pertenecen a Laravel. No forman parte del dominio y no se importan
desde SQLite.

## Datos cargados actualmente

| Tabla | Filas |
|-------|------:|
| `users` | 4 |
| `cursos` | 8 |
| `especialidades` | 12 |
| `asignaturas` | 20 |
| `horarios` | 120 |
| `notas` | 8 |
| `docentes` | 6 |
| `contenidos` | 10 |
| `noticias` | 6 |

- **Promedio general de notas:** `82.50`.
- **Contraseñas:** los 4 usuarios conservan su hash bcrypt original.
- **Cursos 1 "A" y 2 "A":** horario **aprobado**.
- **Cursos 3 "A", 1 "B", 2 "B", 3 "B", 4 "A" y 5 "A":** horario en **borrador**.
- **María Condori Mamani:** 2 "A", Guitarra, gestión 2026, notas de los parciales 1 y 2,
  promedio `82.50`.
- **Juan Quispe Flores:** 3 "A", Canto, sin notas registradas.
- Los IDs originales de usuarios, cursos, horarios y notas se conservaron.

Durante la normalización se detectó que «Lenguaje Musical» (usada en horarios) y
«Lenguaje musical» (usada en notas) eran la misma materia escrita con distinta mayúscula.
El importador las unificó en una sola fila del catálogo y remapeó las notas; no se perdió
ninguna calificación.

## Restaurar el respaldo MySQL

Requiere el cliente de MySQL/MariaDB. En XAMPP suele estar en
`C:\xampp\mysql\bin\mysql.exe`.

```bash
# 1. Crear la base
mysql -u root -e "CREATE DATABASE ebim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Importar el respaldo
mysql -u root ebim < database/ebim_mysql.sql

# 3. Configurar .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=ebim
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Limpiar configuración y compilar assets
php artisan config:clear
npm install
npm run build

# 5. Iniciar el servidor
php artisan serve --host=127.0.0.1 --port=8123
```

El respaldo ya incluye la tabla `migrations`, por lo que **no** hay que ejecutar
`php artisan migrate` después de restaurarlo.

## Crear la base desde cero

Solo en una base vacía:

```bash
php artisan migrate --force
```

Para cargar los datos de demostración:

```bash
php artisan db:seed --force
```

> No uses `php artisan migrate:fresh` ni `migrate:fresh --seed` sobre una base con datos
> reales: elimina todas las tablas y vuelve a crearlas.

## Importar desde la copia SQLite

El importador exige que el origen ya tenga el esquema normalizado (`especialidad_id`,
`asignatura_id`, `parcial`) y que el destino sea MySQL con las migraciones aplicadas.

```bash
# El destino debe estar vacío o se debe confirmar con --force
php database/import_sqlite_to_mysql.php --force

# Con una ruta explícita
php database/import_sqlite_to_mysql.php \
  --source="database/database.normalized.sqlite" \
  --force
```

El script vacía únicamente las tablas del dominio, conserva IDs, fechas, contraseñas,
estados de horario y notas, y al final verifica:

- conteos origen/destino;
- claves foráneas huérfanas;
- franjas duplicadas por curso, día y hora de inicio;
- notas duplicadas por estudiante, asignatura, gestión y parcial.

También deja el `AUTO_INCREMENT` de cada tabla en el último ID importado más uno, sin
conservar los saltos de cargas anteriores.

Si el SQLite de origen todavía tiene el esquema antiguo, primero hay que migrar una copia:

```bash
copy database\database.sqlite database\database.normalized.sqlite
set DB_DATABASE=%CD%\database\database.normalized.sqlite
php artisan migrate --force
```

En PowerShell:

```powershell
Copy-Item database\database.sqlite database\database.normalized.sqlite
$env:DB_DATABASE = (Resolve-Path database\database.normalized.sqlite).Path
php artisan migrate --force
```

## Seguridad para producción

La configuración actual usa `root` sin contraseña porque es el entorno local de XAMPP.
Antes de publicar el sitio:

1. Crear un usuario dedicado, por ejemplo `ebim_app`.
2. Darle solo los permisos necesarios sobre `ebim` (`SELECT`, `INSERT`, `UPDATE`, `DELETE`,
   `CREATE`, `ALTER`, `INDEX`, `REFERENCES`).
3. Asignar una contraseña fuerte y actualizar `.env`.
4. No versionar `.env` ni el respaldo con datos reales.
5. Programar respaldos con `mysqldump` y probar la restauración periódicamente.

## Verificación rápida

```bash
mysql -u root ebim -e "SELECT COUNT(*) FROM users; SELECT COUNT(*) FROM horarios; SELECT ROUND(AVG(nota),2) FROM notas;"
php artisan test
```

Resultado esperado de las pruebas: **72 pruebas aprobadas**.
