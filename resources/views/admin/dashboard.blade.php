<x-admin-layout titulo="Resumen">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Ventas confirmadas', \App\Support\Precio::formato($ventasTotales), route('admin.pedidos.index')],
            ['Pedidos pendientes', $pedidosPendientes, route('admin.pedidos.index', ['estado' => 'pendiente'])],
            ['Productos activos', $productosActivos, route('admin.productos.index')],
            ['Mensajes sin responder', $mensajesPendientes, route('admin.contactos.index')],
        ] as [$etiqueta, $valor, $enlace])
            <a href="{{ $enlace }}" class="rounded-xl bg-white p-5 shadow-sm hover:ring-1 hover:ring-stone-300">
                <p class="text-sm text-stone-500">{{ $etiqueta }}</p>
                <p class="mt-2 text-2xl font-semibold">{{ $valor }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-3">
        <section class="xl:col-span-2 rounded-xl bg-white shadow-sm">
            <h2 class="px-5 py-4 font-semibold border-b border-stone-100">Últimos pedidos</h2>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-stone-100">
                    @forelse ($ultimosPedidos as $pedido)
                        <tr>
                            <td class="px-5 py-3"><a href="{{ route('admin.pedidos.show', $pedido) }}" class="font-medium text-rose-700 hover:underline">{{ $pedido->codigo }}</a></td>
                            <td class="px-5 py-3">{{ $pedido->nombre }}</td>
                            <td class="px-5 py-3 text-stone-500">{{ $pedido->created_at->diffForHumans() }}</td>
                            <td class="px-5 py-3 text-right">@precio($pedido->total)</td>
                            <td class="px-5 py-3 text-right"><x-tienda.estado-pedido :estado="$pedido->estado" /></td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-stone-500">Todavía no hay pedidos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="rounded-xl bg-white shadow-sm">
            <h2 class="px-5 py-4 font-semibold border-b border-stone-100">Poco stock</h2>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($pocoStock as $variante)
                    <li class="flex items-center justify-between px-5 py-3">
                        <a href="{{ route('admin.productos.edit', $variante->producto) }}" class="hover:text-rose-700">
                            {{ $variante->producto->nombre }}
                            @if ($variante->color)<span class="text-stone-500">· {{ $variante->color->nombre }}</span>@endif
                        </a>
                        <span @class(['font-semibold', 'text-rose-700' => $variante->stock === 0])>{{ $variante->stock }}</span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-stone-500">Todo el inventario está en orden.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-admin-layout>
