<x-tienda-layout titulo="Pedido confirmado">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            </div>
            <h1 class="mt-4 font-serif text-4xl font-semibold">¡Gracias por tu compra!</h1>
            <p class="mt-3 text-stone-600">Tu pedido <strong>{{ $pedido->codigo }}</strong> fue registrado. Te contactaremos a {{ $pedido->email }} para coordinar el pago y el envío.</p>
        </div>

        @if ($pedido->metodo_pago === 'transferencia')
            <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                Para completar tu compra realiza una transferencia por <strong>@precio($pedido->total)</strong> indicando el código <strong>{{ $pedido->codigo }}</strong>. Te enviaremos los datos bancarios por correo.
            </div>
        @endif

        <div class="mt-8">
            <x-tienda.detalle-pedido :pedido="$pedido" />
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('tienda.catalogo') }}" class="inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-700">Seguir comprando</a>
        </div>
    </div>
</x-tienda-layout>
