<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    private function estudiante(): User
    {
        return User::create([
            'name' => 'Estudiante de Prueba',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'estudiante',
        ]);
    }

    private function admin(): User
    {
        return User::create([
            'name' => 'Administrador de Prueba',
            'email' => 'admin@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'admin',
        ]);
    }

    private function director(): User
    {
        return User::create([
            'name' => 'Director de Prueba',
            'email' => 'director@example.com',
            'password' => Hash::make('secreto123'),
            'role' => 'director',
        ]);
    }

    public function test_un_estudiante_ingresa_y_es_redirigido_al_portal(): void
    {
        $estudiante = $this->estudiante();

        $this->post('/ingresar', [
            'email' => $estudiante->email,
            'password' => 'secreto123',
        ])->assertRedirect(route('portal.index'));

        $this->assertAuthenticatedAs($estudiante);
    }

    public function test_un_estudiante_ingresa_con_su_codigo_y_contrasena_generada(): void
    {
        $estudiante = User::create([
            'name' => 'Nayra Pérez',
            'role' => 'estudiante',
            'codigo' => '6509',
            'carnet' => '7084785',
            'password' => '7084785nayraperez',
        ]);

        $this->post('/ingresar', [
            'login' => '6509',
            'password' => '7084785nayraperez',
        ])->assertRedirect(route('portal.index'));

        $this->assertAuthenticatedAs($estudiante);
    }

    public function test_la_contrasena_inicial_se_genera_del_carnet_y_el_nombre(): void
    {
        $this->assertSame(
            '7084785josequia',
            User::generarPasswordEstudiante('7084785', 'José Raúl Quia Calizaya')
        );

        $this->assertSame(
            '7123456nayraperez',
            User::generarPasswordEstudiante('7123456', 'Nayra Pérez')
        );
    }

    public function test_un_codigo_incorrecto_no_autentica(): void
    {
        User::create([
            'name' => 'Nayra Pérez',
            'role' => 'estudiante',
            'codigo' => '6509',
            'carnet' => '7084785',
            'password' => '7084785nayraperez',
        ]);

        $this->post('/ingresar', [
            'login' => '9999',
            'password' => '7084785nayraperez',
        ])->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_el_login_exige_codigo_o_correo_y_contrasena(): void
    {
        $this->from('/ingresar')
            ->post('/ingresar', [
                'login' => '',
                'password' => '',
            ])
            ->assertRedirect('/ingresar')
            ->assertSessionHasErrors([
                'login' => 'Ingrese su código de estudiante o correo electrónico.',
                'password' => 'La contraseña es obligatoria.',
            ]);
    }

    public function test_un_admin_ingresa_y_es_redirigido_al_panel(): void
    {
        $admin = $this->admin();

        $this->post('/ingresar', [
            'login' => $admin->email,
            'password' => 'secreto123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_un_director_academico_ingresa_y_es_redirigido_al_panel(): void
    {
        $director = $this->director();

        $this->post('/ingresar', [
            'email' => $director->email,
            'password' => 'secreto123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($director);
    }

    public function test_credenciales_invalidas_no_autentican(): void
    {
        $estudiante = $this->estudiante();

        $this->post('/ingresar', [
            'email' => $estudiante->email,
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_un_estudiante_no_puede_acceder_al_panel_de_administracion(): void
    {
        $estudiante = $this->estudiante();

        $this->actingAs($estudiante)->get('/admin')
            ->assertForbidden()
            ->assertSee('Acceso denegado');
    }

    public function test_un_recurso_inexistente_muestra_la_pagina_de_error_del_sitio(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/docentes/999999/edit')
            ->assertNotFound()
            ->assertSee('Página no encontrada');
    }

    public function test_un_invitado_no_puede_acceder_al_portal(): void
    {
        $this->get('/portal')->assertRedirect(route('login'));
    }

    public function test_un_admin_puede_acceder_al_panel_de_administracion(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_un_estudiante_que_intenta_entrar_al_panel_es_enviado_a_su_portal(): void
    {
        $estudiante = $this->estudiante();

        // Sin sesión, al pedir el panel se guarda /admin como destino.
        $this->get('/admin')->assertRedirect(route('login'));

        // Al iniciar sesión no debe redirigirse al panel (donde recibiría un
        // 403), sino a su propio portal.
        $this->post('/ingresar', [
            'email' => $estudiante->email,
            'password' => 'secreto123',
        ])->assertRedirect(route('portal.index'));

        $this->assertAuthenticatedAs($estudiante);
    }

    public function test_un_admin_que_intenta_entrar_al_portal_es_enviado_al_panel(): void
    {
        $admin = $this->admin();

        $this->get('/portal')->assertRedirect(route('login'));

        $this->post('/ingresar', [
            'email' => $admin->email,
            'password' => 'secreto123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }
}
