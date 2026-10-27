<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Los estudiantes ingresan con un código numérico y su carnet se usa para
     * generar la contraseña inicial. El correo deja de ser obligatorio para
     * ellos; el personal autorizado conserva el acceso por correo.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('carnet', 20)->nullable()->unique()->after('codigo');
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Antes de volver a exigir el correo, se asigna uno interno a los
        // estudiantes que no lo tengan. Así el rollback no pierde registros.
        DB::table('users')
            ->whereNull('email')
            ->pluck('id')
            ->each(function (int $id): void {
                DB::table('users')->where('id', $id)->update([
                    'email' => 'estudiante'.$id.'@ebim.local',
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['carnet']);
            $table->dropColumn('carnet');
            $table->string('email')->nullable(false)->change();
        });
    }
};
