@props(['activo'])
<span @class(['inline-flex rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-emerald-100 text-emerald-800' => $activo, 'bg-stone-200 text-stone-600' => ! $activo])>{{ $activo ? 'Activo' : 'Inactivo' }}</span>
