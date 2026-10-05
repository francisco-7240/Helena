@props(['subtotal', 'envio', 'total'])
<dl class="space-y-3 text-sm">
    <div class="flex justify-between">
        <dt class="text-stone-600">Subtotal</dt>
        <dd>@precio($subtotal)</dd>
    </div>
    <div class="flex justify-between">
        <dt class="text-stone-600">Envío</dt>
        <dd>{{ $envio > 0 ? \App\Support\Precio::formato($envio) : 'Gratis' }}</dd>
    </div>
    <div class="flex justify-between border-t border-stone-200 pt-3 text-base font-semibold">
        <dt>Total</dt>
        <dd>@precio($total)</dd>
    </div>
</dl>
