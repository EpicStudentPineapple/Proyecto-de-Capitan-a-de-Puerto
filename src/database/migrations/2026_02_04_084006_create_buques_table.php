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
        Schema::create('buques', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('imo', 10)->unique(); // Número IMO
            $table->string('mmsi', 15)->nullable(); // Maritime Mobile Service Identity
            $table->string('bandera', 50);
            $table->decimal('eslora', 8, 2); // metros
            $table->decimal('manga', 6, 2); // metros (ancho)
            $table->decimal('calado', 5, 2); // metros
            $table->integer('tonelaje_bruto')->unsigned();
            $table->enum('tipo_buque', [
                'portacontenedores',
                'granelero',
                'petrolero',
                'gasero',
                'pesquero',
                'ferry',
                'ro-ro',
                'carga_general',
                'crucero',
                'deportivo',
                'narcolancha',
                'remolcador'
            ]);
            $table->foreignId('propietario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('muelle_id')->nullable()->constrained('muelles')->onDelete('set null');
            $table->dateTime('fecha_atraque')->nullable();
            $table->dateTime('fecha_salida_prevista')->nullable();
            $table->enum('estado', ['navegando', 'fondeado', 'atracado', 'en_maniobra', 'mantenimiento'])->default('navegando');
            $table->integer('carga_actual')->unsigned()->nullable(); // toneladas o TEUs
            $table->integer('tripulacion')->unsigned()->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buques');
    }
};