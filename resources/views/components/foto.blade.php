@props(['src', 'alt' => ''])
{{-- Muestra una imagen de public/ o un fondo suave si el archivo aún no existe. --}}
@if (file_exists(public_path($src)))
    <img src="{{ asset($src) }}" alt="{{ $alt }}" loading="lazy" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div role="img" aria-label="{{ $alt }}" {{ $attributes->merge(['class' => 'bg-gradient-to-br from-helena-arena via-helena-crema to-[#e6d6c8]']) }}></div>
@endif
