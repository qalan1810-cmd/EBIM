<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    private function estudiante(): User
    {
        $this->seed();

        return User::where('email', 'estudiante@ebim.edu.bo')->firstOrFail();
    }

    private function fotoFalsa(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'perfil.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Y9Zl1sAAAAASUVORK5CYII=')
        );
    }

    public function test_un_invitado_no_puede_entrar_a_configuracion(): void
    {
        $this->get('/perfil')->assertRedirect(route('login'));
    }

    public function test_el_estudiante_ve_su_configuracion_con_foto_predeterminada(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->get('/perfil')
            ->assertOk()
            ->assertSee('Configuración')
            ->assertSee('Datos personales')
            ->assertSee('Foto de perfil')
            ->assertSee('/assets/ebim/perfiles/default-estudiante.svg')
            ->assertSee($estudiante->codigo)
            ->assertSee('Cambiar contraseña');
    }

    public function test_el_estudiante_puede_actualizar_sus_datos(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => 'María Elena Condori',
                'email' => 'maria@example.com',
            ])
            ->assertRedirect(route('perfil.edit'))
            ->assertSessionHas('exito');

        $estudiante->refresh();

        $this->assertSame('María Elena Condori', $estudiante->name);
        $this->assertSame('maria@example.com', $estudiante->email);
    }

    public function test_el_estudiante_puede_cambiar_su_foto_de_perfil(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'foto' => $this->fotoFalsa(),
            ])
            ->assertRedirect(route('perfil.edit'))
            ->assertSessionHasNoErrors();

        $estudiante->refresh();

        $this->assertNotNull($estudiante->foto);
        $this->assertStringStartsWith('assets/ebim/perfiles/', $estudiante->foto);

        $ruta = public_path($estudiante->foto);

        $this->assertFileExists($ruta);

        $this->actingAs($estudiante)
            ->get('/portal')
            ->assertOk()
            ->assertSee('/'.$estudiante->foto);

        @unlink($ruta);
    }

    public function test_al_reemplazar_la_foto_se_borra_la_anterior(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'foto' => $this->fotoFalsa(),
            ])
            ->assertSessionHasNoErrors();

        $estudiante->refresh();
        $rutaAnterior = public_path($estudiante->foto);

        $this->assertFileExists($rutaAnterior);

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'foto' => $this->fotoFalsa(),
            ])
            ->assertSessionHasNoErrors();

        $estudiante->refresh();
        $rutaNueva = public_path($estudiante->foto);

        $this->assertNotSame($rutaAnterior, $rutaNueva);
        $this->assertFileDoesNotExist($rutaAnterior);
        $this->assertFileExists($rutaNueva);

        @unlink($rutaNueva);
    }

    public function test_el_estudiante_puede_quitar_su_foto(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'foto' => $this->fotoFalsa(),
            ])
            ->assertSessionHasNoErrors();

        $estudiante->refresh();
        $ruta = public_path($estudiante->foto);

        $this->assertFileExists($ruta);

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'eliminar_foto' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertNull($estudiante->fresh()->foto);
        $this->assertFileDoesNotExist($ruta);
    }

    public function test_el_estudiante_puede_cambiar_su_contrasena(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'password_actual' => '7000001mariacondori',
                'password' => 'nuevaClave123',
                'password_confirmation' => 'nuevaClave123',
            ])
            ->assertRedirect(route('perfil.edit'))
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('nuevaClave123', $estudiante->fresh()->password));
    }

    public function test_la_contrasena_actual_es_obligatoria_para_cambiarla(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)
            ->put('/perfil', [
                'name' => $estudiante->name,
                'email' => $estudiante->email,
                'password' => 'nuevaClave123',
                'password_confirmation' => 'nuevaClave123',
            ])
            ->assertSessionHasErrors('password_actual');

        $this->assertFalse(Hash::check('nuevaClave123', $estudiante->fresh()->password));
    }

    public function test_el_personal_ve_su_foto_predeterminada_y_debe_conservar_correo(): void
    {
        $this->seed();

        $admin = User::where('email', 'rector@ebim.edu.bo')->firstOrFail();

        $this->actingAs($admin)
            ->get('/perfil')
            ->assertOk()
            ->assertSee('/assets/ebim/perfiles/default-personal.svg');

        $this->actingAs($admin)
            ->put('/perfil', [
                'name' => $admin->name,
                'email' => '',
            ])
            ->assertSessionHasErrors('email');
    }
}
