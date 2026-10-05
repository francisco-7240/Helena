@props(['producto', 'url' => null])
@php($url ??= $producto->portadaUrl())
@if ($url)
    <img src="{{ $url }}" alt="{{ $producto->nombre }}" loading="lazy" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center bg-gradient-to-br from-helena-arena via-stone-100 to-helena-crema']) }}>
        <span class="font-serif text-4xl text-helena-verde/40">{{ Str::upper(Str::substr($producto->nombre, 0, 1)) }}</span>
    </div>
@endif
