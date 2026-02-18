<x-guest-layout>
    <h1 class="auth-title">Restablecer Contraseña</h1>
    <p class="auth-subtitle">Introduce tu nueva contraseña.</p>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email', $request->email) }}"
                   class="form-control"
                   required
                   autofocus
                   autocomplete="username"
                   aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}">
            @error('email')
                <p id="email-error" class="form-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nueva contraseña -->
        <div class="form-group">
            <label for="password" class="form-label">Nueva contraseña</label>
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

        <!-- Confirmar contraseña -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
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

        <div class="auth-actions" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary auth-submit">
                Restablecer Contraseña
            </button>
        </div>
    </form>
</x-guest-layout>
