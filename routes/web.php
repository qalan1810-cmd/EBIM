<?php

use App\Http\Controllers\Admin\ContenidoController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocenteController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\NotaController;
use App\Http\Controllers\Admin\NoticiaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\SitioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EBIM — Escuela Boliviana Intercultural de Música
|--------------------------------------------------------------------------
| Épica 1: Información de la institución (visitante y estudiante nuevo)
| Épica 2: Información de los docentes
| Épica 3: Administración de la información (personal autorizado)
| Épica 4: Información académica (notas y horarios de los estudiantes)
*/

// ---------------------------------------------------------------------------
// Sitio público
// ---------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sobre-ebim', [SitioController::class, 'laEscuela'])->name('sobre-ebim');
Route::get('/oferta-academica', [SitioController::class, 'ofertaAcademica'])->name('oferta-academica');
Route::get('/inscripcion', [SitioController::class, 'inscripcion'])->name('inscripcion');
Route::get('/docentes', [SitioController::class, 'docentes'])->name('docentes');
Route::get('/contacto', [SitioController::class, 'contacto'])->name('contacto');
Route::get('/terminos', [SitioController::class, 'terminos'])->name('terminos');
Route::get('/privacidad', [SitioController::class, 'privacidad'])->name('privacidad');

// Dirección anterior de la página "Sobre EBIM". Se conserva como redirección
// permanente para no romper los enlaces ya compartidos o guardados.
Route::permanentRedirect('/la-escuela', '/sobre-ebim');

// ---------------------------------------------------------------------------
// Acceso
// ---------------------------------------------------------------------------
Route::get('/ingresar', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/ingresar', [AuthController::class, 'login'])->middleware('guest');
Route::post('/salir', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ---------------------------------------------------------------------------
// Portal del estudiante — consulta de notas y horarios (Histórico 5)
// ---------------------------------------------------------------------------
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('index');

    // Documentos imprimibles. Solo el propio estudiante puede abrirlos: usan
    // los datos de la sesión y no reciben nombres ni códigos por la URL.
    Route::get('/calificaciones', [PortalController::class, 'calificaciones'])
        ->middleware('role:estudiante')
        ->name('calificaciones');

    Route::get('/carta-notas', [PortalController::class, 'carta'])
        ->middleware('role:estudiante')
        ->name('carta');
});

// ---------------------------------------------------------------------------
// Panel de administración — personal autorizado (Histórico 4)
//
// El rector (admin) administra todo el sistema y aprueba los horarios. El
// director académico (director) elabora los horarios de cada curso y los
// envía a aprobación.
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin,director'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Horarios: consulta y envío a aprobación (ambos roles).
    Route::get('horarios', [HorarioController::class, 'index'])->name('horarios.index');
    Route::post('horarios/{curso}/enviar', [HorarioController::class, 'enviar'])
        ->middleware('role:director')->name('horarios.enviar');

    // Elaboración del horario: solo el director académico.
    Route::middleware('role:director')->group(function () {
        Route::get('horarios/create', [HorarioController::class, 'create'])->name('horarios.create');
        Route::post('horarios', [HorarioController::class, 'store'])->name('horarios.store');
        Route::get('horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

        // Los cursos se administran desde la sección de horarios.
        Route::post('cursos', [CursoController::class, 'store'])->name('cursos.store');
        Route::put('cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');
        Route::delete('cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    });

    // Aprobación de horarios: solo el rector.
    Route::middleware('role:admin')->group(function () {
        Route::post('horarios/{curso}/aprobar', [HorarioController::class, 'aprobar'])->name('horarios.aprobar');
        Route::post('horarios/{curso}/rechazar', [HorarioController::class, 'rechazar'])->name('horarios.rechazar');
    });

    // Administración general del sistema: solo el rector.
    Route::middleware('role:admin')->group(function () {
        Route::resource('docentes', DocenteController::class)->except('show');
        Route::resource('noticias', NoticiaController::class)->except('show');

        Route::get('/contenidos', [ContenidoController::class, 'edit'])->name('contenidos.edit');
        Route::put('/contenidos', [ContenidoController::class, 'update'])->name('contenidos.update');

        Route::get('notas/estudiante/{estudiante}', [NotaController::class, 'estudiante'])->name('notas.estudiante');
        Route::resource('notas', NotaController::class)->except('show');
        Route::resource('usuarios', UsuarioController::class)->except('show');
    });
});

/*
|--------------------------------------------------------------------------
| Reserva de páginas — páginas legales estáticas
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('pages.placeholder', ['uri' => '/'.request()->path()], 404);
});
