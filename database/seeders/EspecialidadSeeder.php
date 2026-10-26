<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Seeder;

/**
 * Mantiene sincronizado el catálogo de especialidades con la lista oficial
 * de la escuela. Es idempotente.
 */
class EspecialidadSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('ebim.especialidades', []) as $indice => $nombre) {
            Especialidad::updateOrCreate(
                ['nombre' => $nombre],
                ['orden' => $indice + 1]
            );
        }
    }
}
