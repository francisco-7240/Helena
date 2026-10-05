<x-admin-layout :titulo="'Pedido '.$pedido->codigo">
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <x-tienda.detalle-pedido :pedido="$pedido" />
        </div>
        <aside class="space-y-6">
            <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}" class="rounded-xl bg-white p-6 shadow-sm space-y-4">
                @csrf
                @method('PATCH')
                <x-input-label for="estado" value="Estado del pedido" />
                <select id="estado" name="estado" class="block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach (\App\Models\Pedido::ESTADOS as $valor => $texto)
                        <option value="{{ $valor }}" @selected($pedido->estado === $valor)>{{ $texto }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-stone-500">Al cancelar un pedido el stock se devuelve al inventario.</p>
                <x-admin.boton>Actualizar estado</x-admin.boton>
            </form>

            <div class="rounded-xl bg-white p-6 shadow-sm text-sm">
                <p class="font-semibold">Cliente</p>
                @if ($pedido->user)
                    <p class="mt-1">Cuenta registrada: {{ $pedido->user->name }} ({{ $pedido->user->email }})</p>
                @else
                    <p class="mt-1 text-stone-500">Compra como invitado</p>
                @endif
            </div>
            <a href="{{ route('admin.pedidos.index') }}" class="inline-block text-sm text-stone-600 hover:underline">← Volver a pedidos</a>
        </aside>
    </div>
</x-admin-layout>
