<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Panel del estudiante: consulta de notas y horarios (Histórico 5).
     */
    public function index(Request $request): View
    {
        $estudiante = $request->user();

        $gestiones = $this->gestionesDelEstudiante($estudiante);
        $gestionSeleccionada = $this->gestionSeleccionada($gestiones, $request->query('gestion'));

        $notas = $this->notasDe($estudiante, $gestionSeleccionada);

        // El horario es del curso del estudiante, no individual. El estudiante
        // solo lo ve cuando el rector ya lo aprobó.
        $curso = $estudiante->curso;
        $horarioAprobado = $curso !== null && $curso->estado_horario === Curso::ESTADO_APROBADO;

        return view('pages.portal.index', [
            'estudiante' => $estudiante,
            'gestiones' => $gestiones,
            'gestionSeleccionada' => $gestionSeleccionada,
            'parciales' => $this->parciales(),
            'filasNotas' => $this->filasPorAsignatura($notas),
            'promedio' => $this->promedioDe($notas),
            'curso' => $curso,
            'horarioAprobado' => $horarioAprobado,
            'rejilla' => $curso?->rejilla() ?? ['dias' => Curso::DIAS, 'franjas' => []],
        ]);
    }

    /**
     * Calificaciones actuales en una hoja imprimible.
     */
    public function calificaciones(Request $request): View
    {
        $estudiante = $request->user();

        $gestiones = $this->gestionesDelEstudiante($estudiante);
        $gestion = $this->gestionSeleccionada($gestiones, $request->query('gestion'));
        $notas = $this->notasDe($estudiante, $gestion);

        return view('pages.portal.calificaciones', [
            'estudiante' => $estudiante,
            'gestion' => $gestion,
            'fecha' => now(),
            'parciales' => $this->parciales(),
            'filasNotas' => $this->filasPorAsignatura($notas),
            'promedio' => $this->promedioDe($notas),
        ]);
    }

    /**
     * Carta de solicitud de notas para trámites.
     *
     * La carta usa los datos del estudiante autenticado y la gestión
     * consultada, sin recibir nombres ni códigos por la URL.
     */
    public function carta(Request $request): View
    {
        $estudiante = $request->user();

        $gestiones = $this->gestionesDelEstudiante($estudiante);
        $gestion = $this->gestionSeleccionada($gestiones, $request->query('gestion'));

        return view('pages.portal.carta', [
            'estudiante' => $estudiante,
            'condicion' => $this->condicion($estudiante),
            'gestion' => $gestion,
            'fecha' => now(),
        ]);
    }

    private function gestionesDelEstudiante(User $estudiante): Collection
    {
        return $estudiante->notas()->orderByDesc('gestion')->distinct()->pluck('gestion');
    }

    private function gestionSeleccionada(Collection $gestiones, mixed $solicitada): string
    {
        if (is_string($solicitada) && $gestiones->contains($solicitada)) {
            return $solicitada;
        }

        return $gestiones->first() ?? (string) now()->year;
    }

    private function notasDe(User $estudiante, string $gestion): Collection
    {
        return $estudiante->notas()
            ->with('asignaturaRel')
            ->where('notas.gestion', $gestion)
            ->ordenadoPorAsignatura()
            ->get();
    }

    /**
     * Etiquetas de los cuatro parciales.
     */
    private function parciales(): array
    {
        return [
            1 => '1er Parcial',
            2 => '2do Parcial',
            3 => '3er Parcial',
            4 => '4to Parcial',
        ];
    }

    /**
     * Una fila por asignatura: los cuatro parciales en columnas y la nota
     * final como promedio de los parciales registrados.
     */
    private function filasPorAsignatura(Collection $notas): Collection
    {
        $parciales = $this->parciales();

        return $notas
            ->groupBy('asignatura_id')
            ->map(function ($notasAsignatura) use ($parciales) {
                $porParcial = $notasAsignatura->keyBy('parcial');

                return [
                    'asignatura' => $notasAsignatura->first()->asignatura,
                    'notas' => array_map(
                        fn (int $parcial) => $porParcial->get($parcial),
                        array_keys($parciales),
                    ),
                    'final' => round($notasAsignatura->avg('nota'), 2),
                ];
            })
            ->values();
    }

    private function promedioDe(Collection $notas): ?float
    {
        return $notas->isEmpty() ? null : round($notas->avg('nota'), 2);
    }

    /**
     * " con código de estudiante …" y "cursante del curso …" según los datos.
     */
    private function condicion(User $estudiante): string
    {
        $condicion = '';

        if ($estudiante->codigo) {
            $condicion .= ', con código de estudiante ' . $estudiante->codigo;
        }

        if ($estudiante->curso) {
            $condicion .= ', cursante del curso ' . $estudiante->curso->nombre;
        }

        return $condicion;
    }
}
