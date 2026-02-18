<?php

use App\Http\Controllers\EuskalmetController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPropietario;
use Illuminate\Support\Facades\Route;

// Public API - Get approved reviews for landing page
Route::get('/resenas/aprobadas', [ResenaController::class , 'aprobadas'])->name('api.resenas.aprobadas');
Route::get('/resenas/servicio/{servicio}', [ResenaController::class , 'porServicio'])->name('api.resenas.porServicio');

// Statistics for landing page
Route::get('/estadisticas', [WelcomeController::class , 'estadisticas'])->name('api.estadisticas');

// Euskalmet
Route::get('/euskalmet/prediccion', [EuskalmetController::class, 'prediccion']);

// Authenticated API routes
Route::middleware('auth:sanctum')->group(function () {

    // Propietario routes - Create reviews only
    Route::middleware(CheckPropietario::class)->group(function () {
            Route::post('/resenas', [ResenaController::class , 'store'])->name('api.resenas.store');
            Route::get('/resenas/mis-servicios', [ResenaController::class , 'misServicios'])->name('api.resenas.misServicios');
        }
        );

        // Admin routes - Edit, delete, and approve reviews
        Route::middleware(CheckAdmin::class)->group(function () {
            Route::get('/resenas', [ResenaController::class , 'index'])->name('api.resenas.index');
            Route::put('/resenas/{resena}', [ResenaController::class , 'update'])->name('api.resenas.update');
            Route::delete('/resenas/{resena}', [ResenaController::class , 'destroy'])->name('api.resenas.destroy');
            Route::post('/resenas/{resena}/aprobar', [ResenaController::class , 'aprobar'])->name('api.resenas.aprobar');
        }
        );

        // Shared routes
        Route::get('/resenas/{resena}', [ResenaController::class , 'show'])->name('api.resenas.show');
    });
