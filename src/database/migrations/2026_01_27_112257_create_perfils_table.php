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
        Schema::create('perfils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipo_usuario', ['administrador', 'propietario'])->default('propietario');
            $table->string('telefono', 20)->nullable();
            $table->string('empresa', 255)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->string('licencia_maritima', 50)->nullable()->unique();
            $table->date('fecha_alta')->default(now());
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfils');
    }
};