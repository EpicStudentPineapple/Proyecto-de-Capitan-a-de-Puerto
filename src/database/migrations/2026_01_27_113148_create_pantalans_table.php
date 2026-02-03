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
        Schema::create('pantalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muelle_id')->constrained('muelles')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique();
            $table->integer('numero_amarre')->unsigned(); // Número de posición en el pantalán
            $table->decimal('longitud_maxima', 6, 2); // metros - eslora máxima permitida
            $table->decimal('manga_maxima', 5, 2)->nullable(); // metros - ancho máximo
            $table->decimal('calado_maximo', 4, 2); // metros
            $table->enum('tipo_amarre', ['lateral', 'muerto', 'boya'])->default('lateral');
            $table->boolean('agua_disponible')->default(true);
            $table->boolean('electricidad_disponible')->default(true);
            $table->integer('amperaje')->unsigned()->nullable(); // Para toma eléctrica
            $table->boolean('wifi_disponible')->default(false);
            $table->boolean('disponible')->default(true);
            $table->decimal('precio_dia', 8, 2)->nullable(); // euros/día
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pantalans');
    }
};