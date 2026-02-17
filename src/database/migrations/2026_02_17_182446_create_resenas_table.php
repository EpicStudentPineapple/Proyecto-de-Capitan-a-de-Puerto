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
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->integer('calificacion')->unsigned();
            $table->text('comentario');
            $table->timestamp('fecha_resena')->useCurrent();
            $table->boolean('aprobado')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('servicio_id');
            $table->index('aprobado');

            // Unique constraint: one review per user per service
            $table->unique(['user_id', 'servicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
