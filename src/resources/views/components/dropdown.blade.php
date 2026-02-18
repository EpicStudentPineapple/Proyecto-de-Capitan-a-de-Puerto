@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

{{--
    CORRECCIÓN:
    - position: relative en el wrapper para anclar el panel
    - position: absolute + z-index: 200 en el panel (superior al navbar z-index:100)
    - min-width y background explícitos para no depender solo de Tailwind
--}}
<div style="position: relative;" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="{{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
        style="
            display: none;
            position: absolute;
            z-index: 200;
            top: calc(100% + 0.5rem);
            min-width: 12rem;
        "
        @click="open = false">
        <div class="rounded-md {{ $contentClasses }}" style="
            background-color: var(--color-surface, #fff);
            border: 1px solid var(--color-border, #e5e7eb);
            border-radius: var(--radius-md, 0.375rem);
            box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0,0,0,.1));
            overflow: hidden;
        ">
            {{ $content }}
        </div>
    </div>
</div>