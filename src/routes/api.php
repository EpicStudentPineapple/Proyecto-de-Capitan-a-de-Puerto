<?php

use App\Http\Controllers\ClimaController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPropietario;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- */

// ── Reseñas públicas ─────────────────────────────────────────────────────
Route::get('/resenas/aprobadas', [ResenaController::class , 'aprobadas'])->name('api.resenas.aprobadas');
Route::get('/resenas/servicio/{servicio}', [ResenaController::class , 'porServicio'])->name('api.resenas.porServicio');

// ── Estadísticas para la landing page ────────────────────────────────────
Route::get('/estadisticas', [WelcomeController::class , 'estadisticas'])->name('api.estadisticas');

// ── CLIMA - Predicción meteorológica (OpenMeteo - GRATIS, SIN API KEY) ───
//
//  GET /api/clima/prediccion
//
//  Usa OpenMeteo API (https://open-meteo.com) que es:
//  - 100% gratuita
//  - Sin necesidad de registro
//  - Sin API keys
//  - Con caché de 30 minutos
//
//  Devuelve datos para Donostia-San Sebastián:
//  {
//    "municipio": "Donostia - San Sebastián",
//    "actual": {
//      "temperatura": 15.2,
//      "humedad": 75,
//      "viento": 25,
//      "estado_cielo": "Parcialmente nublado"
//    },
//    "manana": {
//      "temperatura_max": 18,
//      "temperatura_min": 12,
//      "precipitacion": 5.2,
//      "viento_max": 30,
//      "estado_cielo": "Lluvia ligera"
//    },
//    "navegacion": {
//      "altura_olas": 1.5,
//      "apto_maniobras": true
//    }
//  }
//
Route::get('/clima/prediccion', [ClimaController::class , 'prediccion'])
    ->name('api.clima.prediccion');

/* |-------------------------------------------------------------------------- | Rutas autenticadas (Sanctum) |-------------------------------------------------------------------------- */
Route::middleware('auth:sanctum')->group(function () {

    // Propietario: crear y consultar sus reseñas
    Route::middleware(CheckPropietario::class)->group(function () {
            Route::post('/resenas', [ResenaController::class , 'store'])->name('api.resenas.store');
            Route::get('/resenas/mis-servicios', [ResenaController::class , 'misServicios'])->name('api.resenas.misServicios');
        }
        );

        // Admin: gestión completa de reseñas
        Route::middleware(CheckAdmin::class)->group(function () {
            Route::get('/resenas', [ResenaController::class , 'index'])->name('api.resenas.index');
            Route::put('/resenas/{resena}', [ResenaController::class , 'update'])->name('api.resenas.update');
            Route::delete('/resenas/{resena}', [ResenaController::class , 'destroy'])->name('api.resenas.destroy');
            Route::post('/resenas/{resena}/aprobar', [ResenaController::class , 'aprobar'])->name('api.resenas.aprobar');
        }
        );

        // Compartidas: ver detalle de una reseña
        Route::get('/resenas/{resena}', [ResenaController::class , 'show'])->name('api.resenas.show');    });