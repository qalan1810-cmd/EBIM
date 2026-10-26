<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Las publicaciones de la portada pueden ser noticias o comunicados
     * oficiales, y pueden incluir multimedia (foto o video de Facebook /
     * YouTube) que se muestra incrustado dentro de la propia página.
     */
    public function up(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->string('tipo')->default('noticia')->after('titulo');
            $table->string('enlace')->nullable()->after('cuerpo');
            $table->string('imagen')->nullable()->after('enlace');
        });
    }

    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'enlace', 'imagen']);
        });
    }
};
