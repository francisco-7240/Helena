@props(['titulo' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $titulo ? $titulo.' · ' : '' }}{{ config('tienda.nombre') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,400i,500,500i,600,600i|montserrat:300,400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-helena-oscuro">
        @include('partials.header')

        @if (session('status'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->hasAny(['carrito', 'producto_color_id', 'cantidad']))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                    {{ $errors->first('carrito') ?: $errors->first('producto_color_id') ?: $errors->first('cantidad') }}
                </div>
            </div>
        @endif

        <main class="min-h-[60vh]">
            {{ $slot }}
        </main>

        @include('partials.footer')
    </body>
</html>
