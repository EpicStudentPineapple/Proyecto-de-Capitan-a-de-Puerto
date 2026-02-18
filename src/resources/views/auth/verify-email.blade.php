<x-guest-layout>
    <h1 class="auth-title">Verifica tu Email</h1>

    <p class="auth-verify-text">
        ¡Gracias por registrarte! Antes de comenzar, verifica tu dirección de correo haciendo clic en el enlace que te hemos enviado. Si no lo has recibido, podemos enviarte otro.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert" aria-live="polite">
            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <div class="auth-verify-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Reenviar Email de Verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline">
                Cerrar Sesión
            </button>
        </form>
    </div>
</x-guest-layout>
