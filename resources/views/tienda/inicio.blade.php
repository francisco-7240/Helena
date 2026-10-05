<x-tienda-layout>
    <section class="relative overflow-hidden bg-gradient-to-br from-rose-50 via-stone-50 to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-rose-700">Nueva colección</p>
                <h1 class="mt-4 font-serif text-4xl md:text-6xl font-semibold leading-tight text-stone-900">Elegancia para cada día</h1>
                <p class="mt-6 text-lg text-stone-600 max-w-md">Descubre piezas únicas en bolsos, calzado, joyería y accesorios. Calidad y estilo que te acompañan.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('tienda.catalogo') }}" class="inline-flex items-center rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700 transition">Ver la tienda</a>
                    <a href="{{ route('contacto.create') }}" class="inline-flex items-center rounded-full border border-stone-300 px-6 py-3 text-sm font-semibold hover:border-stone-900 transition">Contáctanos</a>
                </div>
            </div>
            <div class="hidden md:grid grid-cols-2 gap-4">
                @foreach ($destacados->take(4) as $producto)
                    <a href="{{ route('tienda.producto', $producto) }}" @class(['block self-start overflow-hidden rounded-2xl shadow-sm', 'mt-8' => $loop->odd])>
                        <x-tienda.imagen :producto="$producto" class="aspect-square w-full" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($categorias->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="font-serif text-3xl font-semibold text-center">Compra por categoría</h2>
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($categorias as $categoria)
                    <a href="{{ route('tienda.categoria', $categoria) }}" class="group relative block aspect-square overflow-hidden rounded-xl bg-stone-200">
                        @if ($categoria->imagen)
                            <img src="{{ Storage::disk('public')->url($categoria->imagen) }}" alt="{{ $categoria->nombre }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-stone-200 to-rose-100"></div>
                        @endif
                        <div class="absolute inset-0 bg-stone-900/30 group-hover:bg-stone-900/40 transition"></div>
                        <span class="absolute inset-0 flex items-center justify-center font-serif text-2xl text-white">{{ $categoria->nombre }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($destacados->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-end justify-between">
                <h2 class="font-serif text-3xl font-semibold">Destacados</h2>
                <a href="{{ route('tienda.catalogo') }}" class="text-sm font-medium text-rose-700 hover:underline">Ver todo →</a>
            </div>
            <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @foreach ($destacados as $producto)
                    <x-tienda.producto-card :producto="$producto" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="font-serif text-3xl font-semibold">Novedades</h2>
        @if ($novedades->isEmpty())
            <p class="mt-6 text-stone-500">Muy pronto tendremos productos disponibles.</p>
        @else
            <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @foreach ($novedades as $producto)
                    <x-tienda.producto-card :producto="$producto" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-white border-y border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 sm:grid-cols-3 text-center">
            <div>
                <p class="font-serif text-xl">Envíos a todo el país</p>
                <p class="mt-2 text-sm text-stone-500">Gratis desde @precio(config('tienda.envio_gratis_desde'))</p>
            </div>
            <div>
                <p class="font-serif text-xl">Compra segura</p>
                <p class="mt-2 text-sm text-stone-500">Transferencia o pago contra entrega</p>
            </div>
            <div>
                <p class="font-serif text-xl">Atención personalizada</p>
                <p class="mt-2 text-sm text-stone-500">Te ayudamos a elegir</p>
            </div>
        </div>
    </section>
</x-tienda-layout>
