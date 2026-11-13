<x-tienda-layout>
    {{-- Redes sociales flotantes --}}
    <div class="fixed left-3 top-1/3 z-30 hidden md:flex flex-col items-center gap-3 text-helena-oscuro">
        @include('partials.iconos-redes', ['clase' => 'h-4 w-4'])
    </div>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-helena-arena">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -left-10 -bottom-10 h-[34rem] w-[34rem] opacity-40'])
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute left-1/3 -top-24 h-[26rem] w-[26rem] rotate-180 opacity-30'])

        <div class="relative max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-20 lg:py-24 grid items-center gap-10 md:grid-cols-2">
            <div>
                <h1 class="font-serif text-6xl sm:text-7xl lg:text-8xl leading-[0.9] text-helena-oscuro">
                    <x-contenido.titulo clave="inicio.hero" clase-destacado="text-helena-verde" />
                </h1>
                <p class="mt-6 text-base sm:text-lg text-helena-oscuro">{{ contenido('inicio.hero.texto') }}</p>
                <x-tienda.boton-flecha :href="route('cuidado-capilar')" class="mt-8">{{ contenido('inicio.hero.boton') }}</x-tienda.boton-flecha>
            </div>
            <div class="flex justify-center md:justify-end">
                <x-foto :src="contenido('inicio.hero.imagen')" alt="Productos de cuidado capilar Helena" class="aspect-[4/3] w-full max-w-xl rounded-sm object-contain" />
            </div>
        </div>
    </section>

    {{-- ¿Qué es Helena? --}}
    <section id="nosotros" class="grid md:grid-cols-2 scroll-mt-20">
        <div class="flex items-center px-6 sm:px-10 lg:px-24 py-20">
            <div class="max-w-sm">
                <p class="text-xs sm:text-sm text-helena-oscuro">{{ contenido('inicio.que_es.etiqueta') }}</p>
                <h2 class="mt-4 font-serif italic text-5xl lg:text-6xl leading-tight">
                    <x-contenido.titulo clave="inicio.que_es" clase-destacado="text-helena-verde" />
                </h2>
                <p class="mt-6 text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('inicio.que_es.texto') }}</p>
                <x-tienda.boton-flecha :href="route('nosotros')" class="mt-8">{{ contenido('inicio.que_es.boton') }}</x-tienda.boton-flecha>
            </div>
        </div>
        <x-foto :src="contenido('inicio.que_es.imagen')" alt="Mujeres con distintos tipos de cabello" class="h-80 w-full md:h-full md:min-h-[30rem]" />
    </section>

    {{-- Autocuidado --}}
    <section id="autocuidado" class="bg-helena-crema py-20 scroll-mt-20">
        <div class="max-w-5xl mx-auto px-6">
            <p class="text-xs sm:text-sm text-helena-oscuro">{{ contenido('inicio.autocuidado.etiqueta') }}</p>
            <h2 class="mt-2 font-serif italic text-5xl lg:text-6xl">{{ contenido('inicio.autocuidado.titulo') }}</h2>

            <div class="mt-10 grid gap-8 sm:grid-cols-3">
                @foreach (range(1, 3) as $n)
                    <article>
                        <x-foto :src="contenido('inicio.autocuidado.imagen_'.$n)" :alt="contenido('inicio.autocuidado.titulo_'.$n)" class="aspect-[5/6] w-full" />
                        <h3 class="mt-4 font-serif italic text-3xl">{{ contenido('inicio.autocuidado.titulo_'.$n) }}</h3>
                        <p class="mt-1 max-w-[16rem] text-sm sm:text-base leading-relaxed text-helena-oscuro/70">{{ contenido_html('inicio.autocuidado.texto_'.$n) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cuidado capilar --}}
    <section id="cuidado-capilar" class="relative overflow-hidden bg-helena-verde text-white scroll-mt-20">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute right-0 -bottom-20 h-[36rem] w-[36rem] opacity-60'])
        <div class="relative max-w-5xl mx-auto px-6 py-24 grid gap-12 md:grid-cols-2 items-center">
            <div>
                <p class="text-xs sm:text-sm text-white/80">{{ contenido('inicio.capilar.etiqueta') }}</p>
                <h2 class="mt-4 font-serif text-6xl lg:text-7xl leading-[0.95]"><x-contenido.titulo clave="inicio.capilar" /></h2>
                <p class="mt-6 max-w-xs text-sm sm:text-base leading-relaxed text-white/70">{{ contenido_html('inicio.capilar.texto') }}</p>
                <x-tienda.boton-flecha :href="route('tienda.catalogo')" claro class="mt-8">{{ contenido('inicio.capilar.boton') }}</x-tienda.boton-flecha>
            </div>
            <ul class="md:justify-self-center w-full max-w-[14rem] font-serif italic text-2xl">
                @foreach (range(1, 4) as $n)
                    <li class="border-b border-white/40 py-2">{{ contenido('inicio.capilar.titulo_'.$n) }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Productos --}}
    <section class="py-24">
        <div class="max-w-5xl mx-auto px-6">
            <p class="text-xs sm:text-sm text-helena-oscuro">{{ contenido('inicio.productos.etiqueta') }}</p>
            <h2 class="mt-3 font-serif text-5xl lg:text-6xl leading-[0.95]"><x-contenido.titulo clave="inicio.productos" clase-destacado="text-helena-verde" /></h2>

            @if ($productos->isEmpty())
                <p class="mt-10 text-sm text-stone-500">Muy pronto tendremos productos disponibles.</p>
            @else
                <div class="mt-12 grid grid-cols-2 gap-x-5 gap-y-10 lg:grid-cols-4">
                    @foreach ($productos as $producto)
                        @php($variante = $producto->productoColores->sortByDesc('es_predeterminado')->firstWhere('stock', '>', 0))
                        <article class="flex flex-col">
                            <a href="{{ route('tienda.producto', $producto) }}" class="block overflow-hidden">
                                <x-tienda.imagen :producto="$producto" class="aspect-[3/4] w-full transition duration-500 hover:scale-105" />
                            </a>
                            <h3 class="mt-4 font-serif text-xl leading-tight">
                                <a href="{{ route('tienda.producto', $producto) }}" class="hover:text-helena-verde">{{ $producto->nombre }}</a>
                            </h3>
                            @if ($producto->descripcion)
                                <p class="mt-1 text-sm sm:text-base leading-relaxed text-helena-oscuro/60">{{ Str::limit($producto->descripcion, 60) }}</p>
                            @endif
                            <p class="mt-1 text-xs font-medium">@precio($producto->precioFinal())</p>
                            <div class="mt-3">
                                @if ($variante)
                                    <form method="POST" action="{{ route('carrito.agregar') }}">
                                        @csrf
                                        <input type="hidden" name="producto_color_id" value="{{ $variante->id }}">
                                        <input type="hidden" name="cantidad" value="1">
                                        <button class="group inline-flex items-center gap-2 rounded-full bg-helena-verde px-4 py-1.5 text-xs text-white transition hover:bg-helena-verde-oscuro">
                                            Añadir al carrito
                                            <svg class="h-2.5 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex rounded-full bg-stone-200 px-4 py-1.5 text-xs text-stone-500">Agotado</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-14 text-center">
                    <x-tienda.boton-flecha :href="route('tienda.catalogo')">{{ contenido('inicio.productos.boton') }}</x-tienda.boton-flecha>
                </div>
            @endif
        </div>
    </section>

    {{-- Manifiesto --}}
    <section class="relative overflow-hidden bg-helena-rosa">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -left-16 top-0 h-[34rem] w-[34rem] opacity-70'])
        <div class="relative max-w-5xl mx-auto px-6 py-28 grid md:grid-cols-2">
            <div class="md:col-start-2">
                <p class="text-xs sm:text-sm text-helena-oscuro">{{ contenido('inicio.manifiesto.etiqueta') }}</p>
                <h2 class="mt-4 font-serif text-5xl lg:text-6xl leading-[1.05]"><x-contenido.titulo clave="inicio.manifiesto" clase-destacado="text-helena-verde" /></h2>
                <p class="mt-6 max-w-sm text-sm sm:text-base leading-relaxed text-white">{{ contenido_html('inicio.manifiesto.texto') }}</p>
                <x-tienda.boton-flecha :href="route('nosotros')" claro class="mt-8">{{ contenido('inicio.manifiesto.boton') }}</x-tienda.boton-flecha>
            </div>
        </div>
    </section>
</x-tienda-layout>
