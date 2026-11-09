{{-- Silueta decorativa usada como fondo. Por defecto storage/app/public/recursos/SILUETA.png; se puede cambiar con $src. --}}
<img src="{{ asset($src ?? 'storage/recursos/SILUETA.png') }}" alt="" aria-hidden="true" class="object-contain {{ $clase ?? 'pointer-events-none absolute' }}">
