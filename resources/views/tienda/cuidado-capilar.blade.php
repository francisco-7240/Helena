<x-tienda-layout titulo="Cuidado capilar">
    {{-- Hero: la foto es un banner a todo el ancho (incluye los productos) y el texto va encima, arriba a la derecha --}}
    <section class="relative overflow-hidden bg-helena-crema">
        <x-foto :src="contenido('cuidado_capilar.hero.imagen')" alt="Mujer con cabello largo y saludable junto a los productos Helena"
                class="h-80 w-full object-cover object-left md:absolute md:inset-0 md:h-full" />

        <div class="relative max-w-7xl mx-auto px-6 sm:px-12 md:flex md:min-h-[34rem] md:justify-end lg:min-h-[38rem]">
            <div class="py-14 md:w-[56%] md:pt-16 md:pb-0">
                <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-[0.95] text-helena-verde-oscuro">
                    <x-contenido.titulo clave="cuidado_capilar.hero" clase-destacado="text-[#5b3f2e]" />
                </h1>
                <p class="mt-5 max-w-md text-sm leading-relaxed text-helena-oscuro/80">{{ contenido_html('cuidado_capilar.hero.texto') }}</p>
                <a href="#que-es" class="group mt-6 inline-flex items-center gap-3 bg-helena-rosa-oscuro px-6 py-3 text-[10px] font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-oscuro md:float-right md:mt-4">
                    {{ contenido('cuidado_capilar.hero.boton') }}
                    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ¿Qué es el cuidado capilar? --}}
    <section id="que-es" class="scroll-mt-20 bg-helena-crema/60 py-4">
        <div class="grid items-center md:grid-cols-[0.9fr_1.1fr]">
            <div class="px-6 sm:px-12 lg:px-16 py-14">
                <h2 class="font-serif text-4xl sm:text-5xl leading-tight"><x-contenido.titulo clave="cuidado_capilar.que_es" /></h2>
                <div class="mt-5 h-0.5 w-14 bg-helena-rosa-oscuro"></div>
                <p class="mt-6 max-w-md text-sm leading-relaxed text-helena-oscuro/80">{{ contenido_html('cuidado_capilar.que_es.texto') }}</p>
            </div>
            <x-foto :src="contenido('cuidado_capilar.que_es.imagen')" alt="Mujer acomodando su cabello" class="h-72 w-full md:h-96" />
        </div>
    </section>

    {{-- Tu rutina capilar --}}
    <section class="bg-helena-crema/60 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="flex items-center justify-center gap-4 text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">
                <span class="h-px w-16 bg-helena-oscuro/20"></span>{{ contenido('cuidado_capilar.rutina.etiqueta') }}<span class="h-px w-16 bg-helena-oscuro/20"></span>
            </p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">{{ contenido_html('cuidado_capilar.rutina.titulo') }}</h2>

            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (range(1, 4) as $n)
                    <article>
                        <x-foto :src="contenido('cuidado_capilar.rutina.imagen_'.$n)" :alt="contenido('cuidado_capilar.rutina.titulo_'.$n)" class="aspect-[3/2] w-full" />
                        <h3 class="mt-4 font-serif text-3xl">{{ contenido('cuidado_capilar.rutina.titulo_'.$n) }}</h3>
                        <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-helena-oscuro/80">{{ contenido_html('cuidado_capilar.rutina.texto_'.$n) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nuestros productos --}}
    <section class="bg-helena-arena/50 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">{{ contenido('cuidado_capilar.productos.etiqueta') }}</p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">{{ contenido_html('cuidado_capilar.productos.titulo') }}</h2>

            @if ($productos->isEmpty())
                <p class="mt-10 text-sm text-stone-500">Muy pronto tendremos productos disponibles.</p>
            @else
                <div class="mt-10 grid grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
                    @foreach ($productos as $producto)
                        <a href="{{ route('tienda.producto', $producto) }}" class="group block">
                            <div class="overflow-hidden">
                                <x-tienda.imagen :producto="$producto" class="aspect-[4/5] w-full transition duration-500 group-hover:scale-105" />
                            </div>
                            <h3 class="mt-4 font-serif text-2xl leading-tight group-hover:text-helena-verde">{{ $producto->nombre }}</h3>
                            @if ($producto->descripcion)
                                <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-helena-oscuro/80">{{ Str::limit($producto->descripcion, 120) }}</p>
                            @endif
                            <p class="mt-2 text-sm font-medium">@precio($producto->precioFinal())</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Nuestra filosofía --}}
    <section class="relative overflow-hidden bg-helena-crema/60 py-16">
        <div class="relative max-w-6xl mx-auto px-6 text-center">
            <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">{{ contenido('cuidado_capilar.filosofia.etiqueta') }}</p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">{{ contenido_html('cuidado_capilar.filosofia.titulo') }}</h2>

            <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-helena-oscuro/15">
                @foreach (['hoja', 'gota', 'brillo', 'loto'] as $indice => $icono)
                    @php($n = $indice + 1)
                    <article class="px-6">
                        <svg class="mx-auto h-11 w-11 text-helena-verde" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @switch($icono)
                                @case('hoja')
                                    <path d="M10 38C10 20 22 10 40 8c0 18-10 30-28 30Z"/><path d="M10 38 30 18M18 30h8M22 26v-8"/>
                                    @break
                                @case('gota')
                                    <path d="M24 6s-12 14-12 23a12 12 0 0 0 24 0C36 20 24 6 24 6Z"/><path d="M18 30a6 6 0 0 0 6 6"/>
                                    @break
                                @case('brillo')
                                    <path d="M20 12c1 7 3 9 10 10-7 1-9 3-10 10-1-7-3-9-10-10 7-1 9-3 10-10Z"/><path d="M34 6c.5 3 1.5 4 4.5 4.5-3 .5-4 1.5-4.5 4.5-.5-3-1.5-4-4.5-4.5 3-.5 4-1.5 4.5-4.5ZM34 30c.5 2.5 1.2 3.2 3.5 3.5-2.3.3-3 1-3.5 3.5-.5-2.5-1.2-3.2-3.5-3.5 2.3-.3 3-1 3.5-3.5Z"/>
                                    @break
                                @default
                                    <path d="M24 36c-5-4-7-10-5-18 1 0 3 2 5 4 2-2 4-4 5-4 2 8 0 14-5 18Z"/><path d="M24 36c-7 0-13-4-15-12 4-1 8 0 10 2M24 36c7 0 13-4 15-12-4-1-8 0-10 2M24 36c-9 2-16 0-20-5 3-2 7-2 10-1M24 36c9 2 16 0 20-5-3-2-7-2-10-1"/>
                            @endswitch
                        </svg>
                        <h3 class="mt-5 font-serif text-2xl leading-tight">{{ contenido_html('cuidado_capilar.filosofia.titulo_'.$n) }}</h3>
                        <p class="mx-auto mt-3 max-w-[14rem] text-sm leading-relaxed text-helena-oscuro/80">{{ contenido_html('cuidado_capilar.filosofia.texto_'.$n) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nuestro manifiesto --}}
    <section class="relative overflow-hidden bg-helena-rosa-claro">
        <div class="grid items-center md:grid-cols-[0.8fr_1.2fr]">
            <x-foto :src="contenido('cuidado_capilar.manifiesto.imagen')" alt="Flor rosada" class="h-64 w-full md:h-[24rem]" />
            <div class="px-6 sm:px-12 py-14 text-center">
                <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">{{ contenido('cuidado_capilar.manifiesto.etiqueta') }}</p>
                <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl lg:text-6xl leading-tight text-[#5b3f2e]">{{ contenido_html('cuidado_capilar.manifiesto.titulo') }}</h2>
                <p class="mx-auto mt-5 max-w-lg text-sm leading-relaxed text-helena-oscuro/80">{{ contenido_html('cuidado_capilar.manifiesto.texto') }}</p>
                <a href="{{ $categoria ? route('tienda.categoria', $categoria) : route('tienda.catalogo') }}" class="group mt-7 inline-flex items-center gap-3 bg-helena-verde-oscuro px-7 py-3 text-[11px] font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-oscuro">
                    {{ contenido('cuidado_capilar.manifiesto.boton') }}
                    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-tienda-layout>
