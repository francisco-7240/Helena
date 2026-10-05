@props(['pedido'])
<div class="rounded-xl bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-semibold text-lg">Pedido {{ $pedido->codigo }}</h2>
        <x-tienda.estado-pedido :estado="$pedido->estado" />
    </div>
    <p class="mt-1 text-sm text-stone-500">{{ $pedido->created_at->format('d/m/Y H:i') }} · {{ $pedido->metodoPagoTexto() }}</p>

    <table class="mt-6 w-full text-sm">
        <thead class="text-left text-stone-500">
            <tr>
                <th class="pb-2 font-medium">Producto</th>
                <th class="pb-2 font-medium text-right">Precio</th>
                <th class="pb-2 font-medium text-right">Cant.</th>
                <th class="pb-2 font-medium text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach ($pedido->items as $item)
                <tr>
                    <td class="py-2">{{ $item->nombre_producto }} @if ($item->nombre_color)<span class="text-stone-500">({{ $item->nombre_color }})</span>@endif</td>
                    <td class="py-2 text-right">@precio($item->precio)</td>
                    <td class="py-2 text-right">{{ $item->cantidad }}</td>
                    <td class="py-2 text-right">@precio($item->subtotal)</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 ml-auto max-w-xs">
        <x-tienda.resumen :subtotal="$pedido->subtotal" :envio="$pedido->envio" :total="$pedido->total" />
    </div>

    <div class="mt-6 border-t border-stone-200 pt-4 text-sm">
        <p class="font-semibold">Envío a</p>
        <p class="mt-1 text-stone-600">
            {{ $pedido->nombre }}<br>
            {{ $pedido->direccion }}, {{ $pedido->ciudad }} {{ $pedido->codigo_postal }}<br>
            {{ $pedido->telefono }} · {{ $pedido->email }}
        </p>
        @if ($pedido->notas)
            <p class="mt-3 text-stone-600"><span class="font-semibold text-stone-800">Notas:</span> {{ $pedido->notas }}</p>
        @endif
    </div>
</div>
