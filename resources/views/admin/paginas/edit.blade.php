<x-admin-layout :titulo="'Editar: '.$pagina['titulo']">
    <x-slot name="acciones">
        @if ($pagina['ruta'])
            <a href="{{ route($pagina['ruta']) }}" target="_blank" class="text-sm text-stone-600 hover:underline">Ver página ↗</a>
        @endif
    </x-slot>

    <form method="POST" action="{{ route('admin.paginas.update', $clavePagina) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <p class="max-w-2xl text-sm text-stone-600">
            Si dejas un campo de texto vacío, se vuelve a mostrar el texto original. En los títulos, cada salto de línea se respeta.
        </p>

        @foreach ($pagina['secciones'] as $claveSeccion => $seccion)
            <section x-data="{ abierta: {{ $loop->first ? 'true' : 'false' }} }" class="rounded-xl bg-white shadow-sm">
                <button type="button" @click="abierta = !abierta" class="flex w-full items-center justify-between px-6 py-4 text-left">
                    <h2 class="font-semibold">{{ $seccion['titulo'] }}</h2>
                    <svg class="h-5 w-5 text-stone-400 transition" :class="abierta && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="abierta" x-cloak class="grid gap-5 border-t border-stone-100 px-6 py-6 md:grid-cols-2">
                    @foreach ($seccion['campos'] as $claveCampo => $campo)
                        @php
                            $clave = "{$clavePagina}.{$claveSeccion}.{$claveCampo}";
                            $nombre = str_replace('.', '__', $clave);
                            $valor = $contenidos->get($clave) ?? (isset($campo['config']) ? config($campo['config']) : null);
                            $original = $campo['defecto'] ?? null;
                        @endphp

                        <div @class(['md:col-span-2' => in_array($campo['tipo'], ['parrafo', 'imagen'])])>
                            <x-input-label :for="$nombre" :value="$campo['etiqueta']" />

                            @switch($campo['tipo'])
                                @case('imagen')
                                    <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-start">
                                        <x-foto :src="(string) $valor" :alt="$campo['etiqueta']" class="h-32 w-48 shrink-0 rounded border border-stone-200 object-cover" />
                                        <div class="space-y-2 text-sm">
                                            <p class="break-all text-xs text-stone-500">{{ $valor }}</p>
                                            @unless (file_exists(public_path((string) $valor)))
                                                <p class="text-xs text-amber-700">El archivo aún no existe: sube una imagen o cópiala en esa ruta.</p>
                                            @endunless
                                            <input id="{{ $nombre }}" type="file" name="{{ $nombre }}" accept="image/*" class="block text-sm">
                                            @if ($contenidos->personalizado($clave))
                                                <label class="flex items-center gap-2 text-xs text-stone-600">
                                                    <input type="checkbox" name="{{ $nombre }}_restaurar" value="1" class="rounded border-gray-300">
                                                    Volver a la imagen original
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                    @break

                                @case('parrafo')
                                    <textarea id="{{ $nombre }}" name="{{ $nombre }}" rows="{{ max(2, min(8, substr_count((string) $valor, "\n") + 2)) }}"
                                              placeholder="{{ $original }}"
                                              class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">{{ old($nombre, $valor) }}</textarea>
                                    @break

                                @case('numero')
                                    <x-text-input :id="$nombre" :name="$nombre" type="number" step="any" min="0" class="mt-1 block w-full" :value="old($nombre, $valor)" />
                                    @break

                                @default
                                    <x-text-input :id="$nombre" :name="$nombre" class="mt-1 block w-full" :value="old($nombre, $valor)" :placeholder="$original" />
                            @endswitch

                            <x-input-error :messages="$errors->get($nombre)" class="mt-1" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky bottom-0 -mx-4 flex gap-3 border-t border-stone-200 bg-stone-100/95 px-4 py-4 sm:-mx-8 sm:px-8">
            <x-admin.boton>Guardar cambios</x-admin.boton>
            <a href="{{ route('admin.paginas.index') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm">Volver</a>
        </div>
    </form>
</x-admin-layout>
