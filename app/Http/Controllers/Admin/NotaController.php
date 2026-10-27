<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Registro y actualización de notas de los estudiantes (Histórico 5).
 *
 * El listado principal es por curso: una fila por estudiante con su
 * especialidad y su promedio. Las notas de cada estudiante se ven en su
 * propia página.
 */
class NotaController extends Controller
{
    public function index(Request $request): View
    {
        $cursos = Curso::ordenado()->get();
        $cursoId = is_string($request->query('curso')) && $request->query('curso') !== ''
            ? $request->query('curso')
            : null;

        $estudiantes = User::query()
            ->where('role', 'estudiante')
            ->whereNotNull('curso_id')
            ->with(['curso', 'especialidadRel'])
            ->when($cursoId, fn ($query) => $query->where('curso_id', $cursoId))
            ->orderBy('name')
            ->get();

        $gestiones = Nota::query()->orderByDesc('gestion')->distinct()->pluck('gestion');

        if ($gestiones->isEmpty()) {
            $gestiones = collect([(string) now()->year]);
        }

        $gestion = $this->gestionSeleccionada($request, $gestiones);

        $promedios = Nota::query()
            ->where('gestion', $gestion)
            ->whereIn('user_id', $estudiantes->pluck('id'))
            ->selectRaw('user_id, AVG(nota) as promedio')
            ->groupBy('user_id')
            ->pluck('promedio', 'user_id');

        return view('pages.admin.notas.index', [
            'cursos' => $cursos,
            'cursoId' => $cursoId,
            'estudiantes' => $estudiantes,
            'gestiones' => $gestiones,
            'gestion' => $gestion,
            'promedios' => $promedios,
        ]);
    }

    /**
     * Notas de un estudiante en la gestión consultada.
     */
    public function estudiante(Request $request, User $estudiante): View
    {
        abort_unless($estudiante->esEstudiante() && $estudiante->curso_id !== null, 404);

        $gestiones = $estudiante->notas()->orderByDesc('gestion')->distinct()->pluck('gestion');

        if ($gestiones->isEmpty()) {
            $gestiones = collect([(string) now()->year]);
        }

        $gestion = $this->gestionSeleccionada($request, $gestiones);

        $notas = $estudiante->notas()
            ->with('asignaturaRel')
            ->where('notas.gestion', $gestion)
            ->ordenadoPorAsignatura()
            ->paginate(20)
            ->withQueryString();

        $promedio = $estudiante->notas()->where('gestion', $gestion)->avg('nota');

        $cursoId = is_string($request->query('curso')) && $request->query('curso') !== ''
            ? $request->query('curso')
            : null;

        return view('pages.admin.notas.estudiante', [
            'estudiante' => $estudiante,
            'notas' => $notas,
            'gestiones' => $gestiones,
            'gestion' => $gestion,
            'promedio' => $promedio !== null ? round($promedio, 2) : null,
            'cursoId' => $cursoId,
        ]);
    }

    public function create(Request $request): View
    {
        $estudiantes = $this->estudiantesConCurso();
        $estudianteId = $request->query('estudiante');

        if (is_string($estudianteId) && $estudianteId === '') {
            $estudianteId = null;
        }

        if ($estudianteId !== null && (! is_string($estudianteId) || ! $estudiantes->contains('id', (int) $estudianteId))) {
            abort(404);
        }

        return view('pages.admin.notas.form', [
            'nota' => new Nota(['user_id' => $estudianteId]),
            'estudiantes' => $estudiantes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $nota = Nota::create($this->validar($request));

        return $this->volverAlEstudiante($nota, 'Nota registrada correctamente.');
    }

    public function edit(Nota $nota): View
    {
        $estudiantes = $this->estudiantesConCurso();

        if (! $estudiantes->contains('id', $nota->user_id)) {
            abort(404);
        }

        return view('pages.admin.notas.form', [
            'nota' => $nota,
            'estudiantes' => $estudiantes,
        ]);
    }

    public function update(Request $request, Nota $nota): RedirectResponse
    {
        $nota->update($this->validar($request, $nota->id));

        return $this->volverAlEstudiante($nota, 'Nota actualizada correctamente.');
    }

    public function destroy(Nota $nota): RedirectResponse
    {
        $estudianteId = $nota->user_id;
        $gestion = $nota->gestion;

        $nota->delete();

        return redirect()
            ->route('admin.notas.estudiante', ['estudiante' => $estudianteId, 'gestion' => $gestion])
            ->with('exito', 'Nota eliminada.');
    }

    private function volverAlEstudiante(Nota $nota, string $mensaje): RedirectResponse
    {
        return redirect()
            ->route('admin.notas.estudiante', [
                'estudiante' => $nota->user_id,
                'gestion' => $nota->gestion,
            ])
            ->with('exito', $mensaje);
    }

    /**
     * Estudiantes habilitados para el registro de notas: deben tener un curso
     * asignado. Nayra todavía no tiene curso, por lo que queda fuera.
     *
     * @return Collection<int, User>
     */
    private function estudiantesConCurso(): Collection
    {
        return User::query()
            ->where('role', 'estudiante')
            ->whereNotNull('curso_id')
            ->orderBy('name')
            ->get();
    }

    private function gestionSeleccionada(Request $request, Collection $gestiones): string
    {
        $solicitada = $request->query('gestion');

        if (is_string($solicitada) && $gestiones->contains($solicitada)) {
            return $solicitada;
        }

        return (string) $gestiones->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?int $ignorar = null): array
    {
        $datos = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'asignatura' => ['required', 'string', 'max:120', 'regex:/\S/'],
            'gestion' => ['required', 'string', 'max:9'],
            'parcial' => ['required', 'integer', 'between:1,4'],
            'nota' => ['required', 'numeric', 'between:0,100'],
        ], [], [
            'user_id' => 'estudiante',
            'asignatura' => 'asignatura',
            'gestion' => 'gestión',
            'parcial' => 'parcial',
            'nota' => 'nota',
        ]);

        $estudiante = User::find($datos['user_id']);

        if (! $estudiante?->esEstudiante() || $estudiante->curso_id === null) {
            throw ValidationException::withMessages([
                'user_id' => 'Solo se pueden registrar notas a estudiantes con curso asignado.',
            ]);
        }

        $asignatura = Asignatura::firstOrCreate([
            'nombre' => trim($datos['asignatura']),
        ]);

        $datos['asignatura_id'] = $asignatura->id;
        unset($datos['asignatura']);

        $duplicada = Nota::query()
            ->where('user_id', $datos['user_id'])
            ->where('asignatura_id', $asignatura->id)
            ->where('gestion', $datos['gestion'])
            ->where('parcial', $datos['parcial'])
            ->when($ignorar, fn ($query) => $query->whereKeyNot($ignorar))
            ->exists();

        if ($duplicada) {
            throw ValidationException::withMessages([
                'parcial' => 'Ya existe una nota de esa asignatura en la gestión y el parcial seleccionados.',
            ]);
        }

        return $datos;
    }
}
