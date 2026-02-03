<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\BuqueController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\PantalanController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Login y ostias
})->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    // Resgristo y todo el pollastre
})->name('register.submit');

Route::post('/logout', function () {
    // Mandar a la mierda al user
})->name('logout');

// No tocar (Mierdas que requieren middleware y pollastres complicados)
Route::middleware(['auth'])->group(function () {
    // Dashboard (. / trafico / clima / servicios)
    Route::post('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');

    
    // Muelles (listar / crear / especifico(id) / editar / fulminar / cambiarDispo)
    // Buques (listar / crear / atrauqe (drag&drop del henri) / detalles(id) / editar / fulminar / asignar al muelle (AJAX) / Desatracar)
    // Servicios ( listar / asignar / retirar / editar / detalles(id) / crear / fulimnar)
    // Pantalan (lista / crear / atraque / detalles(id)) / fulminar / editar / detracar
});