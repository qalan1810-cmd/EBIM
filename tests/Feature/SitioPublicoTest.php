<?php

namespace Tests\Feature;

use App\Models\Contenido;
use App\Models\Docente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitioPublicoTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_muestra_informacion_institucional(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Escuela Boliviana Intercultural de Música')
            ->assertSee('Portal del Estudiante');
    }

    public function test_las_paginas_publicas_responden_correctamente(): void
    {
        $this->seed();

        foreach (['/sobre-ebim', '/oferta-academica', '/inscripcion', '/docentes', '/contacto', '/terminos', '/privacidad'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_la_pagina_de_docentes_lista_el_plantel(): void
    {
        $this->seed();

        $docente = Docente::query()->firstOrFail();

        $this->get('/docentes')
            ->assertOk()
            ->assertSee($docente->nombre);
    }

    public function test_la_inscripcion_muestra_requisitos_y_pasos(): void
    {
        $this->seed();

        $requisitos = Contenido::buscar('requisitos');

        $this->get('/inscripcion')
            ->assertOk()
            ->assertSee('Requisitos de inscripción')
            ->assertSee(trim(explode("\n", $requisitos->cuerpo)[0]));
    }

    public function test_una_ruta_inexistente_devuelve_404(): void
    {
        $this->get('/esta-ruta-no-existe')->assertNotFound();
    }

    public function test_las_paginas_publicas_no_muestran_miga_de_ruta(): void
    {
        $this->seed();

        foreach (['/', '/sobre-ebim', '/oferta-academica', '/inscripcion', '/docentes', '/contacto', '/terminos', '/privacidad'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('Ruta de navegación');
        }
    }

    public function test_la_portada_publica_un_resumen_corto_de_la_historia(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Nuestra historia')
            ->assertSee('Asociación de Bandas de Músicos Profesionales de Oruro')
            ->assertSee('D.S. 0009');
    }

    public function test_sobre_ebim_muestra_la_historia_en_linea_de_tiempo_y_la_rectoria(): void
    {
        $this->seed();

        $this->get('/sobre-ebim')
            ->assertOk()
            ->assertSee('Nuestra historia')
            ->assertSee('Inauguración')
            ->assertSee('Primeras clases')
            ->assertSee('Resolución Ministerial')
            ->assertSee('Nueva etapa')
            ->assertSee('Rectoría')
            ->assertSee('Arturo César Herrera Camargo');
    }

    public function test_sobre_ebim_no_repite_el_decreto_en_el_texto_inicial(): void
    {
        $this->seed();

        $this->get('/sobre-ebim')
            ->assertOk()
            ->assertSee('primera institución de formación musical intercultural de Latinoamérica');
    }

    public function test_la_direccion_anterior_redirige_a_sobre_ebim(): void
    {
        $this->seed();

        $this->get('/la-escuela')->assertRedirect('/sobre-ebim');
    }
}
