@props(['producto'])
<a href="{{ route('tienda.producto', $producto) }}" class="group block">
    <div class="relative aspect-[4/5] overflow-hidden rounded-lg bg-stone-100">
        <x-tienda.imagen :producto="$producto" class="h-full w-full transition duration-300 group-hover:scale-105" />
        @if ($producto->enOferta())
            <span class="absolute top-3 left-3 rounded-full bg-helena-verde px-2.5 py-1 text-xs font-semibold text-white">Oferta</span>
        @endif
    </div>
    <div class="mt-3">
        @if ($producto->relationLoaded('categoria') && $producto->categoria)
            <p class="text-xs uppercase tracking-wider text-stone-500">{{ $producto->categoria->nombre }}</p>
        @endif
        <h3 class="mt-1 font-medium text-stone-900 group-hover:text-helena-verde">{{ $producto->nombre }}</h3>
        <p class="mt-1 text-sm">
            @if ($producto->enOferta())
                <span class="font-semibold text-helena-verde">@precio($producto->precio_oferta)</span>
                <span class="ml-1 text-stone-400 line-through">@precio($producto->precio)</span>
            @else
                <span class="font-semibold">@precio($producto->precio)</span>
            @endif
        </p>
    </div>
</a>
