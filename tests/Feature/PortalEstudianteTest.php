<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalEstudianteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Publica el horario del curso del estudiante: el rector ya lo aprobó.
     */
    private function publicarHorario(User $estudiante): void
    {
        $curso = $estudiante->curso;

        $curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Instrumento principal',
            'docente' => 'Docente de Prueba',
            'ambiente' => 'Aula 1',
        ]);

        $curso->estado_horario = Curso::ESTADO_APROBADO;
        $curso->save();
    }

    public function test_el_portal_muestra_las_notas_y_el_horario_del_estudiante(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->publicarHorario($estudiante);

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Mis notas')
            ->assertSee('Mi horario')
            ->assertSee('Instrumento principal')
            ->assertSee('Promedio');
    }

    public function test_las_notas_se_agrupan_por_asignatura_con_los_cuatro_parciales_y_nota_final(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $estudiante->notas()->create([
            'asignatura' => 'Lenguaje musical',
            'gestion' => '2026',
            'parcial' => 3,
            'nota' => 81,
        ]);

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('1er Parcial')
            ->assertSee('2do Parcial')
            ->assertSee('3er Parcial')
            ->assertSee('4to Parcial')
            ->assertSee('Nota final')
            ->assertSee('Gestión:')
            ->assertSee('81.00')
            ->assertSee('78.67')
            ->assertSee('Promedio de la gestión')
            ->assertDontSee('Bimestre')
            ->assertDontSee('>Gestión</th>', false);
    }

    public function test_el_portal_ofrece_los_dos_documentos_imprimibles(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Imprimir calificaciones')
            ->assertSee('Imprimir carta')
            ->assertSee('Para tu conocimiento')
            ->assertSee('Para trámites')
            ->assertSee('/portal/calificaciones?gestion=2026')
            ->assertSee('/portal/carta-notas?gestion=2026');
    }

    public function test_el_estudiante_puede_imprimir_sus_calificaciones_actuales(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)
            ->get('/portal/calificaciones?gestion=2026')
            ->assertOk()
            ->assertSee('Calificaciones actuales')
            ->assertSee($estudiante->name)
            ->assertSee($estudiante->codigo)
            ->assertSee('1er Parcial')
            ->assertSee('4to Parcial')
            ->assertSee('Nota final')
            ->assertSee('Promedio de la gestión')
            ->assertSee('82.50')
            ->assertSee('Imprimir');
    }

    public function test_un_administrador_no_puede_imprimir_las_calificaciones(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)
            ->get('/portal/calificaciones')
            ->assertForbidden();
    }

    public function test_el_estudiante_puede_imprimir_su_carta_de_solicitud_de_notas(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)
            ->get('/portal/carta-notas?gestion=2026')
            ->assertOk()
            ->assertSee('Solicitud de notas')
            ->assertSee($estudiante->name)
            ->assertSee($estudiante->codigo)
            ->assertSee('cursante del curso')
            ->assertSee('2 &quot;A&quot;', false)
            ->assertSee('2026')
            ->assertSee('Imprimir');
    }

    public function test_la_carta_usa_la_ultima_gestion_si_la_consultada_no_existe(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)
            ->get('/portal/carta-notas?gestion=1999')
            ->assertOk()
            ->assertSee('2026')
            ->assertDontSee('1999');
    }

    public function test_la_carta_solo_muestra_los_datos_del_estudiante_autenticado(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();
        $otro = User::where('email', 'estudiante2@ebim.edu.bo')->firstOrFail();

        $this->actingAs($otro)
            ->get('/portal/carta-notas')
            ->assertOk()
            ->assertSee($otro->name)
            ->assertDontSee($estudiante->name);
    }

    public function test_un_administrador_no_puede_imprimir_la_carta_de_notas(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)
            ->get('/portal/carta-notas')
            ->assertForbidden();
    }

    public function test_el_portal_muestra_el_perfil_del_estudiante(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee($estudiante->codigo)
            ->assertSee($estudiante->email);
    }

    public function test_el_portal_muestra_el_horario_del_curso_del_estudiante(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->publicarHorario($estudiante);

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Horario del curso')
            ->assertSee('2 "A"')
            ->assertSee('Lunes')
            ->assertSee('Viernes')
            ->assertDontSee('Sábado')
            ->assertSee('Instrumento principal');
    }

    public function test_el_portal_no_publica_un_horario_que_el_rector_todavia_no_aprobo(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        // El director académico ya cargó el horario, pero sigue en revisión.
        $estudiante->curso->horarios()->create([
            'dia' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'asignatura' => 'Asignatura solo del horario',
            'docente' => 'Docente de Prueba',
        ]);
        $estudiante->curso->estado_horario = Curso::ESTADO_ENVIADO;
        $estudiante->curso->save();

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('El horario de tu curso todavía no fue publicado')
            ->assertDontSee('Asignatura solo del horario');
    }

    public function test_el_estudiante_esta_asignado_a_un_curso(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->assertNotNull($estudiante->curso);
        $this->assertSame('2 "A"', $estudiante->curso->nombre);
    }

    public function test_un_estudiante_sin_curso_ve_un_aviso_en_lugar_del_horario(): void
    {
        $estudiante = User::create([
            'name' => 'Sin Curso',
            'email' => 'sincurso@example.com',
            'password' => 'secreto123',
            'role' => 'estudiante',
        ]);

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Todavía no se asignó un curso a tu cuenta');
    }

    public function test_un_estudiante_solo_ve_su_propia_informacion(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();
        $otro = User::where('email', 'estudiante2@ebim.edu.bo')->firstOrFail();

        $estudiante->notas()->create([
            'asignatura' => 'Asignatura Privada',
            'gestion' => '2026',
            'parcial' => 1,
            'nota' => 100,
        ]);

        $otro->notas()->create([
            'asignatura' => 'Nota Del Otro Estudiante',
            'gestion' => '2026',
            'parcial' => 1,
            'nota' => 10,
        ]);

        $respuesta = $this->actingAs($estudiante)->get('/portal')->assertOk();

        $respuesta->assertSee('Asignatura Privada');
        $respuesta->assertDontSee('Nota Del Otro Estudiante');
    }
}
