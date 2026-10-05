{{-- Logo de Helena: usa public/images/helena/logo.png si existe, si no, el logotipo en texto. --}}
@php($claro = $claro ?? false)
@if (file_exists(public_path('images/helena/logo.png')) && ! $claro)
    <img src="{{ asset('images/helena/logo.png') }}" alt="{{ config('tienda.nombre') }}" class="h-12 w-auto">
@elseif (file_exists(public_path('images/helena/logo-blanco.png')) && $claro)
    <img src="{{ asset('images/helena/logo-blanco.png') }}" alt="{{ config('tienda.nombre') }}" class="h-14 w-auto">
@else
    <span class="flex flex-col items-center leading-none">
        <span @class(['font-serif italic font-semibold text-4xl tracking-wide', 'text-helena-verde' => ! $claro, 'text-white' => $claro])>{{ config('tienda.nombre') }}</span>
        <span @class(['mt-1 text-[9px] uppercase tracking-[0.45em]', 'text-helena-verde/80' => ! $claro, 'text-white/70' => $claro])>{{ config('tienda.eslogan') }}</span>
    </span>
@endif
