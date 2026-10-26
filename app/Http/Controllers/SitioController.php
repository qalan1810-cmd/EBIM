<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Docente;
use Illuminate\View\View;

class SitioController extends Controller
{
    /**
     * Página "Sobre EBIM" (antes "La Escuela"): historia, misión y visión (Histórico 1).
     */
    public function laEscuela(): View
    {
        $valores = Contenido::buscar('valores');

        return view('pages.site.la-escuela', [
            'intro' => Contenido::buscar('sobre_intro'),
            'cronologia' => Contenido::buscar('cronologia'),
            'rector' => Contenido::buscar('rector'),
            'mision' => Contenido::buscar('mision'),
            'vision' => Contenido::buscar('vision'),
            'valores' => $valores,
            'valoresItems' => self::lineas($valores),
            'hitos' => self::hitos(),
        ]);
    }

    /**
     * Oferta académica: niveles y especialidades.
     */
    public function ofertaAcademica(): View
    {
        return view('pages.site.oferta-academica');
    }

    /**
     * Inscripción: requisitos y pasos para ingresar (Histórico 2).
     */
    public function inscripcion(): View
    {
        $requisitos = Contenido::buscar('requisitos');

        return view('pages.site.inscripcion', [
            'requisitos' => $requisitos,
            'pasos' => Contenido::buscar('pasos_inscripcion'),
            'enlacesRequisitos' => self::enlacesRequisitos($requisitos),
        ]);
    }

    /**
     * Enlaces que acompañan a cada requisito de inscripción: la solicitud y el
     * formulario para imprimir, el ejemplo de cada documento y el material de
     * estudio de la evaluación de aptitud musical.
     *
     * Se arman desde config/ebim.php y se emparejan por el texto del
     * requisito, no por su posición, para que sigan funcionando si la
     * dirección reordena o edita la lista.
     *
     * @return array<string, array{href: string, tipo: string, texto: string}>
     */
    private static function enlacesRequisitos(?Contenido $requisitos): array
    {
        if (blank($requisitos?->cuerpo)) {
            return [];
        }

        $lineas = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $requisitos->cuerpo)));
        $recursos = config('ebim.inscripcion.recursos', []);
        $material = config('ebim.inscripcion.material_estudio');

        $enlaces = [];

        foreach ($lineas as $linea) {
            foreach ($recursos as $recurso) {
                $coincide = $recurso['coincide'] ?? null;
                $href = ($recurso['tipo'] ?? null) === 'drive'
                    ? $material
                    : ($recurso['href'] ?? null);

                if (blank($coincide) || blank($href)) {
                    continue;
                }

                if (mb_stripos($linea, $coincide) !== false) {
                    $enlaces[$linea] = [
                        'href' => $href,
                        'tipo' => $recurso['tipo'] ?? 'descarga',
                        'texto' => $recurso['texto'],
                    ];

                    break;
                }
            }
        }

        return $enlaces;
    }

    /**
     * Plantel docente: formación y experiencia (Histórico 3).
     */
    public function docentes(): View
    {
        return view('pages.site.docentes', [
            'docentes' => Docente::visibles()->get(),
        ]);
    }

    /**
     * Contacto: ubicación en la zona de Capachos, Oruro.
     */
    public function contacto(): View
    {
        return view('pages.site.contacto');
    }

    /**
     * Páginas legales.
     */
    public function terminos(): View
    {
        return view('pages.site.terminos');
    }

    public function privacidad(): View
    {
        return view('pages.site.privacidad');
    }

    /**
     * Convierte la cronología guardada (una línea por hito con el formato
     * "año | título | detalle") en los datos que consume la vista. El detalle
     * es opcional y una línea mal formada se ignora en lugar de romper la
     * página.
     *
     * @return array<int, array{anio: string, titulo: string, detalle: string}>
     */
    private static function hitos(): array
    {
        return array_map(
            function (string $linea): array {
                [$anio, $titulo, $detalle] = array_pad(explode('|', $linea, 3), 3, '');

                return [
                    'anio' => trim($anio),
                    'titulo' => trim($titulo),
                    'detalle' => trim($detalle),
                ];
            },
            self::lineas(Contenido::buscar('cronologia'))
        );
    }

    /**
     * Divide el cuerpo de un contenido en líneas no vacías.
     *
     * @return array<int, string>
     */
    private static function lineas(?Contenido $contenido): array
    {
        if (! $contenido) {
            return [];
        }

        $lineas = [];

        foreach (preg_split('/\R/', $contenido->cuerpo) as $linea) {
            $linea = trim($linea);

            if ($linea !== '') {
                $lineas[] = $linea;
            }
        }

        return $lineas;
    }
}
