{{-- Cabecera de la tienda: navegación, logo centrado, búsqueda, cuenta y carrito. --}}
@php
    $enlacesIzquierda = [
        ['Inicio', route('inicio')],
        ['Nosotros', route('inicio').'#nosotros'],
        ['Autocuidado', route('inicio').'#autocuidado'],
    ];
    $enlacesDerecha = [
        ['Cuidado Capilar', route('inicio').'#cuidado-capilar'],
        ['Tienda', route('tienda.catalogo')],
    ];
@endphp
<header x-data="{ abierto: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-helena-arena">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-[1fr_auto_1fr] items-center h-20 gap-4">
            {{-- Izquierda --}}
            <div class="flex items-center">
                <button type="button" @click="abierto = !abierto" class="lg:hidden p-1 text-helena-oscuro" aria-label="Abrir menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <nav class="hidden lg:flex items-center gap-3 text-[13px] text-helena-oscuro">
                    @foreach ($enlacesIzquierda as [$texto, $url])
                        @unless ($loop->first)<span class="text-helena-verde/50">·</span>@endunless
                        <a href="{{ $url }}" class="hover:text-helena-verde transition">{{ $texto }}</a>
                    @endforeach
                </nav>
            </div>

            {{-- Logo --}}
            <a href="{{ route('inicio') }}" class="justify-self-center" aria-label="{{ config('tienda.nombre') }} - inicio">
                @include('partials.logo')
            </a>

            {{-- Derecha --}}
            <div class="flex items-center justify-end gap-4">
                <nav class="hidden lg:flex items-center gap-3 text-[13px] text-helena-oscuro">
                    @foreach ($enlacesDerecha as [$texto, $url])
                        @unless ($loop->first)<span class="text-helena-verde/50">·</span>@endunless
                        <a href="{{ $url }}" class="hover:text-helena-verde transition">{{ $texto }}</a>
                    @endforeach
                </nav>

                <form action="{{ route('tienda.catalogo') }}" method="GET" class="hidden xl:block relative">
                    <label for="buscar-cabecera" class="sr-only">Buscar productos</label>
                    <input id="buscar-cabecera" type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar productos"
                           class="w-44 rounded-full border-helena-oscuro/30 py-1.5 pl-4 pr-8 text-xs placeholder:text-stone-400 focus:border-helena-verde focus:ring-helena-verde">
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                </form>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="text-helena-oscuro hover:text-helena-verde" aria-label="Mi cuenta">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.2-.6-7.5-1.65Z"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-stone-500">{{ Auth::user()->name }}</div>
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
                    <a href="{{ route('login') }}" class="hidden sm:block text-helena-oscuro hover:text-helena-verde" aria-label="Ingresar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.2-.6-7.5-1.65Z"/></svg>
                    </a>
                @endauth

                <a href="{{ route('carrito.index') }}" class="relative text-helena-oscuro hover:text-helena-verde" aria-label="Carrito">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M2 3h2.6l.6 2H21l-2.2 8.2a1.5 1.5 0 0 1-1.4 1.1H8.3l.4 1.5H18v2H7.2L4.2 5H2V3Zm5.7 4 1.6 5.3h8l1.4-5.3h-11ZM9 20.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm10 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                    @if ($cantidadCarrito > 0)
                        <span class="absolute -top-2 -right-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-helena-verde px-1 text-[10px] font-semibold text-white">{{ $cantidadCarrito }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    {{-- Menú móvil --}}
    <div x-show="abierto" x-cloak x-transition class="lg:hidden border-t border-helena-arena bg-white px-4 py-4 space-y-3 text-sm">
        <form action="{{ route('tienda.catalogo') }}" method="GET">
            <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar productos" aria-label="Buscar productos"
                   class="w-full rounded-full border-helena-oscuro/30 text-sm focus:border-helena-verde focus:ring-helena-verde">
        </form>
        @foreach (array_merge($enlacesIzquierda, $enlacesDerecha) as [$texto, $url])
            <a href="{{ $url }}" @click="abierto = false" class="block py-1 text-helena-oscuro">{{ $texto }}</a>
        @endforeach
        @guest
            <a href="{{ route('login') }}" class="block py-1 text-helena-verde font-medium">Ingresar</a>
        @endguest
    </div>
</header>
