{{--
    Carrusel de imágenes de la portada. Recibe $imagenes (colección de CarruselImagen).
    Cambia solo cada 5 segundos, se pausa al pasar el mouse y se puede deslizar en el celular.
--}}
<div x-data="{
        actual: 0,
        total: {{ $imagenes->count() }},
        temporizador: null,
        inicioToque: null,
        iniciar() {
            if (this.total < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
            this.detener();
            this.temporizador = setInterval(() => this.siguiente(), 5000);
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
     class="group relative w-full max-w-xl"
     role="region" aria-roledescription="carrusel" aria-label="Imágenes destacadas">

    <div class="relative aspect-[4/3] w-full">
        @foreach ($imagenes as $indice => $imagen)
            <img src="{{ $imagen->url() }}" alt="{{ $imagen->descripcion }}"
                 @if (! $loop->first) loading="lazy" @endif
                 x-show="actual === {{ $indice }}"
                 x-transition:enter="transition duration-700 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition duration-500 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @class(['absolute inset-0 h-full w-full object-contain', 'hidden' => ! $loop->first])
                 :class="{ 'hidden': false }"
                 :aria-hidden="actual !== {{ $indice }}">
        @endforeach
    </div>

    @if ($imagenes->count() > 1)
        <button type="button" @click="anterior(); iniciar()" aria-label="Imagen anterior"
                class="absolute left-0 top-1/2 -translate-y-1/2 rounded-full bg-white/70 p-2 text-helena-oscuro opacity-0 shadow-sm transition hover:bg-white focus:opacity-100 group-hover:opacity-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="siguiente(); iniciar()" aria-label="Imagen siguiente"
                class="absolute right-0 top-1/2 -translate-y-1/2 rounded-full bg-white/70 p-2 text-helena-oscuro opacity-0 shadow-sm transition hover:bg-white focus:opacity-100 group-hover:opacity-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="mt-4 flex justify-center gap-2">
            @foreach ($imagenes as $indice => $imagen)
                <button type="button" @click="ir({{ $indice }})" aria-label="Ver imagen {{ $indice + 1 }}"
                        class="h-2 rounded-full transition-all"
                        :class="actual === {{ $indice }} ? 'w-6 bg-helena-verde' : 'w-2 bg-helena-oscuro/25 hover:bg-helena-oscuro/40'"></button>
            @endforeach
        </div>
    @endif
</div>
