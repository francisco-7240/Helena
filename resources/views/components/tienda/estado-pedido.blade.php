@props(['estado'])
@php
    $clases = match ($estado) {
        'pagado' => 'bg-sky-100 text-sky-800',
        'enviado' => 'bg-indigo-100 text-indigo-800',
        'entregado' => 'bg-emerald-100 text-emerald-800',
        'cancelado' => 'bg-stone-200 text-stone-600',
        default => 'bg-amber-100 text-amber-800',
    };
@endphp
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clases }}">{{ \App\Models\Pedido::ESTADOS[$estado] ?? $estado }}</span>
