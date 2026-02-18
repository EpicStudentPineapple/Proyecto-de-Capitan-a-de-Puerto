<x-guest-layout>
    <h1 class="auth-title">Crear Cuenta</h1>
    <p class="auth-subtitle">Únete al Sistema de Gestión Portuaria</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
        @csrf

        <!-- Nombre -->
        <div class="form-group">
            <label for="name" class="form-label">Nombre completo</label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="form-control"
                   required
                   autofocus
                   autocomplete="name"
                   aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}">
            @error('name')
                <p id="name-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control"
                   required
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
                   autocomplete="new-password"
                   aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}">
            @error('password')
                <p id="password-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmar Contraseña -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   class="form-control"
                   required
                   autocomplete="new-password"
                   aria-describedby="{{ $errors->has('password_confirmation') ? 'password-confirm-error' : '' }}">
            @error('password_confirmation')
                <p id="password-confirm-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Acciones -->
        <div class="auth-actions">
            <a href="{{ route('login') }}" class="auth-link">¿Ya tienes cuenta?</a>
            <button type="submit" class="btn btn-primary auth-submit">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>
