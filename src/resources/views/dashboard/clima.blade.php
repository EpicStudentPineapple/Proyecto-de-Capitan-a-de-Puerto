{{--
    ARCHIVO: resources/views/components/clima.blade.php

    DEPENDENCIAS — copia cada archivo a su ruta:
      public/css/euskalmet.css   ← euskalmet.css
      public/js/euskalmet.js     ← euskalmet.js

    USO en cualquier blade:
      @include('components.clima')
--}}

<link rel="stylesheet" href="{{ asset('css/euskalmet.css') }}">

<div id="euskalmet-widget" class="em-widget em-widget--cargando" aria-live="polite" aria-label="Pronóstico meteorológico">
    <div class="em-spinner-wrap">
        <span class="em-spinner"></span>
        <span class="em-cargando-txt">Cargando datos meteorológicos&hellip;</span>
    </div>
</div>

<script src="{{ asset('js/euskalmet.js') }}"></script>
<script>
    // Euskalmet.js ya está cargado de forma síncrona arriba.
    // Iniciamos el widget en cuanto el DOM esté listo.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Euskalmet.init('euskalmet-widget'));
    } else {
        Euskalmet.init('euskalmet-widget');
    }
</script>