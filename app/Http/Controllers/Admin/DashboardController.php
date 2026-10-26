<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contenido;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Nota;
use App\Models\Noticia;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Resumen del panel. El rector ve la administración completa y los horarios
     * pendientes de aprobación; el director académico ve el estado de los
     * horarios que debe elaborar.
     */
    public function index(): View
    {
        return view('pages.admin.dashboard', [
            'esRector' => auth()->user()->esAdmin(),
            'estadisticas' => [
                'docentes' => Docente::count(),
                'noticias' => Noticia::count(),
                'estudiantes' => User::where('role', 'estudiante')->count(),
                'notas' => Nota::count(),
                'contenidos' => Contenido::count(),
            ],
            'horarios' => [
                'cursos' => Curso::count(),
                'asignaturas' => Horario::count(),
                'borrador' => Curso::where('estado_horario', Curso::ESTADO_BORRADOR)->count(),
                'enviado' => Curso::where('estado_horario', Curso::ESTADO_ENVIADO)->count(),
                'aprobado' => Curso::where('estado_horario', Curso::ESTADO_APROBADO)->count(),
                'rechazado' => Curso::where('estado_horario', Curso::ESTADO_RECHAZADO)->count(),
            ],
            'pendientes' => Curso::where('estado_horario', Curso::ESTADO_ENVIADO)->ordenado()->with('enviadoPor')->get(),
            'devueltos' => Curso::where('estado_horario', Curso::ESTADO_RECHAZADO)->ordenado()->get(),
        ]);
    }
}
