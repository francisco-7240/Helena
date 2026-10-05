<x-admin-layout titulo="Categorías">
    <x-slot name="acciones"><x-admin.boton :href="route('admin.categorias.create')">Nueva categoría</x-admin.boton></x-slot>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Nombre</th>
                    <th class="px-4 py-3 font-medium">Categoría padre</th>
                    <th class="px-4 py-3 font-medium">Productos</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($categorias as $categoria)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $categoria->nombre }}</td>
                        <td class="px-4 py-3">{{ $categoria->categoriaPadre?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $categoria->productos_count }}</td>
                        <td class="px-4 py-3"><x-admin.estado :activo="$categoria->estado" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.categorias.edit', $categoria) }}" class="text-stone-700 hover:underline">Editar</a>
                            <x-admin.eliminar :action="route('admin.categorias.destroy', $categoria)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-stone-500">No hay categorías.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $categorias->links() }}</div>
</x-admin-layout>
