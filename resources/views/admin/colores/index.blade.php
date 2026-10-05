<x-admin-layout titulo="Colores">
    <x-slot name="acciones"><x-admin.boton :href="route('admin.colores.create')">Nuevo color</x-admin.boton></x-slot>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Color</th>
                    <th class="px-4 py-3 font-medium">Código</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($colores as $color)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2 font-medium">
                                <span class="h-5 w-5 rounded-full border border-stone-300" style="background-color: {{ $color->codigo_hex }}"></span>
                                {{ $color->nombre }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono">{{ $color->codigo_hex }}</td>
                        <td class="px-4 py-3"><x-admin.estado :activo="$color->estado" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.colores.edit', $color) }}" class="text-stone-700 hover:underline">Editar</a>
                            <x-admin.eliminar :action="route('admin.colores.destroy', $color)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-stone-500">No hay colores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $colores->links() }}</div>
</x-admin-layout>
