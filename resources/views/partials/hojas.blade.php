{{-- Silueta decorativa usada como fondo. Por defecto storage/app/public/recursos/SILUETA.png; se puede cambiar con $src. --}}
@php($src ??= 'storage/recursos/SILUETA.png')
@if (file_exists(public_path($src)))
    <img src="{{ asset($src) }}" alt="" aria-hidden="true" class="object-contain {{ $clase ?? 'pointer-events-none absolute' }}">
@endif
