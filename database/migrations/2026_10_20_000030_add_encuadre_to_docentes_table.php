<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Posición vertical de la fotografía dentro de la tarjeta.
     * Valores de 0 a 100, igual que "background-position": 0 es arriba,
     * 50 es el centro y 100 es abajo.
     */
    public function up(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->unsignedTinyInteger('encuadre')->default(50)->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->dropColumn('encuadre');
        });
    }
};
