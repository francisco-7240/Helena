<x-admin-layout titulo="Pedidos">
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Código, nombre o email" class="rounded-md border-gray-300 text-sm">
        <select name="estado" class="rounded-md border-gray-300 text-sm">
            <option value="">Todos los estados</option>
            @foreach (\App\Models\Pedido::ESTADOS as $valor => $texto)
                <option value="{{ $valor }}" @selected(request('estado') === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        <button class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm">Filtrar</button>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Código</th>
                    <th class="px-4 py-3 font-medium">Cliente</th>
                    <th class="px-4 py-3 font-medium">Fecha</th>
                    <th class="px-4 py-3 font-medium">Productos</th>
                    <th class="px-4 py-3 font-medium">Total</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($pedidos as $pedido)
                    <tr class="hover:bg-stone-50">
                        <td class="px-4 py-3"><a href="{{ route('admin.pedidos.show', $pedido) }}" class="font-medium text-rose-700 hover:underline">{{ $pedido->codigo }}</a></td>
                        <td class="px-4 py-3">{{ $pedido->nombre }}<span class="block text-xs text-stone-500">{{ $pedido->email }}</span></td>
                        <td class="px-4 py-3">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $pedido->items_count }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">@precio($pedido->total)</td>
                        <td class="px-4 py-3"><x-tienda.estado-pedido :estado="$pedido->estado" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-stone-500">No hay pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $pedidos->links() }}</div>
</x-admin-layout>
