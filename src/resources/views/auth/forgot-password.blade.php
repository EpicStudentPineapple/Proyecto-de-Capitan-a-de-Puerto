<x-guest-layout>
    <h1 class="auth-title">Recuperar Contraseña</h1>
    <p class="auth-subtitle">Te enviaremos un enlace para restablecer tu contraseña.</p>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
        @csrf

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

        <div class="auth-actions" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary auth-submit">
                Enviar enlace de recuperación
            </button>
        </div>

        <p class="text-center text-sm text-muted" style="margin-top: 1rem;">
            <a href="{{ route('login') }}" class="auth-link">Volver al inicio de sesión</a>
        </p>
    </form>
</x-guest-layout>
