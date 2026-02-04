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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique();
            $table->enum('tipo_servicio', [
                'practicaje',
                'remolque',
                'amarre',
                'suministro_combustible',
                'suministro_agua',
                'suministro_electrico',
                'retirada_residuos',
                'limpieza',
                'reparaciones',
                'aprovisionamiento',
                'inspeccion_aduana',
                'sanidad_portuaria',
                'seguridad',
                'otros'
            ]);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_base', 10, 2); // euros
            $table->enum('unidad_cobro', ['fijo', 'por_hora', 'por_tonelada', 'por_metro', 'por_servicio'])->default('fijo');
            $table->boolean('disponible_24h')->default(false);
            $table->boolean('requiere_reserva')->default(true);
            $table->integer('tiempo_estimado_minutos')->unsigned()->nullable();
            $table->string('proveedor', 100)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};