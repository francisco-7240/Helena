{{-- Sombra decorativa de hojas usada como fondo en varias secciones. --}}
<svg aria-hidden="true" viewBox="0 0 400 400" fill="currentColor" class="{{ $clase ?? 'pointer-events-none absolute' }}">
    <path d="M200 400C200 300 190 200 150 90" stroke="currentColor" stroke-width="4" fill="none"/>
    @foreach ([[175, 320, -40], [185, 260, 35], [168, 210, -45], [178, 160, 40], [158, 120, -50], [162, 80, 30]] as [$x, $y, $angulo])
        <ellipse cx="{{ $x }}" cy="{{ $y }}" rx="55" ry="16" transform="rotate({{ $angulo }} {{ $x }} {{ $y }}) translate({{ $angulo > 0 ? 45 : -45 }} 0)"/>
    @endforeach
    <path d="M250 400C260 330 290 260 340 200" stroke="currentColor" stroke-width="3" fill="none"/>
    @foreach ([[268, 340, 50], [285, 290, -30], [305, 245, 55]] as [$x, $y, $angulo])
        <ellipse cx="{{ $x }}" cy="{{ $y }}" rx="40" ry="12" transform="rotate({{ $angulo }} {{ $x }} {{ $y }}) translate({{ $angulo > 0 ? 32 : -32 }} 0)"/>
    @endforeach
</svg>
