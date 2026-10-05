<x-admin-layout :titulo="$marca->exists ? 'Editar marca' : 'Nueva marca'">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $marca->exists ? route('admin.marcas.update', $marca) : route('admin.marcas.store') }}"
          class="max-w-2xl rounded-xl bg-white p-6 shadow-sm space-y-5">
        @csrf
        @if ($marca->exists) @method('PUT') @endif

        <div>
            <x-input-label for="nombre" value="Nombre" />
            <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $marca->nombre)" required />
        </div>
        <div>
            <x-input-label for="slug" value="Slug (URL, opcional)" />
            <x-text-input id="slug" name="slug" class="mt-1 block w-full" :value="old('slug', $marca->slug)" />
        </div>
        <div>
            <x-input-label for="descripcion" value="Descripción" />
            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descripcion', $marca->descripcion) }}</textarea>
        </div>
        <div>
            <x-input-label for="imagen" value="Imagen" />
            @if ($marca->imagen)
                <img src="{{ Storage::disk('public')->url($marca->imagen) }}" alt="" class="mt-2 h-24 w-24 rounded object-cover">
            @endif
            <input id="imagen" type="file" name="imagen" accept="image/*" class="mt-2 block text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="estado" value="0">
            <input type="checkbox" name="estado" value="1" class="rounded border-gray-300" @checked(old('estado', $marca->estado))>
            Activa (visible en la tienda)
        </label>

        <div class="flex gap-3">
            <x-admin.boton>Guardar</x-admin.boton>
            <a href="{{ route('admin.marcas.index') }}" class="rounded-md border border-stone-300 px-4 py-2 text-sm">Cancelar</a>
        </div>
    </form>
</x-admin-layout>
