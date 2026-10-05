<x-tienda-layout :titulo="'Pedido '.$pedido->codigo">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('pedidos.index') }}" class="text-sm text-stone-500 hover:text-helena-verde">← Mis pedidos</a>
        <div class="mt-4">
            <x-tienda.detalle-pedido :pedido="$pedido" />
        </div>
    </div>
</x-tienda-layout>
