<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM values using raw SQL (most reliable method for enums)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE perfils MODIFY COLUMN tipo_usuario ENUM('administrador', 'propietario') NOT NULL DEFAULT 'propietario'");
        }

        // Add indexes for performance
        Schema::table('buques', function (Blueprint $table) {
            $table->index('propietario_id');
            $table->index('estado');
            $table->index('muelle_id');
        });

        Schema::table('muelles', function (Blueprint $table) {
            $table->index('disponible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ENUM values (Warning: this might fail if data exists with new values)
        // We generally don't revert enum expansions in production without data cleanup
        // But for completeness:
        // DB::statement("ALTER TABLE perfils MODIFY COLUMN tipo_usuario ENUM('administrador', 'propietario') NOT NULL DEFAULT 'propietario'");

        Schema::table('buques', function (Blueprint $table) {
            $table->dropIndex(['propietario_id']);
            $table->dropIndex(['estado']);
            $table->dropIndex(['muelle_id']);
        });

        Schema::table('muelles', function (Blueprint $table) {
            $table->dropIndex(['disponible']);
        });
    }
};
