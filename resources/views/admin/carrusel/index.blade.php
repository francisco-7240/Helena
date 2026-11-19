<x-admin-layout titulo="Banners de inicio">
    <x-slot name="acciones">
        <a href="{{ route('inicio') }}" target="_blank" class="text-sm text-stone-600 hover:underline">Ver inicio ↗</a>
    </x-slot>

    <p class="mb-6 max-w-3xl text-sm text-stone-600">
        Estos banners ocupan toda la pantalla al entrar a la tienda y cambian solos cada 6 segundos, en el orden
        indicado (de menor a mayor). Si no hay ninguno visible, la portada muestra el diseño de «Contenido del sitio → Inicio».
        <br><strong>Tamaño recomendado:</strong> 1920 × 1080 px (horizontal). Para el celular puedes subir una versión vertical de 1080 × 1920 px;
        si no, se usa la horizontal recortada. Deja los textos importantes hacia el centro de la imagen.
    </p>

    {{-- Agregar imágenes --}}
    <form method="POST" action="{{ route('admin.carrusel.store') }}" enctype="multipart/form-data"
          class="mb-8 flex flex-col gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-end">
        @csrf
        <div class="flex-1">
            <x-input-label for="imagenes" value="Agregar banners (puedes elegir varios a la vez)" />
            <input id="imagenes" type="file" name="imagenes[]" accept="image/*" multiple required class="mt-2 block w-full text-sm">
            <p class="mt-1 text-xs text-stone-500">Hasta 10 imágenes por vez, de máximo 5 MB cada una.</p>
            <x-input-error :messages="$errors->get('imagenes')" class="mt-1" />
            <x-input-error :messages="$errors->get('imagenes.*')" class="mt-1" />
        </div>
        <x-admin.boton>Subir banners</x-admin.boton>
    </form>

    {{-- Imágenes actuales --}}
    @if ($imagenes->isEmpty())
        <p class="rounded-xl bg-white p-6 text-sm text-stone-500 shadow-sm">Todavía no hay banners en el carrusel.</p>
    @else
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($imagenes as $imagen)
                <article @class(['flex flex-col overflow-hidden rounded-xl bg-white shadow-sm', 'opacity-60' => ! $imagen->activo])>
                    <div class="relative aspect-video bg-stone-100">
                        <img src="{{ $imagen->url() }}" alt="{{ $imagen->descripcion }}" class="h-full w-full object-cover">
                        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-0.5 text-xs font-semibold text-stone-700">#{{ $imagen->orden }}</span>
                        @unless ($imagen->activo)
                            <span class="absolute right-3 top-3 rounded-full bg-stone-800/80 px-2.5 py-0.5 text-xs font-semibold text-white">Oculta</span>
                        @endunless
                    </div>

                    <form id="actualizar-{{ $imagen->id }}" method="POST" action="{{ route('admin.carrusel.update', $imagen) }}" enctype="multipart/form-data" class="flex flex-1 flex-col gap-3 p-4 pb-0 text-sm">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label :for="'descripcion-'.$imagen->id" value="Descripción (para accesibilidad y buscadores)" />
                            <x-text-input :id="'descripcion-'.$imagen->id" name="descripcion" class="mt-1 block w-full text-sm" :value="$imagen->descripcion" />
                        </div>
                        <div>
                            <x-input-label :for="'enlace-'.$imagen->id" value="Enlace al hacer clic (opcional)" />
                            <x-text-input :id="'enlace-'.$imagen->id" name="enlace" class="mt-1 block w-full text-sm" :value="$imagen->enlace" placeholder="/tienda o https://…" />
                        </div>
                        <div>
                            <x-input-label :for="'movil-'.$imagen->id" value="Versión para celular (opcional, vertical)" />
                            @if ($imagen->imagen_movil)
                                <div class="mt-1 flex items-center gap-3">
                                    <img src="{{ $imagen->urlMovil() }}" alt="" class="h-20 w-12 rounded object-cover">
                                    <label class="flex items-center gap-2 text-xs text-stone-600">
                                        <input type="checkbox" name="quitar_movil" value="1" class="rounded border-gray-300"> Quitar versión para celular
                                    </label>
                                </div>
                            @endif
                            <input id="movil-{{ $imagen->id }}" type="file" name="imagen_movil" accept="image/*" class="mt-1 block w-full text-xs">
                        </div>
                        <div class="flex items-end gap-4">
                            <div>
                                <x-input-label :for="'orden-'.$imagen->id" value="Orden" />
                                <x-text-input :id="'orden-'.$imagen->id" name="orden" type="number" min="0" class="mt-1 block w-24 text-sm" :value="$imagen->orden" />
                            </div>
                            <label class="flex items-center gap-2 pb-2">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" class="rounded border-gray-300" @checked($imagen->activo)>
                                Visible
                            </label>
                        </div>
                    </form>
                    <div class="flex items-center justify-between p-4 text-sm">
                        <button form="actualizar-{{ $imagen->id }}" class="rounded-md border border-stone-300 px-3 py-1.5 text-sm hover:bg-stone-50">Guardar</button>
                        <x-admin.eliminar :action="route('admin.carrusel.destroy', $imagen)" mensaje="¿Eliminar este banner del carrusel? Los archivos se borrarán." />
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-admin-layout>
