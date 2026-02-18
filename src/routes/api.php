<?php

use App\Http\Controllers\EuskalmetController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPropietario;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas públicas (sin autenticación) accesibles desde el cliente JS
| para obtener datos externos y estadísticas de la aplicación.
|
*/

// ── Reseñas públicas ─────────────────────────────────────────────────────
Route::get('/resenas/aprobadas',          [ResenaController::class, 'aprobadas'])  ->name('api.resenas.aprobadas');
Route::get('/resenas/servicio/{servicio}',[ResenaController::class, 'porServicio'])->name('api.resenas.porServicio');

// ── Estadísticas para la landing page ────────────────────────────────────
Route::get('/estadisticas', [WelcomeController::class, 'estadisticas'])->name('api.estadisticas');

// ── Euskalmet — Predicción meteorológica (Donostia - San Sebastián) ──────
//
//  GET /api/euskalmet/prediccion
//
//  El controlador firma la petición con RSA-SHA256 (clave privada en .env)
//  y devuelve el pronóstico del día siguiente en JSON normalizado:
//
//  {
//    "municipio":       "Donostia - San Sebastián",
//    "fecha":           "2025-01-16",
//    "temperatura":     14,          // °C máxima
//    "tempMin":         8,           // °C mínima
//    "precipitacion":   2.4,         // mm
//    "viento":          30,          // km/h
//    "vientoDireccion": "SW",
//    "estadoCielo":     "Nublado",
//    "humedadMax":      85,          // %
//    "alturaOlas":      1.2,         // metros
//    "actualizadoEn":   "2025-01-15T06:00:00+01:00"
//  }
//
Route::get('/euskalmet/prediccion', [EuskalmetController::class, 'prediccion'])
    ->name('api.euskalmet.prediccion');

/*
|--------------------------------------------------------------------------
| Rutas autenticadas (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Propietario: crear y consultar sus reseñas
    Route::middleware(CheckPropietario::class)->group(function () {
        Route::post('/resenas',               [ResenaController::class, 'store'])       ->name('api.resenas.store');
        Route::get('/resenas/mis-servicios',  [ResenaController::class, 'misServicios'])->name('api.resenas.misServicios');
    });

    // Admin: gestión completa de reseñas
    Route::middleware(CheckAdmin::class)->group(function () {
        Route::get('/resenas',                    [ResenaController::class, 'index'])  ->name('api.resenas.index');
        Route::put('/resenas/{resena}',           [ResenaController::class, 'update'])->name('api.resenas.update');
        Route::delete('/resenas/{resena}',        [ResenaController::class, 'destroy'])->name('api.resenas.destroy');
        Route::post('/resenas/{resena}/aprobar',  [ResenaController::class, 'aprobar'])->name('api.resenas.aprobar');
    });

    // Compartidas: ver detalle de una reseña
    Route::get('/resenas/{resena}', [ResenaController::class, 'show'])->name('api.resenas.show');
});