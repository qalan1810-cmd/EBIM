<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Curso al que pertenece un estudiante. Es lo que permite mostrarle el
        // horario de su curso en el portal.
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('curso_id')->nullable()->after('codigo')->constrained('cursos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('curso_id');
        });
    }
};
