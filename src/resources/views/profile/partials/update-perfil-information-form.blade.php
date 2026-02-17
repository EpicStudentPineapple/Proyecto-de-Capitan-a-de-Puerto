<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualiza la información detallada de tu cuenta.") }}
        </p>
    </header>

    <form method="post" action="{{ route('perfil.actualizar-mi-perfil') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="empresa" :value="__('Empresa')" />
            <x-text-input id="empresa" name="empresa" type="text" class="mt-1 block w-full" :value="old('empresa', $user->perfil->empresa ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('empresa')" />
        </div>

        <div>
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $user->perfil->telefono ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>

        <div>
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion', $user->perfil->direccion ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
        </div>

        <div>
            <x-input-label for="cif" :value="__('NIF/CIF')" />
            <x-text-input id="cif" name="cif" type="text" class="mt-1 block w-full" :value="old('cif', $user->perfil->cif ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('cif')" />
        </div>

        <div>
            <x-input-label for="licencia_maritima" :value="__('Licencia Marítima')" />
            <x-text-input id="licencia_maritima" name="licencia_maritima" type="text" class="mt-1 block w-full" :value="old('licencia_maritima', $user->perfil->licencia_maritima ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('licencia_maritima')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'perfil-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
