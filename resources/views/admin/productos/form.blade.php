@php
    $variantes = old('variantes', $producto->exists
        ? $producto->productoColores->map(fn ($v) => ['color_id' => $v->color_id, 'stock' => $v->stock])->values()->all()
        : [['color_id' => null, 'stock' => 0]]);
@endphp
<x-admin-layout :titulo="$producto->exists ? 'Editar producto' : 'Nuevo producto'">
    @if ($producto->exists)
        <x-slot name="acciones">
            <a href="{{ route('tienda.producto', $producto) }}" target="_blank" class="text-sm text-stone-600 hover:underline">Ver en la tienda ↗</a>
        </x-slot>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $producto->exists ? route('admin.productos.update', $producto) : route('admin.productos.store') }}"
          class="grid gap-6 xl:grid-cols-3">
        @csrf
        @if ($producto->exists) @method('PUT') @endif

        <div class="xl:col-span-2 space-y-6">
            <section class="rounded-xl bg-white p-6 shadow-sm space-y-5">
                <div>
                    <x-input-label for="nombre" value="Nombre" />
                    <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $producto->nombre)" required />
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="slug" value="Slug (URL, opcional)" />
                        <x-text-input id="slug" name="slug" class="mt-1 block w-full" :value="old('slug', $producto->slug)" />
                    </div>
                    <div>
                        <x-input-label for="sku" value="SKU (opcional)" />
                        <x-text-input id="sku" name="sku" class="mt-1 block w-full" :value="old('sku', $producto->sku)" />
                    </div>
                </div>
                <div>
                    <x-input-label for="descripcion" value="Descripción" />
                    <textarea id="descripcion" name="descripcion" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm" x-data="{ filas: @js($variantes) }">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Variantes e inventario</h2>
                    <button type="button" @click="filas.push({ color_id: '', stock: 0 })" class="text-sm text-rose-700 hover:underline">+ Agregar color</button>
                </div>
                <p class="mt-1 text-xs text-stone-500">Deja el color en «Sin color» si el producto tiene una sola variante.</p>
                <div class="mt-4 space-y-3">
                    <template x-for="(fila, i) in filas" :key="i">
                        <div class="flex items-center gap-3">
                            <select :name="`variantes[${i}][color_id]`" x-model="fila.color_id" class="flex-1 rounded-md border-gray-300 text-sm">
                                <option value="">Sin color</option>
                                @foreach ($colores as $color)
                                    <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                                @endforeach
                            </select>
                            <input type="number" min="0" :name="`variantes[${i}][stock]`" x-model="fila.stock" class="w-28 rounded-md border-gray-300 text-sm" placeholder="Stock">
                            <button type="button" @click="filas.splice(i, 1)" x-show="filas.length > 1" class="text-stone-400 hover:text-rose-700" aria-label="Quitar">✕</button>
                        </div>
                    </template>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="font-semibold">Imágenes</h2>
                @if ($producto->exists && $producto->imagenes->isNotEmpty())
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($producto->imagenes as $imagen)
                            <div class="rounded-lg border border-stone-200 p-2 text-xs">
                                <img src="{{ Storage::disk('public')->url($imagen->imagen) }}" alt="" class="aspect-square w-full rounded object-cover">
                                <label class="mt-2 flex items-center gap-1">
                                    <input type="radio" name="portada_id" value="{{ $imagen->id }}" @checked($imagen->es_portada)> Portada
                                </label>
                                <label class="mt-1 flex items-center gap-1 text-rose-700">
                                    <input type="checkbox" name="eliminar_imagenes[]" value="{{ $imagen->id }}" class="rounded border-gray-300"> Eliminar
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="imagenes[]" accept="image/*" multiple class="mt-4 block text-sm">
                <p class="mt-1 text-xs text-stone-500">Hasta 10 imágenes de máximo 4 MB cada una.</p>
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-xl bg-white p-6 shadow-sm space-y-5">
                <div>
                    <x-input-label for="precio" value="Precio" />
                    <x-text-input id="precio" type="number" step="0.01" min="0" name="precio" class="mt-1 block w-full" :value="old('precio', $producto->precio)" required />
                </div>
                <div>
                    <x-input-label for="precio_oferta" value="Precio de oferta (opcional)" />
                    <x-text-input id="precio_oferta" type="number" step="0.01" min="0" name="precio_oferta" class="mt-1 block w-full" :value="old('precio_oferta', $producto->precio_oferta)" />
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm space-y-5">
                <div>
                    <x-input-label for="categoria_id" value="Categoría" />
                    <select id="categoria_id" name="categoria_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Selecciona…</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="marca_id" value="Marca" />
                    <select id="marca_id" name="marca_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Sin marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}" @selected(old('marca_id', $producto->marca_id) == $marca->id)>{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="estado" value="0">
                    <input type="checkbox" name="estado" value="1" class="rounded border-gray-300" @checked(old('estado', $producto->estado))>
                    Visible en la tienda
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="destacado" value="0">
                    <input type="checkbox" name="destacado" value="1" class="rounded border-gray-300" @checked(old('destacado', $producto->destacado))>
                    Destacado en la portada
                </label>
            </section>

            <div class="flex gap-3">
                <x-admin.boton>Guardar producto</x-admin.boton>
                <a href="{{ route('admin.productos.index') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm">Cancelar</a>
            </div>
        </div>
    </form>
</x-admin-layout>
