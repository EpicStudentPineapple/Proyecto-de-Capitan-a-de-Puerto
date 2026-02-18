<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuqueController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\PantalanController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResenaController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPropietario;
use Illuminate\Support\Facades\Route;

// Note: Root path (/) is served by Nginx directly from index.html
// See Docker/nginx/default.conf for configuration

// Verified routes (require email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    Route::get('/dashboard/trafico', [DashboardController::class , 'trafico'])->name('dashboard.trafico');
    Route::get('/dashboard/clima', [DashboardController::class , 'clima'])->name('dashboard.clima');
    Route::get('/dashboard/servicios', [DashboardController::class , 'servicios'])->name('dashboard.servicios');
});

// Admin-only routes
Route::middleware(['auth', 'verified', CheckAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Muelles management
    Route::resource('muelles', MuelleController::class);
    Route::post('muelles/{id}/toggle-disponibilidad', [MuelleController::class , 'toggleDisponibilidad'])
        ->name('muelles.toggle-disponibilidad');

    // Servicios management
    Route::resource('servicios', ServiciosController::class);

    // Pantalans management
    Route::resource('pantalans', PantalanController::class);
    Route::get('pantalans/por-muelle/{muelleId}', [PantalanController::class , 'porMuelle'])
        ->name('pantalans.por-muelle');
    Route::post('pantalans/{id}/toggle-disponibilidad', [PantalanController::class , 'toggleDisponibilidad'])
        ->name('pantalans.toggle-disponibilidad');

    // All buques management (admin can see all)
    Route::get('buques/gestion-atraques', [BuqueController::class , 'gestionAtraques'])
        ->name('buques.gestion-atraques');
    Route::resource('buques', BuqueController::class);
    Route::post('buques/{id}/asignar-muelle', [BuqueController::class , 'asignarMuelle'])
        ->name('buques.asignar-muelle');
    Route::post('buques/{id}/desatracar', [BuqueController::class , 'desatracar'])
        ->name('buques.desatracar');
    Route::post('buque-servicio/{id}/estado', [ServiciosController::class , 'actualizarEstado'])
        ->name('buque-servicio.actualizar-estado');

    // Perfiles management (admin can manage all profiles)
    Route::get('perfiles', [PerfilController::class , 'index'])->name('perfiles.index');
    Route::get('perfiles/{id}', [PerfilController::class , 'show'])->name('perfiles.show');
    Route::get('perfiles/{id}/edit', [PerfilController::class , 'edit'])->name('perfiles.edit');
    Route::put('perfiles/{id}', [PerfilController::class , 'update'])->name('perfiles.update');
    Route::post('perfiles/{id}/toggle-activo', [PerfilController::class , 'toggleActivo'])
        ->name('perfiles.toggle-activo');
});

// Propietario-only routes (for vessel owners)
Route::middleware(['auth', 'verified', CheckPropietario::class])->prefix('propietario')->name('propietario.')->group(function () {
    // Own buques only
    Route::get('mis-buques', [BuqueController::class , 'index'])->name('buques.index');
    Route::get('mis-buques/create', [BuqueController::class , 'create'])->name('buques.create');
    Route::post('mis-buques', [BuqueController::class , 'store'])->name('buques.store');
    Route::get('mis-buques/{id}', [BuqueController::class , 'show'])->name('buques.show');
    Route::get('mis-buques/{id}/edit', [BuqueController::class , 'edit'])->name('buques.edit');
    Route::put('mis-buques/{id}', [BuqueController::class , 'update'])->name('buques.update');
    Route::delete('mis-buques/{id}', [BuqueController::class , 'destroy'])->name('buques.destroy');

    // Request services for own buques
    Route::post('servicios/solicitar', [ServiciosController::class , 'solicitar'])->name('servicios.solicitar');
});

// Shared authenticated routes (both admin and propietario)
Route::middleware(['auth', 'verified'])->group(function () {
    // View available muelles (read-only for propietarios)
    Route::get('muelles', [MuelleController::class , 'index'])->name('muelles.index');
    Route::get('muelles/{id}', [MuelleController::class , 'show'])->name('muelles.show');

    // View available servicios (read-only for propietarios)
    Route::get('servicios', [ServiciosController::class , 'index'])->name('servicios.index');
    Route::get('servicios/{id}', [ServiciosController::class , 'show'])->name('servicios.show');

    // View pantalans (read-only for propietarios)
    Route::get('pantalans', [PantalanController::class , 'index'])->name('pantalans.index');
    Route::get('pantalans/{id}', [PantalanController::class , 'show'])->name('pantalans.show');

    // Own profile management
    Route::get('perfil', [PerfilController::class , 'miPerfil'])->name('perfil.mi-perfil');
    Route::get('perfil/editar', [PerfilController::class , 'editarMiPerfil'])->name('perfil.editar-mi-perfil');
    Route::put('perfil', [PerfilController::class , 'actualizarMiPerfil'])->name('perfil.actualizar-mi-perfil');

    // Standard profile routes (Laravel Breeze)
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Reseñas routes
    Route::get('resenas', [ResenaController::class , 'index'])->name('resenas.index');
    Route::get('resenas/crear', [ResenaController::class , 'create'])->name('resenas.create');
    Route::post('resenas', [ResenaController::class , 'store'])->name('resenas.store');
});

// Admin Review Management
Route::middleware(['auth', 'verified', CheckAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::post('resenas/{resena}/aprobar', [ResenaController::class , 'aprobar'])->name('resenas.aprobar');
    Route::delete('resenas/{resena}', [ResenaController::class , 'destroy'])->name('resenas.destroy');
});

require __DIR__ . '/auth.php';
