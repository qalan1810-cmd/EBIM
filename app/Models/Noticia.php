<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    /** Publicación informativa de la portada. */
    public const TIPO_NOTICIA = 'noticia';

    /** Comunicado oficial de la escuela. */
    public const TIPO_COMUNICADO = 'comunicado';

    /**
     * Tipos de publicación disponibles y su etiqueta visible.
     *
     * @var array<string, string>
     */
    public const TIPOS = [
        self::TIPO_NOTICIA => 'Noticia',
        self::TIPO_COMUNICADO => 'Comunicado',
    ];

    protected $fillable = [
        'titulo',
        'tipo',
        'resumen',
        'cuerpo',
        'enlace',
        'imagen',
        'fecha',
        'orden',
        'activo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Noticias visibles para el público, de la más reciente a la más antigua.
     */
    public function scopeVisibles($query)
    {
        return $query->where('activo', true)
            ->orderByDesc('fecha')
            ->orderBy('orden')
            ->orderByDesc('id');
    }

    /**
     * Publicaciones visibles de un tipo concreto.
     */
    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Etiqueta visible del tipo ("Noticia" o "Comunicado").
     */
    public function etiquetaTipo(): string
    {
        return self::TIPOS[$this->tipo] ?? self::TIPOS[self::TIPO_NOTICIA];
    }

    /**
     * Indica si la publicación trae un video incrustable.
     */
    public function tieneVideo(): bool
    {
        return $this->urlIncrustable() !== null;
    }

    /**
     * Ruta de la fotografía subida al proyecto, lista para <img src>.
     * Acepta tanto una ruta del proyecto ("/assets/...") como una URL.
     */
    public function imagenUrl(): ?string
    {
        $imagen = trim((string) $this->imagen);

        if ($imagen === '') {
            return null;
        }

        return str_starts_with($imagen, 'http') ? $imagen : '/' . ltrim($imagen, '/');
    }

    /**
     * Enlace normalizado listo para el reproductor incrustado de Facebook o
     * YouTube. Devuelve null cuando el enlace no se puede mostrar dentro de la
     * página, en cuyo caso la publicación se ofrece como enlace normal.
     *
     * Se aceptan los videos de YouTube en sus distintos formatos
     * (watch?v=, youtu.be/, /embed/, /shorts/, /live/) y los videos de Facebook
     * (facebook.com/watch/?v=…, /videos/…, o un plugins/video.php ya incrustado).
     *
     * Las publicaciones de Facebook que NO son video (fotos, tarjetas,
     * enlaces) no se pueden incrustar: Facebook responde "Video Unavailable" y
     * además bloquea que otros sitios muestren sus imágenes. En esos casos se
     * devuelve null y la portada muestra el botón "Ver en Facebook".
     */
    public function urlIncrustable(): ?string
    {
        $enlace = trim((string) $this->enlace);

        if ($enlace === '') {
            return null;
        }

        if (preg_match('~facebook\.com/plugins/video\.php\?href=([^&]+)~i', $enlace, $m)) {
            $destino = urldecode($m[1]);

            return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($destino) . '&show_text=false&width=560';
        }

        if (self::esVideoDeFacebook($enlace)) {
            return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($enlace) . '&show_text=false&width=560';
        }

        $youtube = self::youtubeId($enlace);

        if ($youtube !== null) {
            return 'https://www.youtube.com/embed/' . $youtube;
        }

        return null;
    }

    /**
     * Indica si un enlace de Facebook apunta a un video (y no a una foto,
     * una tarjeta o una publicación de texto, que no se pueden incrustar).
     */
    private static function esVideoDeFacebook(string $enlace): bool
    {
        if (! preg_match('~facebook\.com~i', $enlace)) {
            return false;
        }

        return (bool) preg_match(
            '~facebook\.com/(?:watch/?\?(?:.*&)?v=|(?:[^/]+/)?videos?(?:/|\.php\?(?:.*&)?v=)|reel/|video\.php)~i',
            $enlace
        );
    }

    /**
     * Extrae el identificador de un video de YouTube en cualquiera de sus
     * formatos habituales.
     */
    private static function youtubeId(string $enlace): ?string
    {
        $patrones = [
            '~youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{6,})~i',
            '~youtu\.be/([A-Za-z0-9_-]{6,})~i',
            '~youtube\.com/embed/([A-Za-z0-9_-]{6,})~i',
            '~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~i',
            '~youtube\.com/live/([A-Za-z0-9_-]{6,})~i',
        ];

        foreach ($patrones as $patron) {
            if (preg_match($patron, $enlace, $m)) {
                return $m[1];
            }
        }

        return null;
    }
}
