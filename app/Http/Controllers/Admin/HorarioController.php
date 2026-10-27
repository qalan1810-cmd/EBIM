<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Horario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Horario de clases por curso.
 *
 * La rejilla tiene los días de la semana como columnas y las franjas horarias
 * como filas. El director académico la arma y la envía a aprobación; el rector
 * la aprueba o la devuelve con observaciones.
 */
class HorarioController extends Controller
{
    public function index(Request $request): View
    {
        $cursos = Curso::ordenado()->withCount(['horarios', 'estudiantes'])->get();

        $solicitado = (int) $request->query('curso');

        // Se muestra el curso pedido; si no se indicó ninguno, el rector abre
        // directamente un horario pendiente de aprobación, que es lo que
        // necesita revisar.
        $curso = $cursos->firstWhere('id', $solicitado)
            ?? ($request->user()->esAdmin() ? $cursos->firstWhere('estado_horario', Curso::ESTADO_ENVIADO) : null)
            ?? $cursos->first();

        return view('pages.admin.horarios.index', [
            'cursos' => $cursos,
            'curso' => $curso,
            'rejilla' => $curso?->rejilla() ?? ['dias' => Curso::DIAS, 'franjas' => []],
            'pendientes' => Curso::where('estado_horario', Curso::ESTADO_ENVIADO)->ordenado()->get(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $curso = $request->query('curso') ? Curso::find($request->query('curso')) : null;

        if ($bloqueo = $this->bloqueoSiNoEditable($curso)) {
            return $bloqueo;
        }

        return view('pages.admin.horarios.form', [
            'horario' => new Horario([
                'curso_id' => $curso?->id,
                'dia' => $request->query('dia'),
                'hora_inicio' => $request->query('hora_inicio'),
                'hora_fin' => $request->query('hora_fin'),
            ]),
            'cursos' => Curso::ordenado()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        $curso = Curso::findOrFail($datos['curso_id']);

        if ($bloqueo = $this->bloqueoSiNoEditable($curso)) {
            return $bloqueo;
        }

        Horario::create($datos);

        return redirect()
            ->route('admin.horarios.index', ['curso' => $curso->id])
            ->with('exito', 'Asignatura agregada al horario.');
    }

    public function edit(Horario $horario): View|RedirectResponse
    {
        if ($bloqueo = $this->bloqueoSiNoEditable($horario->curso)) {
            return $bloqueo;
        }

        return view('pages.admin.horarios.form', [
            'horario' => $horario,
            'cursos' => Curso::ordenado()->get(),
        ]);
    }

    public function update(Request $request, Horario $horario): RedirectResponse
    {
        $datos = $this->validar($request, $horario->id);

        $curso = Curso::findOrFail($datos['curso_id']);

        // Ni el curso actual ni el curso de destino pueden estar bloqueados.
        if ($bloqueo = $this->bloqueoSiNoEditable($horario->curso)) {
            return $bloqueo;
        }

        if ($bloqueo = $this->bloqueoSiNoEditable($curso)) {
            return $bloqueo;
        }

        $horario->update($datos);

        return redirect()
            ->route('admin.horarios.index', ['curso' => $curso->id])
            ->with('exito', 'Asignatura actualizada.');
    }

    public function destroy(Horario $horario): RedirectResponse
    {
        if ($bloqueo = $this->bloqueoSiNoEditable($horario->curso)) {
            return $bloqueo;
        }

        $cursoId = $horario->curso_id;

        $horario->delete();

        return redirect()
            ->route('admin.horarios.index', ['curso' => $cursoId])
            ->with('exito', 'Asignatura quitada del horario.');
    }

    /**
     * El director académico envía el horario del curso al rector.
     */
    public function enviar(Curso $curso): RedirectResponse
    {
        if ($bloqueo = $this->bloqueoSiNoEditable($curso)) {
            return $bloqueo;
        }

        if (! $curso->horarios()->exists()) {
            return $this->volverAlCurso($curso, 'error', 'Agregue al menos una asignatura antes de enviar el horario a aprobación.');
        }

        $curso->estado_horario = Curso::ESTADO_ENVIADO;
        $curso->horario_enviado_en = now();
        $curso->horario_enviado_por = auth()->id();
        $curso->horario_revisado_en = null;
        $curso->horario_revisado_por = null;
        $curso->horario_observacion = null;
        $curso->save();

        return $this->volverAlCurso($curso, 'exito', 'Horario enviado al rector para su aprobación.');
    }

    /**
     * El rector aprueba el horario enviado por el director académico.
     */
    public function aprobar(Curso $curso): RedirectResponse
    {
        if ($curso->estado_horario !== Curso::ESTADO_ENVIADO) {
            return $this->volverAlCurso($curso, 'error', 'Solo se pueden aprobar horarios pendientes de revisión.');
        }

        $curso->estado_horario = Curso::ESTADO_APROBADO;
        $curso->horario_revisado_en = now();
        $curso->horario_revisado_por = auth()->id();
        $curso->save();

        return $this->volverAlCurso($curso, 'exito', 'Horario aprobado. El curso ya lo ve en su portal.');
    }

    /**
     * El rector devuelve el horario al director académico con una observación.
     *
     * También puede reabrir un horario ya aprobado para que el director lo
     * corrija. Mientras se corrige, el curso deja de verlo en su portal.
     */
    public function rechazar(Request $request, Curso $curso): RedirectResponse
    {
        if (! in_array($curso->estado_horario, [Curso::ESTADO_ENVIADO, Curso::ESTADO_APROBADO], true)) {
            return $this->volverAlCurso($curso, 'error', 'Solo se pueden devolver horarios pendientes de revisión o ya aprobados.');
        }

        $datos = $request->validate([
            'observacion' => ['required', 'string', 'max:500'],
        ], [], ['observacion' => 'observación']);

        $curso->estado_horario = Curso::ESTADO_RECHAZADO;
        $curso->horario_revisado_en = now();
        $curso->horario_revisado_por = auth()->id();
        $curso->horario_observacion = $datos['observacion'];
        $curso->save();

        return $this->volverAlCurso($curso, 'exito', 'Horario devuelto al director académico con observaciones.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?int $ignorar = null): array
    {
        $datos = $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
            'dia' => ['required', 'in:' . implode(',', Curso::DIAS)],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'asignatura' => ['required', 'string', 'max:120', 'regex:/\S/'],
            'docente' => ['required', 'string', 'max:120'],
            'ambiente' => ['nullable', 'string', 'max:80'],
        ], [], [
            'curso_id' => 'curso',
            'dia' => 'día',
            'hora_inicio' => 'hora de inicio',
            'hora_fin' => 'hora de fin',
            'asignatura' => 'asignatura',
            'docente' => 'docente',
            'ambiente' => 'ambiente',
        ]);

        // Un curso no puede tener dos asignaturas en el mismo día y a la misma
        // hora de inicio: se cruzarían en la rejilla. La base de datos tiene la
        // misma restricción única.
        $ocupada = Horario::query()
            ->where('curso_id', $datos['curso_id'])
            ->where('dia', $datos['dia'])
            ->where('hora_inicio', $datos['hora_inicio'])
            ->when($ignorar, fn ($query) => $query->whereKeyNot($ignorar))
            ->exists();

        if ($ocupada) {
            throw ValidationException::withMessages([
                'dia' => 'Ese curso ya tiene una asignatura en el mismo día y la misma franja horaria.',
            ]);
        }

        return $datos;
    }

    /**
     * Un horario enviado o aprobado ya no se puede modificar: el rector debe
     * devolverlo primero.
     */
    private function bloqueoSiNoEditable(?Curso $curso): ?RedirectResponse
    {
        if (! $curso || $curso->esEditable()) {
            return null;
        }

        return $this->volverAlCurso(
            $curso,
            'error',
            'El horario del curso ' . $curso->nombre . ' está ' . mb_strtolower($curso->estadoEtiqueta()) . ' y no se puede modificar.'
        );
    }

    private function volverAlCurso(Curso $curso, string $tipo, string $mensaje): RedirectResponse
    {
        return redirect()->route('admin.horarios.index', ['curso' => $curso->id])->with($tipo, $mensaje);
    }
}
