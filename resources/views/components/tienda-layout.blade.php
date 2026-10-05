@props(['titulo' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $titulo ? $titulo.' · ' : '' }}{{ config('tienda.nombre') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|playfair-display:500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-50 text-stone-800">
        <div class="bg-stone-900 text-stone-100 text-xs text-center py-2 px-4">
            Envío gratis en compras desde @precio(config('tienda.envio_gratis_desde'))
        </div>

        <header x-data="{ open: false }" class="bg-white border-b border-stone-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 gap-4">
                    <a href="{{ route('inicio') }}" class="font-serif text-2xl font-semibold tracking-wide text-stone-900">
                        {{ config('tienda.nombre') }}
                    </a>

                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                        <a href="{{ route('inicio') }}" @class(['hover:text-rose-700', 'text-rose-700' => request()->routeIs('inicio')])>Inicio</a>
                        <a href="{{ route('tienda.catalogo') }}" @class(['hover:text-rose-700', 'text-rose-700' => request()->routeIs('tienda.*')])>Tienda</a>
                        <a href="{{ route('contacto.create') }}" @class(['hover:text-rose-700', 'text-rose-700' => request()->routeIs('contacto.*')])>Contacto</a>
                    </nav>

                    <div class="flex items-center gap-4">
                        <form action="{{ route('tienda.catalogo') }}" method="GET" class="hidden lg:block">
                            <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar productos…"
                                   class="w-56 rounded-full border-stone-300 text-sm focus:border-rose-400 focus:ring-rose-400">
                        </form>

                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="text-sm font-medium hover:text-rose-700 flex items-center gap-1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.2-.6-7.5-1.65Z"/></svg>
                                        <span class="hidden sm:inline">{{ Str::of(Auth::user()->name)->before(' ') }}</span>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @if (Auth::user()->esAdmin())
                                        <x-dropdown-link :href="route('admin.dashboard')">Panel de administración</x-dropdown-link>
                                    @endif
                                    <x-dropdown-link :href="route('pedidos.index')">Mis pedidos</x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')">Mi perfil</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesión</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium hover:text-rose-700">Ingresar</a>
                        @endauth

                        <a href="{{ route('carrito.index') }}" class="relative hover:text-rose-700" aria-label="Carrito">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                            @if ($cantidadCarrito > 0)
                                <span class="absolute -top-2 -right-2 bg-rose-600 text-white text-[10px] font-semibold rounded-full h-5 min-w-5 px-1 flex items-center justify-center">{{ $cantidadCarrito }}</span>
                            @endif
                        </a>

                        <button @click="open = !open" class="md:hidden p-1" aria-label="Menú">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" x-cloak class="md:hidden border-t border-stone-200 px-4 py-3 space-y-2 text-sm">
                <form action="{{ route('tienda.catalogo') }}" method="GET">
                    <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar productos…" class="w-full rounded-full border-stone-300 text-sm">
                </form>
                <a href="{{ route('inicio') }}" class="block py-1">Inicio</a>
                <a href="{{ route('tienda.catalogo') }}" class="block py-1">Tienda</a>
                <a href="{{ route('contacto.create') }}" class="block py-1">Contacto</a>
            </div>
        </header>

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

        <footer class="bg-stone-900 text-stone-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 md:grid-cols-3 text-sm">
                <div>
                    <p class="font-serif text-2xl text-white">{{ config('tienda.nombre') }}</p>
                    <p class="mt-3 text-stone-400">Bolsos, calzado, joyería y accesorios seleccionados con cariño.</p>
                </div>
                <div>
                    <p class="font-semibold text-white mb-3">Tienda</p>
                    <ul class="space-y-2">
                        <li><a href="{{ route('tienda.catalogo') }}" class="hover:text-white">Todos los productos</a></li>
                        <li><a href="{{ route('carrito.index') }}" class="hover:text-white">Carrito</a></li>
                        @auth
                            <li><a href="{{ route('pedidos.index') }}" class="hover:text-white">Mis pedidos</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <p class="font-semibold text-white mb-3">Contacto</p>
                    <ul class="space-y-2">
                        <li>{{ config('tienda.email') }}</li>
                        @if (config('tienda.telefono'))
                            <li>{{ config('tienda.telefono') }}</li>
                        @endif
                        <li><a href="{{ route('contacto.create') }}" class="hover:text-white">Escríbenos</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-stone-800 py-4 text-center text-xs text-stone-500">
                © {{ date('Y') }} {{ config('tienda.nombre') }}. Todos los derechos reservados.
            </div>
        </footer>
    </body>
</html>
