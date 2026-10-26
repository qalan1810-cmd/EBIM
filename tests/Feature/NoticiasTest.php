<?php

namespace Tests\Feature;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoticiasTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_publica_las_noticias_y_los_comunicados_visibles(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Noticias')
            ->assertSee('Comunicados')
            ->assertSee(Noticia::visibles()->deTipo(Noticia::TIPO_NOTICIA)->firstOrFail()->titulo)
            ->assertSee(Noticia::visibles()->deTipo(Noticia::TIPO_COMUNICADO)->firstOrFail()->titulo);
    }

    public function test_las_noticias_ocultas_no_se_publican(): void
    {
        $this->seed();
        Noticia::query()->update(['activo' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('17 años formando talento');
    }

    public function test_un_comunicado_se_publica_aparte_de_las_noticias(): void
    {
        $this->seed();

        // Una noticia no debe aparecer en el bloque de comunicados.
        Noticia::query()->update(['tipo' => Noticia::TIPO_NOTICIA]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Noticias')
            ->assertDontSee('Comunicados');
    }

    public function test_un_enlace_de_facebook_o_youtube_se_convierte_en_reproductor_incrustado(): void
    {
        // Videos: sí se incrustan.
        foreach ([
            'https://www.facebook.com/watch/?v=10153231379946729',
            'https://www.facebook.com/EBIMORURO/videos/1234567890/',
        ] as $enlace) {
            $this->assertStringContainsString(
                'plugins/video.php',
                (new Noticia(['enlace' => $enlace]))->urlIncrustable(),
                "El video de Facebook debería incrustarse: {$enlace}"
            );
        }

        foreach ([
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ',
            'https://www.youtube.com/shorts/dQw4w9WgXcQ',
        ] as $enlace) {
            $this->assertSame(
                'https://www.youtube.com/embed/dQw4w9WgXcQ',
                (new Noticia(['enlace' => $enlace]))->urlIncrustable()
            );
        }
    }

    public function test_una_publicacion_de_facebook_que_no_es_video_no_se_incrusta(): void
    {
        // Facebook no permite mostrar sus publicaciones de foto o texto fuera
        // de Facebook: el reproductor responde "Video Unavailable". Por eso
        // estas publicaciones deben quedar como enlace, nunca como reproductor.
        foreach ([
            'https://www.facebook.com/share/p/19gZf3FPiC/',
            'https://www.facebook.com/100063251977207/posts/1375630397888680/',
            'https://www.facebook.com/permalink.php?story_fbid=123&id=456',
        ] as $enlace) {
            $noticia = new Noticia(['enlace' => $enlace]);

            $this->assertNull($noticia->urlIncrustable(), "No debería incrustarse: {$enlace}");
            $this->assertFalse($noticia->tieneVideo());
        }
    }

    public function test_un_enlace_que_no_se_puede_incrustar_queda_como_enlace_normal(): void
    {
        $nota = new Noticia(['enlace' => 'https://lapatria.bo/nota-de-prensa/']);

        $this->assertNull($nota->urlIncrustable());
        $this->assertFalse($nota->tieneVideo());
    }

    public function test_un_visitante_no_puede_administrar_noticias(): void
    {
        $this->seed();

        $this->get('/admin/noticias')->assertRedirect(route('login'));
    }

    public function test_un_estudiante_no_puede_administrar_noticias(): void
    {
        $this->seed();

        $estudiante = User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();

        $this->actingAs($estudiante)->get('/admin/noticias')->assertForbidden();
    }

    public function test_el_titulo_el_tipo_y_la_fecha_son_obligatorios(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)
            ->from('/admin/noticias/create')
            ->post('/admin/noticias', [])
            ->assertRedirect('/admin/noticias/create')
            ->assertSessionHasErrors(['titulo', 'tipo', 'fecha']);
    }

    public function test_el_admin_puede_registrar_editar_y_eliminar_una_noticia(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)->post('/admin/noticias', [
            'titulo' => 'Noticia de prueba',
            'tipo' => Noticia::TIPO_COMUNICADO,
            'fecha' => '2026-10-18',
            'resumen' => 'Resumen de prueba.',
            'cuerpo' => 'Contenido de prueba.',
            'enlace' => 'https://youtu.be/dQw4w9WgXcQ',
            'orden' => 9,
            'activo' => 1,
        ])->assertRedirect(route('admin.noticias.index'));

        $this->assertDatabaseHas('noticias', [
            'titulo' => 'Noticia de prueba',
            'tipo' => Noticia::TIPO_COMUNICADO,
            'enlace' => 'https://youtu.be/dQw4w9WgXcQ',
            'activo' => true,
        ]);

        $noticia = Noticia::where('titulo', 'Noticia de prueba')->firstOrFail();

        // El video se muestra incrustado: el enlace se publica como reproductor.
        $this->get('/')->assertOk()->assertSee('youtube.com/embed/dQw4w9WgXcQ', false);

        $this->actingAs($admin)->put("/admin/noticias/{$noticia->id}", [
            'titulo' => 'Noticia de prueba actualizada',
            'tipo' => Noticia::TIPO_NOTICIA,
            'fecha' => '2026-10-18',
            'resumen' => 'Resumen actualizado.',
            'cuerpo' => 'Contenido actualizado.',
            'orden' => 9,
            'activo' => '',
        ])->assertRedirect(route('admin.noticias.index'));

        $this->assertDatabaseHas('noticias', [
            'id' => $noticia->id,
            'activo' => false,
            'tipo' => Noticia::TIPO_NOTICIA,
        ]);

        $this->actingAs($admin)->delete("/admin/noticias/{$noticia->id}")
            ->assertRedirect(route('admin.noticias.index'));

        $this->assertDatabaseMissing('noticias', ['id' => $noticia->id]);
    }

    public function test_el_admin_ve_el_listado_y_el_formulario(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)->get('/admin/noticias')
            ->assertOk()
            ->assertSee('Listado de publicaciones');

        $this->actingAs($admin)->get('/admin/noticias/create')
            ->assertOk()
            ->assertSee('Nueva publicación');
    }
}
