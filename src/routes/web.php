<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\BuqueController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\PantalanController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

// Rutas públicas (no auth :Thumbsdown:)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ])->onlyInput('email');
})->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'telefono' => ['nullable', 'string', 'max:20'],
        'empresa' => ['nullable', 'string', 'max:255'],
        'cargo' => ['nullable', 'string', 'max:100'],
    ]);

    // Crear usuario
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Crear perfil asociado
    \App\Models\Perfil::create([
        'user_id' => $user->id,
        'tipo_usuario' => 'propietario', // Por defecto
        'telefono' => $validated['telefono'] ?? null,
        'empresa' => $validated['empresa'] ?? null,
        'cargo' => $validated['cargo'] ?? null,
        'fecha_alta' => now(),
        'activo' => true,
    ]);

    // Autenticar automáticamente
    Auth::login($user);

    return redirect()->route('dashboard')->with('success', '¡Registro completado exitosamente!');
})->name('register.submit');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rutas protegidas (si auth :Thumbsup:)
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/trafico', [DashboardController::class, 'trafico'])->name('dashboard.trafico');
    Route::get('/dashboard/clima', [DashboardController::class, 'clima'])->name('dashboard.clima');

    // MUELLES
    Route::get('/muelles', [MuelleController::class, 'index'])->name('muelles.index');
    
    Route::get('/muelles/create', [MuelleController::class, 'create'])->name('muelles.create');
    Route::post('/muelles', [MuelleController::class, 'store'])->name('muelles.store');

    Route::get('/muelles/{id}', [MuelleController::class, 'show'])->name('muelles.show');

    Route::get('/muelles/{id}/edit', [MuelleController::class, 'edit'])->name('muelles.edit');
    Route::put('/muelles/{id}', [MuelleController::class, 'update'])->name('muelles.update');
    
    Route::delete('/muelles/{id}', [MuelleController::class, 'destroy'])->name('muelles.destroy');
    
    Route::post('/muelles/{id}/toggle-disponibilidad', [MuelleController::class, 'toggleDisponibilidad'])
        ->name('muelles.toggle-disponibilidad');


});

// RUTAS API (para integraciones externas)
Route::prefix('api')->group(function () {
    // Euskalmet - Datos climáticos
    Route::get('/clima/actual', function () {
        return response()->json(['message' => 'Implementar integración Euskalmet']);
    })->name('api.clima.actual');

    Route::get('/puerto/estado', function () {
        $muelles = \App\Models\Muelle::with('buqueActual')->get();
        $buques = \App\Models\Buque::whereIn('estado', ['atracado', 'fondeado'])->get();
        
        return response()->json([
            'muelles' => $muelles,
            'buques' => $buques,
            'timestamp' => now()
        ]);
    })->name('api.puerto.estado');

    Route::post('/muelles/{id}/verificar-disponibilidad', function ($id) {
        $muelle = \App\Models\Muelle::findOrFail($id);
        
        return response()->json([
            'disponible' => !$muelle->estaOcupado(),
            'muelle' => $muelle
        ]);
    })->name('api.muelles.verificar-disponibilidad');
});