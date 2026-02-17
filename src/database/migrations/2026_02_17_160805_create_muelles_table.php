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
        Schema::create('muelles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique();
            $table->decimal('longitud', 8, 2); // metros
            $table->decimal('calado_maximo', 5, 2); // metros
            $table->integer('capacidad_toneladas')->unsigned();
            $table->enum('tipo_muelle', [
                'contenedores',
                'carga_general',
                'graneles',
                'pesquero',
                'pasajeros',
                'ro-ro',
                'hidrocarburos',
                'deportivo',
                'servicios'
            ]);
            $table->boolean('disponible')->default(true);
            $table->boolean('grua_disponible')->default(false);
            $table->boolean('energia_tierra')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muelles');
    }
};