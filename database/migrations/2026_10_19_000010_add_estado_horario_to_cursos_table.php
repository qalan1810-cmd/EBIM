<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Estado de aprobación del horario del curso. El director académico lo
        // elabora y lo envía; el rector lo aprueba o lo devuelve con
        // observaciones.
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('estado_horario')->default('borrador')->after('orden');
            $table->timestamp('horario_enviado_en')->nullable();
            $table->foreignId('horario_enviado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('horario_revisado_en')->nullable();
            $table->foreignId('horario_revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('horario_observacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('horario_enviado_por');
            $table->dropConstrainedForeignId('horario_revisado_por');
            $table->dropColumn([
                'estado_horario',
                'horario_enviado_en',
                'horario_revisado_en',
                'horario_observacion',
            ]);
        });
    }
};
