<x-admin-layout titulo="Marcas">
    <x-slot name="acciones"><x-admin.boton :href="route('admin.marcas.create')">Nueva marca</x-admin.boton></x-slot>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Nombre</th>
                    <th class="px-4 py-3 font-medium">Productos</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($marcas as $marca)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $marca->nombre }}</td>
                        <td class="px-4 py-3">{{ $marca->productos_count }}</td>
                        <td class="px-4 py-3"><x-admin.estado :activo="$marca->estado" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.marcas.edit', $marca) }}" class="text-stone-700 hover:underline">Editar</a>
                            <x-admin.eliminar :action="route('admin.marcas.destroy', $marca)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-stone-500">No hay marcas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $marcas->links() }}</div>
</x-admin-layout>
