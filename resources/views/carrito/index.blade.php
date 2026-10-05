<x-tienda-layout titulo="Carrito">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="font-serif text-4xl font-semibold">Tu carrito</h1>

        @if ($lineas->isEmpty())
            <div class="mt-10 rounded-lg border border-dashed border-stone-300 p-12 text-center">
                <p class="text-stone-500">Tu carrito está vacío.</p>
                <a href="{{ route('tienda.catalogo') }}" class="mt-6 inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700">Ir a la tienda</a>
            </div>
        @else
            <div class="mt-8 grid gap-10 lg:grid-cols-[1fr_360px]">
                <ul class="divide-y divide-stone-200 border-y border-stone-200">
                    @foreach ($lineas as $linea)
                        <li class="flex gap-4 py-6">
                            <a href="{{ route('tienda.producto', $linea->producto) }}" class="shrink-0">
                                <x-tienda.imagen :producto="$linea->producto" class="h-24 w-24 rounded-md" />
                            </a>
                            <div class="flex flex-1 flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <a href="{{ route('tienda.producto', $linea->producto) }}" class="font-medium hover:text-rose-700">{{ $linea->producto->nombre }}</a>
                                    @if ($linea->variante->color)
                                        <p class="text-sm text-stone-500">Color: {{ $linea->variante->color->nombre }}</p>
                                    @endif
                                    <p class="text-sm text-stone-500">@precio($linea->precio) c/u</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('carrito.actualizar', $linea->variante->id) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="cantidad-{{ $linea->variante->id }}" class="sr-only">Cantidad</label>
                                        <input id="cantidad-{{ $linea->variante->id }}" type="number" name="cantidad" value="{{ $linea->cantidad }}" min="0" max="{{ $linea->variante->stock }}" class="w-20 rounded-md border-stone-300 text-sm" onchange="this.form.submit()">
                                    </form>
                                    <p class="w-24 text-right font-semibold">@precio($linea->subtotal)</p>
                                    <form method="POST" action="{{ route('carrito.eliminar', $linea->variante->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-stone-400 hover:text-rose-700" aria-label="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <aside class="rounded-xl bg-white p-6 shadow-sm h-fit">
                    <h2 class="font-semibold text-lg">Resumen</h2>
                    <div class="mt-4">
                        <x-tienda.resumen :subtotal="$subtotal" :envio="$envio" :total="$total" />
                    </div>
                    @if ($envio > 0)
                        <p class="mt-3 text-xs text-stone-500">Agrega @precio(config('tienda.envio_gratis_desde') - $subtotal) más y obtén envío gratis.</p>
                    @endif
                    <a href="{{ route('checkout.create') }}" class="mt-6 block rounded-full bg-stone-900 px-6 py-3 text-center text-sm font-semibold text-white hover:bg-rose-700">Finalizar compra</a>
                    <form method="POST" action="{{ route('carrito.vaciar') }}" class="mt-3 text-center">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-stone-500 hover:text-rose-700">Vaciar carrito</button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</x-tienda-layout>
