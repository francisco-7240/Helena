<x-admin-layout titulo="Contenido del sitio">
    <p class="mb-6 max-w-2xl text-sm text-stone-600">
        Edita los textos e imágenes de cada página. Los cambios se ven de inmediato en la tienda.
        Si dejas un campo vacío, vuelve a mostrarse el texto original.
    </p>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($paginas as $clave => $pagina)
            <div class="flex flex-col rounded-xl bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">{{ $pagina['titulo'] }}</h2>
                <p class="mt-1 text-sm text-stone-500">
                    {{ count($pagina['secciones']) }} {{ count($pagina['secciones']) === 1 ? 'sección' : 'secciones' }}:
                    {{ collect($pagina['secciones'])->pluck('titulo')->join(', ', ' y ') }}.
                </p>
                <div class="mt-4 flex items-center gap-4 pt-2 mt-auto">
                    <x-admin.boton :href="route('admin.paginas.edit', $clave)">Editar</x-admin.boton>
                    @if ($pagina['ruta'])
                        <a href="{{ route($pagina['ruta']) }}" target="_blank" class="text-sm text-stone-600 hover:underline">Ver página ↗</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
