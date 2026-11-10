<x-tienda-layout titulo="Cuidado capilar">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-helena-crema">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -right-16 -top-10 h-[26rem] w-[26rem] opacity-40'])
        <div class="relative grid md:grid-cols-[0.9fr_1.1fr]">
            <x-foto src="storage/recursos/capilar-hero.png" alt="Mujer con cabello largo y saludable" class="h-80 w-full md:h-full md:min-h-[34rem]" />
            <div class="px-6 sm:px-12 pt-14 md:pt-16">
                <h1 class="font-serif text-5xl sm:text-6xl leading-[0.95] text-helena-verde-oscuro">
                    Cuidado capilar<br><em class="text-[#5b3f2e]">consciente.</em>
                </h1>
                <p class="mt-5 max-w-md text-sm leading-relaxed text-helena-oscuro/80">
                    Rituales y productos naturales para un cabello más fuerte, saludable y lleno de vida.
                </p>
                <div class="mt-6 flex flex-col-reverse items-start gap-6 lg:flex-row lg:items-end">
                    <x-foto src="storage/recursos/capilar-productos.png" alt="Productos de cuidado capilar Helena" class="aspect-[16/9] w-full max-w-md object-contain" />
                    <a href="#que-es" class="group inline-flex shrink-0 items-center gap-3 bg-helena-rosa-oscuro px-6 py-3 text-[10px] font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-oscuro lg:mb-auto">
                        Descubre el cuidado capilar
                        <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ¿Qué es el cuidado capilar? --}}
    <section id="que-es" class="scroll-mt-20 bg-helena-crema/60 py-4">
        <div class="grid items-center md:grid-cols-[0.9fr_1.1fr]">
            <div class="px-6 sm:px-12 lg:px-16 py-14">
                <h2 class="font-serif text-4xl sm:text-5xl leading-tight">¿Qué es el<br><em>cuidado capilar?</em></h2>
                <div class="mt-5 h-0.5 w-14 bg-helena-rosa-oscuro"></div>
                <p class="mt-6 max-w-md text-sm leading-relaxed text-helena-oscuro/80">
                    Es un conjunto de hábitos, productos y rituales que limpian, nutren, fortalecen y equilibran el cabello, respetando su naturaleza y sus necesidades únicas.
                </p>
            </div>
            <x-foto src="storage/recursos/capilar-que-es.png" alt="Mujer acomodando su cabello" class="h-72 w-full md:h-96" />
        </div>
    </section>

    {{-- Tu rutina capilar --}}
    <section class="bg-helena-crema/60 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="flex items-center justify-center gap-4 text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">
                <span class="h-px w-16 bg-helena-oscuro/20"></span>Tu rutina capilar<span class="h-px w-16 bg-helena-oscuro/20"></span>
            </p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">Un ritual completo para un cabello saludable.</h2>

            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Limpiar', 'Purifica el cuero cabelludo, elimina impurezas y prepara el cabello.', 'storage/recursos/capilar-limpiar.png'],
                    ['Acondicionar', 'Suaviza, desenreda y recupera la hidratación natural.', 'storage/recursos/capilar-acondicionar.png'],
                    ['Nutrir', 'Aporta nutrientes esenciales que fortalecen y revitalizan desde la raíz.', 'storage/recursos/capilar-nutrir.png'],
                    ['Transformar', 'Mejora visiblemente la textura, el brillo y la fuerza de tu cabello.', 'storage/recursos/capilar-transformar.png'],
                ] as [$paso, $texto, $imagen])
                    <article>
                        <x-foto :src="$imagen" :alt="$paso" class="aspect-[3/2] w-full" />
                        <h3 class="mt-4 font-serif text-3xl">{{ $paso }}</h3>
                        <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-helena-oscuro/80">{{ $texto }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nuestros productos --}}
    <section class="bg-helena-arena/50 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">Nuestros productos</p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">Fórmulas naturales para cada necesidad.</h2>

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
            <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">Nuestra filosofía</p>
            <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl">Cuidado capilar que respeta tu esencia.</h2>

            <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-helena-oscuro/15">
                @foreach ([
                    ['hoja', 'Ingredientes<br>conscientes', 'De origen natural y seleccionados por sus beneficios reales para tu cabello.'],
                    ['gota', 'Hidratación<br>profunda', 'Restaura la humedad, la suavidad y la elasticidad.'],
                    ['brillo', 'Brillo<br>natural', 'Realza la belleza de tu cabello, sin residuos ni peso.'],
                    ['loto', 'Fórmulas<br>suaves', 'Efectivas y respetuosas, ideales para el uso diario y para todo tipo de cabello.'],
                ] as [$icono, $titulo, $texto])
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
                        <h3 class="mt-5 font-serif text-2xl leading-tight">{!! $titulo !!}</h3>
                        <p class="mx-auto mt-3 max-w-[14rem] text-sm leading-relaxed text-helena-oscuro/80">{{ $texto }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nuestro manifiesto --}}
    <section class="relative overflow-hidden bg-helena-rosa-claro">
        <div class="grid items-center md:grid-cols-[0.8fr_1.2fr]">
            <x-foto src="storage/recursos/autocuidado-flor.png" alt="Flor rosada" class="h-64 w-full md:h-[24rem]" />
            <div class="px-6 sm:px-12 py-14 text-center">
                <p class="text-xs font-medium uppercase tracking-[0.35em] text-helena-rosa-oscuro">Nuestro manifiesto</p>
                <h2 class="mt-4 font-serif italic text-4xl sm:text-5xl lg:text-6xl leading-tight text-[#5b3f2e]">
                    Ama tu cabello<br>de forma consciente.
                </h2>
                <p class="mx-auto mt-5 max-w-lg text-sm leading-relaxed text-helena-oscuro/80">
                    Cuídalo, escúchalo y celebra su naturaleza. Creemos en un cuidado capilar real, natural y respetuoso, que te ayude a sentirte bien por dentro y por fuera.
                </p>
                <a href="{{ $categoria ? route('tienda.categoria', $categoria) : route('tienda.catalogo') }}" class="group mt-7 inline-flex items-center gap-3 bg-helena-verde-oscuro px-7 py-3 text-[11px] font-medium uppercase tracking-[0.2em] text-white transition hover:bg-helena-oscuro">
                    Explora nuestros productos
                    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-tienda-layout>
