@props(['href', 'claro' => false])
<a href="{{ $href }}" {{ $attributes->class([
    'group inline-flex items-center gap-3 rounded-full border px-5 py-2 text-[10px] font-medium uppercase tracking-[0.15em] transition',
    'border-helena-oscuro/60 text-helena-oscuro hover:bg-helena-oscuro hover:text-white' => ! $claro,
    'border-white/80 text-white hover:bg-white hover:text-helena-verde' => $claro,
]) }}>
    {{ $slot }}
    <svg class="h-3 w-5 transition group-hover:translate-x-1" viewBox="0 0 20 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M0 6h18M13 1l5 5-5 5"/></svg>
</a>
