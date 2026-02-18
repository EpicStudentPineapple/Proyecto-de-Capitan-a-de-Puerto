<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tutorial de Uso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        @if(Auth::user()->isAdmin())
                            {{ __('Video Tutorial para Administradores') }}
                        @else
                            {{ __('Video Tutorial para Usuarios') }}
                        @endif
                    </h3>
                    
                    <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg overflow-hidden mb-4">
                        <video 
                            id="tutorialVideo" 
                            class="w-full h-full" 
                            controls 
                            preload="metadata"
                            aria-label="{{ Auth::user()->isAdmin() ? 'Tutorial de administración' : 'Tutorial de usuario' }}"
                        >
                            @if(Auth::user()->isAdmin())
                                <source src="{{ asset('assets/videos/admin_video.mkv') }}" type="video/x-matroska">
                                <track 
                                    label="Español" 
                                    kind="subtitles" 
                                    srclang="es" 
                                    src="{{ asset('assets/videos/admin_subtitles.vtt') }}" 
                                    default
                                >
                            @else
                                <source src="{{ asset('assets/videos/user_video.mkv') }}" type="video/x-matroska">
                                <track 
                                    label="Español" 
                                    kind="subtitles" 
                                    srclang="es" 
                                    src="{{ asset('assets/videos/user_subtitles.vtt') }}" 
                                    default
                                >
                            @endif
                            Tu navegador no soporta el elemento de video.
                        </video>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between text-sm text-gray-500 border-t pt-4">
                    <p>Cumple con el estándar WCAG 2.0 Nivel A/AA: Incluye controles, subtítulos y transcripción de texto.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
