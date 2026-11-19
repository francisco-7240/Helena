{{--
    Carrusel de banners a pantalla completa de la portada. Recibe $imagenes (colección de CarruselImagen).
    Cada banner ocupa todo el ancho y el alto visible bajo el menú; en el celular usa la imagen
    vertical si la hay. Cambia solo cada 6 segundos, se pausa al pasar el mouse y se puede deslizar.
--}}
<section x-data="{
        actual: 0,
        total: {{ $imagenes->count() }},
        temporizador: null,
        inicioToque: null,
        iniciar() {
            if (this.total < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
            this.detener();
            this.temporizador = setInterval(() => this.siguiente(), 6000);
        },
        detener() { clearInterval(this.temporizador); },
        siguiente() { this.actual = (this.actual + 1) % this.total; },
        anterior() { this.actual = (this.actual - 1 + this.total) % this.total; },
        ir(indice) { this.actual = indice; this.iniciar(); },
        soltar(evento) {
            if (this.inicioToque === null) { return; }
            const distancia = evento.changedTouches[0].clientX - this.inicioToque;
            if (Math.abs(distancia) > 40) { distancia < 0 ? this.siguiente() : this.anterior(); this.iniciar(); }
            this.inicioToque = null;
        },
     }"
     x-init="iniciar()"
     @mouseenter="detener()" @mouseleave="iniciar()"
     @touchstart.passive="inicioToque = $event.touches[0].clientX" @touchend="soltar($event)"
     @keydown.arrow-left="anterior(); iniciar()" @keydown.arrow-right="siguiente(); iniciar()"
     class="group relative h-[calc(100svh-5rem-1px)] min-h-[24rem] w-full overflow-hidden bg-helena-arena"
     role="region" aria-roledescription="carrusel" aria-label="Banners destacados">

    @foreach ($imagenes as $indice => $imagen)
        <div x-show="actual === {{ $indice }}"
             x-transition:enter="transition duration-700 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-700 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @class(['absolute inset-0', 'hidden' => ! $loop->first]) :class="{ 'hidden': false }"
             role="group" aria-roledescription="banner" aria-label="{{ $indice + 1 }} de {{ $imagenes->count() }}"
             :aria-hidden="actual !== {{ $indice }}">
            @if ($imagen->enlace)
                <a href="{{ $imagen->enlace }}" class="block h-full w-full" @if (! str_starts_with($imagen->enlace, '/')) target="_blank" rel="noopener" @endif>
            @endif
                <picture>
                    @if ($imagen->imagen_movil)
                        <source media="(max-width: 767px)" srcset="{{ $imagen->urlMovil() }}">
                    @endif
                    <img src="{{ $imagen->url() }}" alt="{{ $imagen->descripcion }}"
                         @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                         class="h-full w-full object-cover">
                </picture>
            @if ($imagen->enlace)
                </a>
            @endif
        </div>
    @endforeach

    @if ($imagenes->count() > 1)
        <button type="button" @click="anterior(); iniciar()" aria-label="Banner anterior"
                class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/70 p-3 text-helena-oscuro shadow-sm transition hover:bg-white md:opacity-0 md:focus:opacity-100 md:group-hover:opacity-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="siguiente(); iniciar()" aria-label="Banner siguiente"
                class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/70 p-3 text-helena-oscuro shadow-sm transition hover:bg-white md:opacity-0 md:focus:opacity-100 md:group-hover:opacity-100">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="absolute inset-x-0 bottom-6 flex justify-center gap-2">
            @foreach ($imagenes as $indice => $imagen)
                <button type="button" @click="ir({{ $indice }})" aria-label="Ver banner {{ $indice + 1 }}"
                        class="h-2.5 rounded-full shadow transition-all"
                        :class="actual === {{ $indice }} ? 'w-8 bg-white' : 'w-2.5 bg-white/60 hover:bg-white/80'"></button>
            @endforeach
        </div>
    @endif
</section>
