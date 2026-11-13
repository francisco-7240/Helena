<x-tienda-layout titulo="Nosotros">
    {{-- Encabezado --}}
    <section class="bg-helena-rosa">
        <div class="max-w-6xl mx-auto px-6 pt-16 pb-28 text-center">
            <p class="text-xs text-white">{{ contenido('nosotros.encabezado.etiqueta') }}</p>
            <h1 class="mt-5 font-serif italic text-5xl sm:text-6xl lg:text-7xl leading-[1.05] text-helena-oscuro">
                <x-contenido.titulo clave="nosotros.encabezado" etiqueta="span" clase-destacado="text-helena-verde" />
            </h1>
        </div>
    </section>

    {{-- Sobre Helena Baüt --}}
    <section class="relative">
        <div class="absolute inset-x-0 top-0 h-4 bg-helena-rosa"></div>
        <div class="relative max-w-6xl mx-auto -mt-4 bg-white grid items-center gap-10 md:grid-cols-2">
            <div class="px-6 sm:px-12 py-14">
                <p class="text-xs font-medium text-helena-oscuro">{{ contenido('nosotros.historia.etiqueta') }}</p>
                <h2 class="mt-4 font-serif italic text-5xl lg:text-6xl leading-[1.05]">
                    <x-contenido.titulo clave="nosotros.historia" etiqueta="span" clase-destacado="text-helena-verde" />
                </h2>
                <div class="mt-8 max-w-md space-y-4 text-xs leading-relaxed text-helena-oscuro/80">
                    @foreach (contenido_parrafos('nosotros.historia.texto') as $parrafo)
                        <p>{!! nl2br(e($parrafo), false) !!}</p>
                    @endforeach
                </div>
            </div>
            <x-foto :src="contenido('nosotros.historia.imagen')" alt="Fundadora de Helena Baüt junto a sus productos" class="aspect-square w-full md:h-full" />
        </div>
    </section>

    {{-- Nuestra filosofía --}}
    <section class="relative mt-12 overflow-hidden bg-gradient-to-br from-helena-crema via-[#f1e2d4] to-helena-arena">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -right-10 bottom-0 h-[30rem] w-[30rem] opacity-40'])
        <div class="relative max-w-6xl mx-auto px-6 sm:px-12 py-24">
            <p class="text-xs font-medium text-helena-oscuro">{{ contenido('nosotros.filosofia.etiqueta') }}</p>
            <h2 class="mt-6 font-serif italic text-4xl sm:text-5xl lg:text-6xl"><x-contenido.titulo clave="nosotros.filosofia" etiqueta="span" clase-destacado="text-helena-verde" en-linea /></h2>
            <p class="mt-4 max-w-3xl text-xs leading-relaxed text-helena-oscuro">{{ contenido_html('nosotros.filosofia.texto') }}</p>
            <p class="mt-1 text-xs font-semibold text-helena-oscuro">{{ contenido('nosotros.filosofia.firma') }}</p>

            <div class="mt-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (range(1, 4) as $n)
                    <article class="max-w-[13rem]">
                        <div class="h-px w-28 bg-helena-oscuro/30"></div>
                        <p class="mt-6 text-xl font-semibold text-helena-verde">{{ str_pad($n, 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-4 font-serif italic text-2xl leading-tight">{{ contenido_html('nosotros.filosofia.titulo_'.$n) }}</h3>
                        <p class="mt-3 text-[11px] leading-relaxed text-helena-oscuro/70">{{ contenido_html('nosotros.filosofia.texto_'.$n) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Más allá del cabello --}}
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6 sm:px-12 grid items-center gap-12 md:grid-cols-[0.85fr_1fr]">
            <x-foto :src="contenido('nosotros.mas_alla.imagen')" alt="Mujer con cabello rizado y flores" class="aspect-[4/5] w-full" />
            <div>
                <p class="text-xs font-semibold text-helena-oscuro">{{ contenido('nosotros.mas_alla.etiqueta') }}</p>
                <div class="mt-4 max-w-md space-y-4 text-xs leading-relaxed text-helena-oscuro/80">
                    @foreach (contenido_parrafos('nosotros.mas_alla.texto') as $parrafo)
                        <p>{!! nl2br(e($parrafo), false) !!}</p>
                    @endforeach
                </div>
                <p class="mt-10 font-serif text-5xl lg:text-6xl leading-[1.05]">
                    <x-contenido.titulo clave="nosotros.mas_alla" clase-destacado="text-helena-verde" />
                </p>
                <p class="mt-6 text-xs font-semibold text-helena-oscuro">{{ contenido('nosotros.mas_alla.firma') }}</p>
                <x-tienda.boton-flecha :href="route('autocuidado')" class="mt-6">{{ contenido('nosotros.mas_alla.boton') }}</x-tienda.boton-flecha>
            </div>
        </div>
    </section>
</x-tienda-layout>
