@php
    $imagenes = $producto->imagenes->map(fn ($img) => Storage::disk('public')->url($img->imagen))->values();
    $portada = $producto->portadaUrl();
    $variantes = $producto->productoColores->sortByDesc('es_predeterminado')->values();
    $varianteInicial = $variantes->firstWhere('stock', '>', 0) ?? $variantes->first();
@endphp
<x-tienda-layout :titulo="$producto->nombre">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-stone-500">
            <a href="{{ route('inicio') }}" class="hover:text-rose-700">Inicio</a> /
            <a href="{{ route('tienda.categoria', $producto->categoria) }}" class="hover:text-rose-700">{{ $producto->categoria->nombre }}</a> /
            <span class="text-stone-800">{{ $producto->nombre }}</span>
        </nav>

        <div class="mt-6 grid gap-10 md:grid-cols-2">
            <div x-data="{ actual: @js($portada) }">
                <div class="aspect-square overflow-hidden rounded-xl bg-stone-100">
                    @if ($portada)
                        <img :src="actual" src="{{ $portada }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                    @else
                        <x-tienda.imagen :producto="$producto" class="h-full w-full" />
                    @endif
                </div>
                @if ($imagenes->count() > 1)
                    <div class="mt-4 grid grid-cols-5 gap-3">
                        @foreach ($imagenes as $url)
                            <button type="button" @click="actual = @js($url)" class="aspect-square overflow-hidden rounded-md border-2" :class="actual === @js($url) ? 'border-rose-500' : 'border-transparent'">
                                <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                @if ($producto->marca)
                    <p class="text-sm uppercase tracking-wider text-stone-500">{{ $producto->marca->nombre }}</p>
                @endif
                <h1 class="mt-2 font-serif text-4xl font-semibold text-stone-900">{{ $producto->nombre }}</h1>

                <p class="mt-4 text-2xl">
                    @if ($producto->enOferta())
                        <span class="font-semibold text-rose-700">@precio($producto->precio_oferta)</span>
                        <span class="ml-2 text-lg text-stone-400 line-through">@precio($producto->precio)</span>
                    @else
                        <span class="font-semibold">@precio($producto->precio)</span>
                    @endif
                </p>

                @if ($producto->descripcion)
                    <div class="mt-6 text-stone-600 leading-relaxed">{!! nl2br(e($producto->descripcion)) !!}</div>
                @endif

                @if ($variantes->sum('stock') > 0)
                    <form method="POST" action="{{ route('carrito.agregar') }}" class="mt-8 space-y-6"
                          x-data="{ variante: {{ $varianteInicial->id }}, stock: @js($variantes->pluck('stock', 'id')) }">
                        @csrf

                        @if ($variantes->whereNotNull('color_id')->isNotEmpty())
                            <fieldset>
                                <legend class="text-sm font-semibold">Color</legend>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @foreach ($variantes as $variante)
                                        <label @class(['cursor-pointer', 'opacity-40 cursor-not-allowed' => $variante->stock < 1]) title="{{ $variante->color?->nombre ?? 'Único' }}{{ $variante->stock < 1 ? ' (agotado)' : '' }}">
                                            <input type="radio" name="producto_color_id" value="{{ $variante->id }}" x-model.number="variante" class="sr-only peer" @disabled($variante->stock < 1) @checked($variante->is($varianteInicial))>
                                            <span class="flex items-center gap-2 rounded-full border border-stone-300 px-3 py-1.5 text-sm peer-checked:border-stone-900 peer-checked:ring-1 peer-checked:ring-stone-900">
                                                <span class="h-4 w-4 rounded-full border border-stone-300" style="background-color: {{ $variante->color?->codigo_hex ?? '#e7e5e4' }}"></span>
                                                {{ $variante->color?->nombre ?? 'Único' }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @else
                            <input type="hidden" name="producto_color_id" value="{{ $varianteInicial->id }}">
                        @endif

                        <div class="flex items-end gap-4">
                            <div>
                                <label for="cantidad" class="text-sm font-semibold">Cantidad</label>
                                <input id="cantidad" type="number" name="cantidad" value="1" min="1" :max="stock[variante]" class="mt-2 w-24 rounded-md border-stone-300">
                            </div>
                            <button class="flex-1 rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700 transition">Agregar al carrito</button>
                        </div>
                        <p class="text-sm text-stone-500" x-text="stock[variante] <= 3 ? '¡Últimas ' + stock[variante] + ' unidades!' : 'En stock'"></p>
                    </form>
                @else
                    <p class="mt-8 rounded-md bg-stone-100 px-4 py-3 text-sm font-medium text-stone-600">Producto agotado por el momento.</p>
                @endif

                <dl class="mt-10 border-t border-stone-200 pt-6 text-sm grid grid-cols-2 gap-y-2">
                    <dt class="text-stone-500">Categoría</dt>
                    <dd>{{ $producto->categoria->nombre }}</dd>
                    @if ($producto->sku)
                        <dt class="text-stone-500">SKU</dt>
                        <dd>{{ $producto->sku }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        @if ($relacionados->isNotEmpty())
            <section class="mt-20">
                <h2 class="font-serif text-2xl font-semibold">También te puede gustar</h2>
                <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                    @foreach ($relacionados as $relacionado)
                        <x-tienda.producto-card :producto="$relacionado" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-tienda-layout>
