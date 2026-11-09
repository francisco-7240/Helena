<x-tienda-layout titulo="Nosotros">
    {{-- Encabezado --}}
    <section class="bg-helena-rosa">
        <div class="max-w-6xl mx-auto px-6 pt-16 pb-28 text-center">
            <p class="text-xs text-white">La historia Helena</p>
            <h1 class="mt-5 font-serif italic text-5xl sm:text-6xl lg:text-7xl leading-[1.05] text-helena-oscuro">
                Nacimos para<br><span class="text-helena-verde">volver a ti.</span>
            </h1>
        </div>
    </section>

    {{-- Sobre Helena Baüt --}}
    <section class="relative">
        <div class="absolute inset-x-0 top-0 h-4 bg-helena-rosa"></div>
        <div class="relative max-w-6xl mx-auto -mt-4 bg-white grid items-center gap-10 md:grid-cols-2">
            <div class="px-6 sm:px-12 py-14">
                <p class="text-xs font-medium text-helena-oscuro">Sobre Helena Baüt</p>
                <h2 class="mt-4 font-serif italic text-5xl lg:text-6xl leading-[1.05]">
                    El cuidado<br><span class="text-helena-verde">consciente</span>
                </h2>
                <div class="mt-8 max-w-md space-y-4 text-xs leading-relaxed text-helena-oscuro/80">
                    <p>Helena Baüt nació de una necesidad personal: encontrar productos de cuidado elaborados con buenos ingredientes y con la menor cantidad posible de componentes sintéticos.</p>
                    <p>Comenzamos con jabones y shampoo en barra. Con el tiempo, escuchamos a nuestras clientas y fuimos construyendo nuestra línea de cuidado capilar.</p>
                    <p>Hoy seguimos haciendo lo mismo: crear productos de buena calidad y elegir cuidadosamente aquello que ponemos en ellos.</p>
                </div>
            </div>
            <x-foto src="storage/recursos/nosotros-historia.png" alt="Fundadora de Helena Baüt junto a sus productos" class="aspect-square w-full md:h-full" />
        </div>
    </section>

    {{-- Nuestra filosofía --}}
    <section class="relative mt-12 overflow-hidden bg-gradient-to-br from-helena-crema via-[#f1e2d4] to-helena-arena">
        @include('partials.hojas', ['clase' => 'pointer-events-none absolute -right-10 bottom-0 h-[30rem] w-[30rem] opacity-40'])
        <div class="relative max-w-6xl mx-auto px-6 sm:px-12 py-24">
            <p class="text-xs font-medium text-helena-oscuro">Nuestra filosofía</p>
            <h2 class="mt-6 font-serif italic text-4xl sm:text-5xl lg:text-6xl">Cuidarte es una forma de <span class="text-helena-verde">amarte.</span></h2>
            <p class="mt-4 max-w-3xl text-xs leading-relaxed text-helena-oscuro">
                Para nosotras, el autocuidado empieza cuando decides escucharte, reconocer lo que necesitas y elegir conscientemente cómo cuidarte.
            </p>
            <p class="mt-1 text-xs font-semibold text-helena-oscuro">Me cuido. Me amo.</p>

            <div class="mt-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Ingredientes conscientes', 'Priorizamos ingredientes de origen natural y vegetal y procuramos reducir al máximo el uso de componentes sintéticos.'],
                    ['Calidad sin atajos', 'Buscamos desarrollar productos efectivos, agradables de usar y elaborados con cuidado.'],
                    ['Transparencia', 'Preferimos hablar con honestidad sobre lo que nuestros productos son y lo que pueden ofrecer.'],
                    ['Coherencia', 'Nuestros productos deben reflejar los principios en los que creemos.'],
                ] as $indice => [$titulo, $texto])
                    <article class="max-w-[13rem]">
                        <div class="h-px w-28 bg-helena-oscuro/30"></div>
                        <p class="mt-6 text-xl font-semibold text-helena-verde">{{ str_pad($indice + 1, 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-4 font-serif italic text-2xl leading-tight">{{ $titulo }}</h3>
                        <p class="mt-3 text-[11px] leading-relaxed text-helena-oscuro/70">{{ $texto }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Más allá del cabello --}}
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6 sm:px-12 grid items-center gap-12 md:grid-cols-[0.85fr_1fr]">
            <x-foto src="storage/recursos/nosotros-mas-alla.png" alt="Mujer con cabello rizado y flores" class="aspect-[4/5] w-full" />
            <div>
                <p class="text-xs font-semibold text-helena-oscuro">Más allá del cabello</p>
                <div class="mt-4 max-w-md space-y-4 text-xs leading-relaxed text-helena-oscuro/80">
                    <p>Hoy Helena Baüt es una marca de cuidado capilar. Pero nuestra visión es más amplia.</p>
                    <p>Queremos construir un espacio donde podamos hablar de cuerpo, mente y espíritu, y explorar juntas distintas formas de autocuidado.</p>
                </div>
                <p class="mt-10 font-serif text-5xl lg:text-6xl leading-[1.05]">
                    Cuidarnos.<br>
                    <span class="text-helena-verde">Escucharnos.</span><br>
                    <em class="text-helena-verde">Volver a nosotras.</em>
                </p>
                <p class="mt-6 text-xs font-semibold text-helena-oscuro">Helena Baüt · Me cuido. Me amo.</p>
                <x-tienda.boton-flecha :href="route('inicio').'#autocuidado'" class="mt-6">Conoce el autocuidado</x-tienda.boton-flecha>
            </div>
        </div>
    </section>
</x-tienda-layout>
