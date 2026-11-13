<x-tienda-layout titulo="Autocuidado">
    {{-- Hero: la foto es un banner a todo el ancho y el texto va encima, a la derecha --}}
    <section class="relative overflow-hidden bg-helena-crema">
        <x-foto :src="contenido('autocuidado.hero.imagen')" alt="Mujer disfrutando un momento de autocuidado"
                class="h-80 w-full object-cover object-left md:absolute md:inset-0 md:h-full" />

        <div class="relative max-w-7xl mx-auto px-6 sm:px-12 md:flex md:min-h-[34rem] md:items-center md:justify-end lg:min-h-[38rem]">
            <div class="py-14 md:w-1/2 md:pl-12">
                <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-[0.95] text-helena-oscuro">
                    <x-contenido.titulo clave="autocuidado.hero" clase-destacado="text-helena-rosa-oscuro" />
                </h1>
                <p class="mt-6 max-w-md text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('autocuidado.hero.texto') }}</p>
                <a href="#que-es" class="group mt-8 inline-flex items-center gap-3 bg-helena-rosa-oscuro px-7 py-3 text-xs font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-oscuro">
                    {{ contenido('autocuidado.hero.boton') }}
                    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ¿Qué es el autocuidado? --}}
    <section id="que-es" class="scroll-mt-20 bg-white py-6">
        <div class="grid items-center bg-helena-crema/60 md:grid-cols-[1fr_1.1fr]">
            <div class="px-6 sm:px-12 lg:px-16 py-14">
                <h2 class="font-serif text-4xl sm:text-5xl leading-tight">{{ contenido_html('autocuidado.que_es.titulo') }}</h2>
                <div class="mt-5 h-0.5 w-14 bg-helena-rosa-oscuro"></div>
                <p class="mt-6 max-w-md text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('autocuidado.que_es.texto') }}</p>
            </div>
            <x-foto :src="contenido('autocuidado.que_es.imagen')" alt="Vela, plantas y textiles suaves" class="h-72 w-full md:h-80" />
        </div>
    </section>

    {{-- Cuerpo, mente y espíritu --}}
    <section class="bg-helena-crema/60 pt-10 pb-16">
        <div class="max-w-6xl mx-auto px-6 grid gap-10 sm:grid-cols-3">
            @foreach (range(1, 3) as $n)
                <article class="text-center">
                    <x-foto :src="contenido('autocuidado.pilares.imagen_'.$n)" :alt="contenido('autocuidado.pilares.titulo_'.$n)" class="aspect-[4/3] w-full" />
                    <h3 class="mt-5 font-serif text-4xl">{{ contenido('autocuidado.pilares.titulo_'.$n) }}</h3>
                    <p class="mx-auto mt-3 max-w-[17rem] text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('autocuidado.pilares.texto_'.$n) }}</p>
                    <div class="mx-auto mt-5 h-0.5 w-12 bg-helena-rosa-oscuro"></div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Nuestra filosofía --}}
    <section class="relative overflow-hidden bg-helena-arena/60 py-20">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -left-20 top-0 h-[26rem] w-[26rem] opacity-40'])
        <div class="relative max-w-6xl mx-auto px-6 text-center">
            <p class="text-xs sm:text-sm font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">{{ contenido('autocuidado.filosofia.etiqueta') }}</p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">{{ contenido_html('autocuidado.filosofia.titulo') }}</h2>

            <div class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-helena-oscuro/15">
                @foreach (['hoja', 'diamante', 'ojo', 'loto'] as $indice => $icono)
                    @php($n = $indice + 1)
                    <article class="px-6">
                        <svg class="mx-auto h-11 w-11 text-helena-verde" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @switch($icono)
                                @case('hoja')
                                    <path d="M10 38C10 20 22 10 40 8c0 18-10 30-28 30Z"/><path d="M10 38 30 18M18 30h8M22 26v-8"/>
                                    @break
                                @case('diamante')
                                    <path d="M14 10h20l8 10-18 20L6 20l8-10Z"/><path d="M6 20h36M18 10l-4 10 10 20 10-20-4-10M24 10l-6 10h12l-6-10"/>
                                    @break
                                @case('ojo')
                                    <path d="M4 26s7-11 20-11 20 11 20 11-7 11-20 11S4 26 4 26Z"/><circle cx="24" cy="26" r="5"/><path d="M24 6v4M14 8l2 4M34 8l-2 4M6 12l3 3M42 12l-3 3"/>
                                    @break
                                @default
                                    <path d="M24 36c-5-4-7-10-5-18 1 0 3 2 5 4 2-2 4-4 5-4 2 8 0 14-5 18Z"/><path d="M24 36c-7 0-13-4-15-12 4-1 8 0 10 2M24 36c7 0 13-4 15-12-4-1-8 0-10 2M24 36c-9 2-16 0-20-5 3-2 7-2 10-1M24 36c9 2 16 0 20-5-3-2-7-2-10-1"/>
                            @endswitch
                        </svg>
                        <h3 class="mt-5 font-serif text-2xl leading-tight">{{ contenido_html('autocuidado.filosofia.titulo_'.$n) }}</h3>
                        <p class="mx-auto mt-3 max-w-[14rem] text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('autocuidado.filosofia.texto_'.$n) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nuestro manifiesto --}}
    <section class="relative overflow-hidden bg-helena-rosa-claro">
        <div class="grid items-center md:grid-cols-[0.8fr_1.2fr]">
            <x-foto :src="contenido('autocuidado.manifiesto.imagen')" alt="Flor rosada" class="h-64 w-full md:h-[26rem]" />
            <div class="px-6 sm:px-12 py-16 text-center">
                <p class="flex items-center justify-center gap-3 text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">
                    <span class="h-px w-8 bg-helena-rosa-oscuro/60"></span>{{ contenido('autocuidado.manifiesto.etiqueta') }}<span class="h-px w-8 bg-helena-rosa-oscuro/60"></span>
                </p>
                <h2 class="mt-5 font-serif italic text-4xl sm:text-5xl lg:text-6xl leading-tight">
                    <x-contenido.titulo clave="autocuidado.manifiesto" etiqueta="span" clase-destacado="text-helena-rosa-oscuro" en-linea />
                </h2>
                <p class="mx-auto mt-6 max-w-xl text-sm sm:text-base leading-relaxed text-helena-oscuro/80">{{ contenido_html('autocuidado.manifiesto.texto') }}</p>
                <a href="{{ route('tienda.catalogo') }}" class="group mt-8 inline-flex items-center gap-3 bg-helena-verde px-7 py-3 text-xs font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-verde-oscuro">
                    {{ contenido('autocuidado.manifiesto.boton') }}
                    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-tienda-layout>
