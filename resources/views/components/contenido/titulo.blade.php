@props([
    'clave',
    'claseDestacado' => '',
    'etiqueta' => 'em',
    'enLinea' => false,
])
{{--
    Título editable: "{clave}.titulo" (respeta saltos de línea), seguido de
    "{clave}.destacado" con otro estilo y, si existe, "{clave}.cierre".
--}}
@php
    $destacado = contenido($clave.'.destacado');
    $cierre = contenido($clave.'.cierre');
@endphp
{{ contenido_html($clave.'.titulo') }}@if ($destacado){!! $enLinea ? ' ' : '<br>' !!}<{{ $etiqueta }} @if ($claseDestacado) class="{{ $claseDestacado }}" @endif>{{ contenido_html($clave.'.destacado') }}</{{ $etiqueta }}>@endif @if ($cierre){{ $cierre }}@endif
