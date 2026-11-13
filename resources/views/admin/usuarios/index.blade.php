<x-admin-layout titulo="Usuarios">
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o correo" class="rounded-md border-gray-300 text-sm">
        <button class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm">Buscar</button>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Nombre</th>
                    <th class="px-4 py-3 font-medium">Correo</th>
                    <th class="px-4 py-3 font-medium">Registro</th>
                    <th class="px-4 py-3 font-medium">Pedidos</th>
                    <th class="px-4 py-3 font-medium">Rol</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($usuarios as $usuario)
                    @php($esAdmin = $usuario->hasRole('admin'))
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $usuario->name }}</td>
                        <td class="px-4 py-3">{{ $usuario->email }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ $usuario->created_at?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $usuario->pedidos_count }}</td>
                        <td class="px-4 py-3">
                            <span @class(['inline-flex rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-helena-verde/15 text-helena-verde-oscuro' => $esAdmin, 'bg-stone-100 text-stone-600' => ! $esAdmin])>
                                {{ $esAdmin ? 'Administrador' : 'Cliente' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @unless ($usuario->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}"
                                      onsubmit="return confirm(@js($esAdmin ? '¿Quitar el rol de administrador a '.$usuario->name.'?' : '¿Dar acceso al panel de administración a '.$usuario->name.'?'))">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="admin" value="{{ $esAdmin ? 0 : 1 }}">
                                    <button class="text-sm text-stone-700 hover:underline">{{ $esAdmin ? 'Quitar administrador' : 'Hacer administrador' }}</button>
                                </form>
                            @else
                                <span class="text-xs text-stone-400">Tú</span>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-stone-500">No hay usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $usuarios->links() }}</div>
</x-admin-layout>
