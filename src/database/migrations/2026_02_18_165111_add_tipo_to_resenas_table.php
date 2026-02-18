<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->enum('tipo', ['plataforma', 'servicio'])->default('servicio')->after('user_id');
            $table->unsignedBigInteger('servicio_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->unsignedBigInteger('servicio_id')->nullable(false)->change();
        });
    }
};
