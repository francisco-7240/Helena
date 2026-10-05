<x-tienda-layout titulo="Mis pedidos">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="font-serif text-4xl font-semibold">Mis pedidos</h1>

        @if ($pedidos->isEmpty())
            <div class="mt-10 rounded-lg border border-dashed border-stone-300 p-12 text-center">
                <p class="text-stone-500">Aún no has realizado pedidos.</p>
                <a href="{{ route('tienda.catalogo') }}" class="mt-6 inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700">Ir a la tienda</a>
            </div>
        @else
            <div class="mt-8 overflow-x-auto rounded-xl bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Código</th>
                            <th class="px-4 py-3 font-medium">Fecha</th>
                            <th class="px-4 py-3 font-medium">Productos</th>
                            <th class="px-4 py-3 font-medium">Total</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($pedidos as $pedido)
                            <tr class="hover:bg-stone-50">
                                <td class="px-4 py-3"><a href="{{ route('pedidos.show', $pedido) }}" class="font-medium text-rose-700 hover:underline">{{ $pedido->codigo }}</a></td>
                                <td class="px-4 py-3">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $pedido->items_count }}</td>
                                <td class="px-4 py-3">@precio($pedido->total)</td>
                                <td class="px-4 py-3"><x-tienda.estado-pedido :estado="$pedido->estado" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $pedidos->links() }}</div>
        @endif
    </div>
</x-tienda-layout>
