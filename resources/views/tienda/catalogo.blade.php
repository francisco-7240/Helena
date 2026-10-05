<x-tienda-layout :titulo="$categoriaActual?->nombre ?? 'Tienda'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-stone-500">
            <a href="{{ route('inicio') }}" class="hover:text-rose-700">Inicio</a> /
            <a href="{{ route('tienda.catalogo') }}" class="hover:text-rose-700">Tienda</a>
            @if ($categoriaActual)
                / <span class="text-stone-800">{{ $categoriaActual->nombre }}</span>
            @endif
        </nav>

        <h1 class="mt-4 font-serif text-4xl font-semibold">{{ $categoriaActual?->nombre ?? 'Todos los productos' }}</h1>
        @if ($categoriaActual?->descripcion)
            <p class="mt-2 text-stone-600">{{ $categoriaActual->descripcion }}</p>
        @endif

        <div class="mt-8 grid gap-10 lg:grid-cols-[220px_1fr]">
            <aside>
                <form method="GET" action="{{ $categoriaActual ? route('tienda.categoria', $categoriaActual) : route('tienda.catalogo') }}" class="space-y-6 text-sm">
                    <div>
                        <label for="buscar" class="font-semibold">Buscar</label>
                        <input id="buscar" type="search" name="buscar" value="{{ $filtros['buscar'] ?? '' }}" class="mt-2 w-full rounded-md border-stone-300 text-sm">
                    </div>

                    <div>
                        <p class="font-semibold">Categorías</p>
                        <ul class="mt-2 space-y-1">
                            <li><a href="{{ route('tienda.catalogo') }}" @class(['hover:text-rose-700', 'text-rose-700 font-medium' => ! $categoriaActual])>Todas</a></li>
                            @foreach ($categorias as $categoria)
                                <li><a href="{{ route('tienda.categoria', $categoria) }}" @class(['hover:text-rose-700', 'text-rose-700 font-medium' => $categoriaActual?->is($categoria)])>{{ $categoria->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    @if ($marcas->isNotEmpty())
                        <div>
                            <label for="marca" class="font-semibold">Marca</label>
                            <select id="marca" name="marca" class="mt-2 w-full rounded-md border-stone-300 text-sm">
                                <option value="">Todas</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->slug }}" @selected(($filtros['marca'] ?? '') === $marca->slug)>{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($colores->isNotEmpty())
                        <div>
                            <p class="font-semibold">Color</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($colores as $color)
                                    <label title="{{ $color->nombre }}" class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color->slug }}" class="sr-only peer" @checked(($filtros['color'] ?? '') === $color->slug)>
                                        <span class="block h-7 w-7 rounded-full border border-stone-300 peer-checked:ring-2 peer-checked:ring-rose-500 peer-checked:ring-offset-2" style="background-color: {{ $color->codigo_hex }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="orden" class="font-semibold">Ordenar por</label>
                        <select id="orden" name="orden" class="mt-2 w-full rounded-md border-stone-300 text-sm">
                            <option value="recientes" @selected(($filtros['orden'] ?? '') === 'recientes')>Más recientes</option>
                            <option value="precio_asc" @selected(($filtros['orden'] ?? '') === 'precio_asc')>Precio: menor a mayor</option>
                            <option value="precio_desc" @selected(($filtros['orden'] ?? '') === 'precio_desc')>Precio: mayor a menor</option>
                            <option value="nombre" @selected(($filtros['orden'] ?? '') === 'nombre')>Nombre</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 rounded-md bg-stone-900 px-4 py-2 font-semibold text-white hover:bg-rose-700">Filtrar</button>
                        <a href="{{ $categoriaActual ? route('tienda.categoria', $categoriaActual) : route('tienda.catalogo') }}" class="rounded-md border border-stone-300 px-4 py-2">Limpiar</a>
                    </div>
                </form>
            </aside>

            <div>
                <p class="text-sm text-stone-500">{{ $productos->total() }} {{ Str::plural('producto', $productos->total()) }}</p>

                @if ($productos->isEmpty())
                    <div class="mt-10 rounded-lg border border-dashed border-stone-300 p-10 text-center text-stone-500">
                        No encontramos productos con esos filtros.
                    </div>
                @else
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10">
                        @foreach ($productos as $producto)
                            <x-tienda.producto-card :producto="$producto" />
                        @endforeach
                    </div>
                    <div class="mt-10">{{ $productos->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-tienda-layout>
