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
        Schema::create('buque_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buque_id')->constrained('buques')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->dateTime('fecha_solicitud');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('estado', ['solicitado', 'en_proceso', 'completado', 'cancelado'])->default('solicitado');
            $table->integer('cantidad')->unsigned()->default(1); // Para servicios cuantificables
            $table->decimal('precio_total', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índice compuesto para búsquedas eficientes
            $table->index(['buque_id', 'servicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buque_servicio');
    }
};