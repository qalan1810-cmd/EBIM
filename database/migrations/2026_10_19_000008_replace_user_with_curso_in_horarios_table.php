<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // El horario deja de pertenecer a un estudiante y pasa a pertenecer a
        // un curso. Las filas anteriores (una por estudiante) no tienen
        // equivalente en el nuevo modelo, así que la tabla se reconstruye; el
        // contenido de prueba se vuelve a generar con el seeder.
        Schema::dropIfExists('horarios');

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('dia');
            $table->string('hora_inicio');
            $table->string('hora_fin');
            $table->string('asignatura');
            $table->string('docente');
            $table->string('ambiente')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('dia');
            $table->string('hora_inicio');
            $table->string('hora_fin');
            $table->string('asignatura');
            $table->string('docente');
            $table->string('ambiente')->nullable();
            $table->timestamps();
        });
    }
};
