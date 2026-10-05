@props(['titulo' => 'Panel'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $titulo }} · Admin {{ config('tienda.nombre') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|playfair-display:600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-100 text-stone-800">
        @php
            $enlaces = [
                ['admin.dashboard', 'admin.dashboard', 'Resumen'],
                ['admin.pedidos.index', 'admin.pedidos.*', 'Pedidos'],
                ['admin.productos.index', 'admin.productos.*', 'Productos'],
                ['admin.categorias.index', 'admin.categorias.*', 'Categorías'],
                ['admin.marcas.index', 'admin.marcas.*', 'Marcas'],
                ['admin.colores.index', 'admin.colores.*', 'Colores'],
                ['admin.contactos.index', 'admin.contactos.*', 'Mensajes'],
            ];
        @endphp
        <div x-data="{ menu: false }" class="min-h-screen md:flex">
            <aside :class="menu ? 'block' : 'hidden'" class="md:block md:w-60 shrink-0 bg-stone-900 text-stone-300">
                <div class="px-6 py-5">
                    <a href="{{ route('admin.dashboard') }}" class="font-serif text-2xl text-white">{{ config('tienda.nombre') }}</a>
                    <p class="text-xs uppercase tracking-wider text-stone-500">Administración</p>
                </div>
                <nav class="px-3 pb-6 space-y-1 text-sm">
                    @foreach ($enlaces as [$ruta, $patron, $texto])
                        <a href="{{ route($ruta) }}" @class(['block rounded-md px-3 py-2 hover:bg-stone-800 hover:text-white', 'bg-stone-800 text-white' => request()->routeIs($patron)])>{{ $texto }}</a>
                    @endforeach
                    <div class="my-3 border-t border-stone-800"></div>
                    <a href="{{ route('inicio') }}" class="block rounded-md px-3 py-2 hover:bg-stone-800 hover:text-white">Ver tienda ↗</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left rounded-md px-3 py-2 hover:bg-stone-800 hover:text-white">Cerrar sesión</button>
                    </form>
                </nav>
            </aside>

            <div class="flex-1 min-w-0">
                <header class="bg-white border-b border-stone-200">
                    <div class="flex items-center justify-between gap-4 px-4 sm:px-8 h-16">
                        <div class="flex items-center gap-3">
                            <button @click="menu = !menu" class="md:hidden" aria-label="Menú">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                            <h1 class="text-lg font-semibold">{{ $titulo }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            {{ $acciones ?? '' }}
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
