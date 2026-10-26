<?php

namespace Tests\Feature;

use App\Models\Asignatura;
use App\Models\Contenido;
use App\Models\Curso;
use App\Models\Especialidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'admin',
        ]);
    }

    private function director(): User
    {
        return User::create([
            'name' => 'Director Académico',
            'email' => 'director@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'director',
        ]);
    }

    private function estudiante(): User
    {
        return User::create([
            'name' => 'Estudiante',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'estudiante',
        ]);
    }

    private function especialidad(string $nombre): int
    {
        return (int) Especialidad::where('nombre', $nombre)->value('id');
    }

    private function asignatura(string $nombre): int
    {
        return Asignatura::firstOrCreate(['nombre' => $nombre])->id;
    }

    public function test_el_panel_muestra_el_resumen(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk()->assertSee('Panel de Administración');
    }

    public function test_se_puede_registrar_un_docente(): void
    {
        $this->actingAs($this->admin())->post('/admin/docentes', [
            'nombre' => 'Docente Nuevo',
            'cargo' => 'Docente de Prueba',
            'especialidad' => 'Percusión',
            'formacion' => 'Técnico Superior en Música.',
            'experiencia' => 'Cinco años de experiencia.',
            'orden' => 1,
            'activo' => 1,
        ])->assertRedirect(route('admin.docentes.index'));

        $this->assertDatabaseHas('docentes', ['nombre' => 'Docente Nuevo', 'activo' => true]);
    }

    public function test_se_puede_actualizar_la_informacion_institucional(): void
    {
        foreach (['historia', 'historia_resumen', 'cronologia', 'sobre_intro', 'rector', 'mision', 'vision', 'valores', 'requisitos', 'pasos_inscripcion'] as $clave) {
            Contenido::create(['clave' => $clave, 'titulo' => 'Viejo', 'cuerpo' => 'Viejo']);
        }

        $this->actingAs($this->admin())->put('/admin/contenidos', [
            // Se incluyen los campos del formulario real para evitar
            // regresiones con la validación de los textos institucionales.
            '_token' => 'token-de-prueba',
            '_method' => 'PUT',
            'historia' => ['titulo' => 'Nuestra historia', 'cuerpo' => 'Contenido actualizado.'],
            'historia_resumen' => ['titulo' => 'Nuestra historia', 'cuerpo' => 'Resumen actualizado.'],
            'cronologia' => ['titulo' => 'Cronología', 'cuerpo' => "2009 | Hito | Detalle"],
            'sobre_intro' => ['titulo' => 'Sobre EBIM', 'cuerpo' => 'Texto inicial actualizado.'],
            'rector' => ['titulo' => 'Rector de prueba', 'cuerpo' => 'Formación de prueba.', 'foto' => '/assets/ebim/prueba.jpg'],
            'mision' => ['titulo' => 'Misión', 'cuerpo' => 'Misión actualizada.'],
            'vision' => ['titulo' => 'Visión', 'cuerpo' => 'Visión actualizada.'],
            'valores' => ['titulo' => 'Valores', 'cuerpo' => 'Valores actualizados.'],
            'requisitos' => ['titulo' => 'Requisitos', 'cuerpo' => "Uno\nDos"],
            'pasos_inscripcion' => ['titulo' => 'Pasos', 'cuerpo' => "Paso uno\nPaso dos"],
        ])->assertRedirect(route('admin.contenidos.edit'))
            ->assertSessionHas('exito');

        $this->assertDatabaseHas('contenidos', [
            'clave' => 'mision',
            'titulo' => 'Misión',
            'cuerpo' => 'Misión actualizada.',
        ]);

        $this->assertDatabaseHas('contenidos', [
            'clave' => 'rector',
            'titulo' => 'Rector de prueba',
            'foto' => '/assets/ebim/prueba.jpg',
        ]);
    }

    public function test_se_puede_registrar_una_nota(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($this->admin())->post('/admin/notas', [
            'user_id' => $estudiante->id,
            'asignatura' => 'Lenguaje musical',
            'gestion' => '2026',
            'parcial' => 1,
            'nota' => 87,
        ])->assertRedirect(route('admin.notas.estudiante', ['estudiante' => $estudiante->id, 'gestion' => '2026']));

        $this->assertDatabaseHas('notas', [
            'user_id' => $estudiante->id,
            'asignatura_id' => $this->asignatura('Lenguaje musical'),
            'parcial' => 1,
            'nota' => 87,
        ]);

        $this->assertDatabaseHas('asignaturas', ['nombre' => 'Lenguaje musical']);
    }

    public function test_no_se_puede_repetir_una_nota_en_el_mismo_parcial(): void
    {
        $estudiante = $this->estudiante();
        $asignaturaId = $this->asignatura('Lenguaje musical');

        $estudiante->notas()->create([
            'asignatura_id' => $asignaturaId,
            'gestion' => '2026',
            'parcial' => 1,
            'nota' => 70,
        ]);

        $this->actingAs($this->admin())->post('/admin/notas', [
            'user_id' => $estudiante->id,
            'asignatura' => 'Lenguaje musical',
            'gestion' => '2026',
            'parcial' => 1,
            'nota' => 90,
        ])->assertSessionHasErrors('parcial');

        $this->assertDatabaseCount('notas', 1);
    }

    public function test_la_lista_de_notas_muestra_a_los_estudiantes_por_curso(): void
    {
        $cursoA = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);
        $cursoB = Curso::create(['nombre' => '3 "A"', 'orden' => 2]);

        $ana = User::create([
            'name' => 'Ana Pinedo',
            'email' => 'ana@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'estudiante',
            'curso_id' => $cursoA->id,
            'especialidad_id' => $this->especialidad('Guitarra'),
        ]);

        User::create([
            'name' => 'Luis Quispe',
            'email' => 'luis@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'estudiante',
            'curso_id' => $cursoB->id,
            'especialidad_id' => $this->especialidad('Piano'),
        ]);

        $ana->notas()->create(['asignatura' => 'Instrumento principal', 'gestion' => '2026', 'parcial' => 1, 'nota' => 80]);
        $ana->notas()->create(['asignatura' => 'Instrumento principal', 'gestion' => '2026', 'parcial' => 2, 'nota' => 90]);

        $this->actingAs($this->admin())
            ->get('/admin/notas?curso=' . $cursoA->id)
            ->assertOk()
            ->assertSee('Curso')
            ->assertSee('Estudiante')
            ->assertSee('Especialidad')
            ->assertSee('Nota promedio')
            ->assertSee('Acciones')
            ->assertSee('Ana Pinedo')
            ->assertSee('Guitarra')
            ->assertSee('85.00')
            ->assertSee('Ver notas')
            ->assertDontSee('Luis Quispe')
            ->assertDontSee('Filtrar por estudiante');
    }

    public function test_el_rector_consulta_las_notas_de_un_estudiante(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $estudiante = User::create([
            'name' => 'Ana Pinedo',
            'email' => 'ana@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'estudiante',
            'curso_id' => $curso->id,
            'especialidad_id' => $this->especialidad('Guitarra'),
        ]);

        $estudiante->notas()->create(['asignatura' => 'Lenguaje musical', 'gestion' => '2026', 'parcial' => 1, 'nota' => 77]);

        $this->actingAs($this->admin())
            ->get('/admin/notas/estudiante/' . $estudiante->id . '?gestion=2026')
            ->assertOk()
            ->assertSee('Ana Pinedo')
            ->assertSee('Guitarra')
            ->assertSee('Lenguaje musical')
            ->assertSee('77.00')
            ->assertSee('Nueva nota')
            ->assertSee('Promedio de la gestión');
    }

    public function test_se_puede_registrar_un_horario(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $this->actingAs($this->director())->post('/admin/horarios', [
            'curso_id' => $curso->id,
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
            'ambiente' => 'Aula 1',
        ])->assertRedirect(route('admin.horarios.index', ['curso' => $curso->id]));

        $this->assertDatabaseHas('horarios', [
            'curso_id' => $curso->id,
            'dia' => 'Lunes',
            'asignatura_id' => $this->asignatura('Instrumento principal'),
        ]);
    }

    public function test_no_se_puede_registrar_un_horario_el_sabado(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $this->actingAs($this->director())->post('/admin/horarios', [
            'curso_id' => $curso->id,
            'dia' => 'Sábado',
            'hora_inicio' => '08:30',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ])->assertSessionHasErrors('dia');

        $this->assertDatabaseCount('horarios', 0);
    }

    public function test_no_se_puede_repetir_la_franja_de_un_curso(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $datos = [
            'curso_id' => $curso->id,
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ];

        $director = $this->director();

        $this->actingAs($director)->post('/admin/horarios', $datos)->assertRedirect();

        $this->actingAs($director)
            ->post('/admin/horarios', array_merge($datos, ['asignatura' => 'Otra asignatura']))
            ->assertSessionHasErrors('dia');

        $this->assertDatabaseCount('horarios', 1);
    }

    public function test_el_rector_no_elabora_horarios(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        // El rector aprueba, pero no arma la rejilla: eso es del director académico.
        $this->actingAs($this->admin())->post('/admin/horarios', [
            'curso_id' => $curso->id,
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ])->assertForbidden();

        $this->assertDatabaseCount('horarios', 0);
    }

    public function test_solo_el_rector_aprueba_el_horario(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $director = $this->director();

        $this->actingAs($director)
            ->post("/admin/horarios/{$curso->id}/enviar")
            ->assertRedirect(route('admin.horarios.index', ['curso' => $curso->id]));

        $this->assertSame(Curso::ESTADO_ENVIADO, $curso->fresh()->estado_horario);

        // El director académico no puede aprobar su propio horario.
        $this->actingAs($director)
            ->post("/admin/horarios/{$curso->id}/aprobar")
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->post("/admin/horarios/{$curso->id}/aprobar")
            ->assertRedirect(route('admin.horarios.index', ['curso' => $curso->id]));

        $curso->refresh();

        $this->assertSame(Curso::ESTADO_APROBADO, $curso->estado_horario);
        $this->assertNotNull($curso->horario_revisado_en);
    }

    public function test_no_se_puede_enviar_un_horario_sin_asignaturas(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $this->actingAs($this->director())
            ->post("/admin/horarios/{$curso->id}/enviar")
            ->assertSessionHas('error');

        $this->assertSame(Curso::ESTADO_BORRADOR, $curso->fresh()->estado_horario);
    }

    public function test_un_horario_enviado_no_se_puede_modificar(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $curso->estado_horario = Curso::ESTADO_ENVIADO;
        $curso->save();

        $this->actingAs($this->director())->post('/admin/horarios', [
            'curso_id' => $curso->id,
            'dia' => 'Martes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Otra asignatura',
            'docente' => 'Docente de Prueba',
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('horarios', 1);
    }

    public function test_el_rector_devuelve_el_horario_con_observaciones(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $horario = $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $curso->estado_horario = Curso::ESTADO_ENVIADO;
        $curso->save();

        $this->actingAs($this->admin())
            ->post("/admin/horarios/{$curso->id}/rechazar", ['observacion' => 'Falta la carga horaria de lenguaje musical.'])
            ->assertRedirect(route('admin.horarios.index', ['curso' => $curso->id]));

        $curso->refresh();

        $this->assertSame(Curso::ESTADO_RECHAZADO, $curso->estado_horario);
        $this->assertSame('Falta la carga horaria de lenguaje musical.', $curso->horario_observacion);

        // Ya devuelto, el director académico puede corregirlo otra vez.
        $this->actingAs($this->director())
            ->put("/admin/horarios/{$horario->id}", [
                'curso_id' => $curso->id,
                'dia' => 'Martes',
                'hora_inicio' => '08:00',
                'hora_fin' => '10:00',
                'asignatura' => 'Instrumento principal',
                'docente' => 'Docente de Prueba',
            ])->assertRedirect(route('admin.horarios.index', ['curso' => $curso->id]));

        $this->assertSame('Martes', $horario->fresh()->dia);
    }

    public function test_el_rector_necesita_una_observacion_para_devolver_el_horario(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $curso->estado_horario = Curso::ESTADO_ENVIADO;
        $curso->save();

        $this->actingAs($this->admin())
            ->post("/admin/horarios/{$curso->id}/rechazar", ['observacion' => ''])
            ->assertSessionHasErrors('observacion');

        $this->assertSame(Curso::ESTADO_ENVIADO, $curso->fresh()->estado_horario);
    }

    public function test_el_rector_ve_los_horarios_pendientes_de_aprobacion(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $curso->estado_horario = Curso::ESTADO_ENVIADO;
        $curso->horario_enviado_en = now();
        $curso->horario_enviado_por = $this->director()->id;
        $curso->save();

        $this->actingAs($this->admin())
            ->get('/admin/horarios')
            ->assertOk()
            ->assertSee('Horarios pendientes de aprobación')
            ->assertSee('2 "A"');
    }

    public function test_el_director_no_entra_a_las_secciones_del_rector(): void
    {
        $director = $this->director();

        // Solo entra a su panel y a la sección de horarios.
        $this->actingAs($director)->get('/admin')->assertOk()->assertSee('Panel de Administración');
        $this->actingAs($director)->get('/admin/horarios')->assertOk();

        $this->actingAs($director)->get('/admin/docentes')->assertForbidden();
        $this->actingAs($director)->get('/admin/usuarios')->assertForbidden();
        $this->actingAs($director)->get('/admin/contenidos')->assertForbidden();

        // La navegación del panel no le ofrece lo que no puede abrir.
        $this->actingAs($director)
            ->get('/admin/horarios')
            ->assertOk()
            ->assertDontSee('Usuarios');
    }

    public function test_se_puede_crear_un_curso(): void
    {
        $this->actingAs($this->director())
            ->post('/admin/cursos', ['nombre' => '3 "B"', 'orden' => 4])
            ->assertRedirect();

        $this->assertDatabaseHas('cursos', ['nombre' => '3 "B"', 'orden' => 4]);
    }

    public function test_no_se_puede_eliminar_un_curso_con_horarios(): void
    {
        $curso = Curso::create(['nombre' => '2 "A"', 'orden' => 1]);

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
        ]);

        $this->actingAs($this->director())
            ->delete("/admin/cursos/{$curso->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('cursos', ['id' => $curso->id]);
    }

    public function test_se_puede_eliminar_un_curso_sin_horarios_ni_estudiantes(): void
    {
        $curso = Curso::create(['nombre' => '4 "A"', 'orden' => 9]);

        $this->actingAs($this->director())
            ->delete("/admin/cursos/{$curso->id}")
            ->assertRedirect(route('admin.horarios.index'));

        $this->assertDatabaseMissing('cursos', ['id' => $curso->id]);
    }

    public function test_se_puede_registrar_un_usuario(): void
    {
        $especialidadId = $this->especialidad('Guitarra');

        $this->actingAs($this->admin())->post('/admin/usuarios', [
            'name' => 'Nuevo Estudiante',
            'email' => 'nuevo@example.com',
            'password' => 'secreto123',
            'role' => 'estudiante',
            'codigo' => '2026-100',
            'especialidad' => 'Guitarra',
        ])->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@example.com',
            'role' => 'estudiante',
            'especialidad_id' => $especialidadId,
        ]);
    }

    public function test_no_se_puede_eliminar_el_propio_usuario(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$admin->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_el_mensaje_de_exito_se_muestra_en_el_panel(): void
    {
        $this->actingAs($this->admin())
            ->withSession(['exito' => 'PRUEBA_FLASH'])
            ->get('/admin')
            ->assertOk()
            ->assertSee('PRUEBA_FLASH');
    }

    public function test_el_mensaje_de_exito_sobrevive_al_redirect_de_contenidos(): void
    {
        foreach (['historia', 'historia_resumen', 'cronologia', 'sobre_intro', 'rector', 'mision', 'vision', 'valores', 'requisitos', 'pasos_inscripcion'] as $clave) {
            Contenido::create(['clave' => $clave, 'titulo' => 'Viejo', 'cuerpo' => 'Viejo']);
        }

        $payload = ['_token' => 'token-de-prueba', '_method' => 'PUT'];

        foreach (['historia', 'historia_resumen', 'cronologia', 'sobre_intro', 'rector', 'mision', 'vision', 'valores', 'requisitos', 'pasos_inscripcion'] as $clave) {
            $payload[$clave] = ['titulo' => 'T ' . $clave, 'cuerpo' => 'C ' . $clave];
        }

        $this->actingAs($this->admin())
            ->put('/admin/contenidos', $payload)
            ->assertRedirect(route('admin.contenidos.edit'));

        $this->get('/admin/contenidos')
            ->assertOk()
            ->assertSee('Información institucional actualizada.');
    }
}
