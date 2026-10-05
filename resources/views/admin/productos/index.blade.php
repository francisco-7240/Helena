<x-admin-layout titulo="Productos">
    <x-slot name="acciones"><x-admin.boton :href="route('admin.productos.create')">Nuevo producto</x-admin.boton></x-slot>

    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o SKU" class="rounded-md border-gray-300 text-sm">
        <select name="categoria" class="rounded-md border-gray-300 text-sm">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(request('categoria') == $categoria->id)>{{ $categoria->nombre }}</option>
            @endforeach
        </select>
        <button class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm">Filtrar</button>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Producto</th>
                    <th class="px-4 py-3 font-medium">Categoría</th>
                    <th class="px-4 py-3 font-medium">Precio</th>
                    <th class="px-4 py-3 font-medium">Stock</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($productos as $producto)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <x-tienda.imagen :producto="$producto" class="h-12 w-12 rounded" />
                                <div>
                                    <p class="font-medium">{{ $producto->nombre }} @if ($producto->destacado)<span class="text-amber-500" title="Destacado">★</span>@endif</p>
                                    <p class="text-xs text-stone-500">{{ $producto->sku ?? 'Sin SKU' }} · {{ $producto->marca?->nombre ?? 'Sin marca' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $producto->categoria->nombre }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @precio($producto->precioFinal())
                            @if ($producto->enOferta())<span class="block text-xs text-stone-400 line-through">@precio($producto->precio)</span>@endif
                        </td>
                        <td @class(['px-4 py-3', 'text-rose-700 font-semibold' => $producto->stockTotal() === 0])>{{ $producto->stockTotal() }}</td>
                        <td class="px-4 py-3"><x-admin.estado :activo="$producto->estado" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('tienda.producto', $producto) }}" class="text-stone-500 hover:underline" target="_blank">Ver</a>
                            <a href="{{ route('admin.productos.edit', $producto) }}" class="text-stone-700 hover:underline">Editar</a>
                            <x-admin.eliminar :action="route('admin.productos.destroy', $producto)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-stone-500">No hay productos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $productos->links() }}</div>
</x-admin-layout>
