<x-guest-layout>
    <h1 class="auth-title">Iniciar Sesión</h1>
    <p class="auth-subtitle">Accede a tu cuenta de Gestorinaitor 3000</p>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control"
                   required
                   autofocus
                   autocomplete="username"
                   aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}">
            @error('email')
                <p id="email-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password"
                   type="password"
                   name="password"
                   class="form-control"
                   required
                   autocomplete="current-password"
                   aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}">
            @error('password')
                <p id="password-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Recordarme -->
        <div class="auth-checkbox-group">
            <input id="remember_me" type="checkbox" name="remember" class="auth-checkbox">
            <label for="remember_me" class="auth-checkbox-label">Recordar mi sesión</label>
        </div>

        <!-- Acciones -->
        <div class="auth-actions">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
            <button type="submit" class="btn btn-primary auth-submit">
                Iniciar Sesión
            </button>
        </div>

        <p class="text-center text-sm text-muted" style="margin-top: 1rem;">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="auth-link">Regístrate aquí</a>
        </p>
    </form>
</x-guest-layout>
