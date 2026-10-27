<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Docente;
use App\Models\Noticia;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Portada del sitio (Histórico 1: información general de la escuela).
     */
    public function index(): View
    {
        return view('pages.site.home', [
            'docentes' => Docente::visibles()->limit(4)->get(),
            'historiaResumen' => Contenido::buscar('historia_resumen'),
            'galeriaHistoria' => $this->galeriaHistoria(),
            'noticias' => Noticia::visibles()->deTipo(Noticia::TIPO_NOTICIA)->limit(6)->get(),
            'comunicados' => Noticia::visibles()->deTipo(Noticia::TIPO_COMUNICADO)->limit(4)->get(),
        ]);
    }

    /**
     * Fotografías de la galería de "Nuestra historia" en la portada.
     *
     * Se leen de public/assets/ebim/home/musef/ en orden natural: al copiar una
     * foto nueva en esa carpeta aparece sola, sin tocar el código. Cada entrada
     * incluye las versiones -400/-800 cuando existen, para no servir una imagen
     * de 1700 px dentro de una caja de 700.
     *
     * Cada foto lleva su propia leyenda debajo. Las que no estén en $leyendas
     * usan $leyendaPorDefecto, así que agregar una foto nunca deja el bloque
     * sin texto.
     *
     * @return array<int, array{src: string, srcset: string, texto: string}>
     */
    private function galeriaHistoria(): array
    {
        $relativa = 'assets/ebim/home/musef';
        $carpeta = public_path($relativa);
        $archivos = [];

        $leyendas = [
            '1.jpg' => 'Presentación de la EBIM en el MUSEF, mostrando el nivel profesional de sus músicos.',
            '2.jpg' => 'La EBIM en Oruro: una escuela que forma músicos y cultiva identidad cultural.',
        ];
        $leyendaPorDefecto = 'La EBIM en Oruro: una escuela que forma músicos y cultiva identidad cultural.';

        if (is_dir($carpeta)) {
            foreach (scandir($carpeta) as $archivo) {
                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                $base = pathinfo($archivo, PATHINFO_FILENAME);

                if (! is_file($carpeta.'/'.$archivo)) {
                    continue;
                }

                if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true)) {
                    continue;
                }

                // Las versiones reducidas acompañan a la original; no son fotos.
                if (preg_match('/-(400|800)$/', $base)) {
                    continue;
                }

                $archivos[] = $archivo;
            }

            sort($archivos, SORT_NATURAL | SORT_FLAG_CASE);
        }

        $galeria = [];

        foreach ($archivos as $archivo) {
            $base = pathinfo($archivo, PATHINFO_FILENAME);
            $variantes = [];

            foreach ([400, 800] as $ancho) {
                $rutaVariante = $relativa.'/'.$base.'-'.$ancho.'.jpg';

                if (is_file(public_path($rutaVariante))) {
                    $variantes[] = '/'.$rutaVariante.' '.$ancho.'w';
                }
            }

            $galeria[] = [
                'src' => '/'.$relativa.'/'.$archivo,
                'srcset' => implode(', ', $variantes),
                'texto' => $leyendas[$archivo] ?? $leyendaPorDefecto,
            ];
        }

        return $galeria !== []
            ? $galeria
            : [[
                'src' => $this->fotoHistoria(),
                'srcset' => '',
                'texto' => $leyendas['1.jpg'],
            ]];
    }

    /**
     * Fotografía que acompaña "Nuestra historia" en la portada. Si la imagen
     * propia no está disponible se recurre a la imagen de reserva.
     */
    private function fotoHistoria(): string
    {
        $propia = '/assets/ebim/home/historia.jpg';

        return is_file(public_path($propia)) ? $propia : '/assets/ebim/home/historia.jpg';
    }
}
