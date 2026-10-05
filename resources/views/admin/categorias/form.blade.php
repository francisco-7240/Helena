<x-admin-layout :titulo="$categoria->exists ? 'Editar categoría' : 'Nueva categoría'">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $categoria->exists ? route('admin.categorias.update', $categoria) : route('admin.categorias.store') }}"
          class="max-w-2xl rounded-xl bg-white p-6 shadow-sm space-y-5">
        @csrf
        @if ($categoria->exists) @method('PUT') @endif

        <div>
            <x-input-label for="nombre" value="Nombre" />
            <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $categoria->nombre)" required />
        </div>
        <div>
            <x-input-label for="slug" value="Slug (URL, opcional)" />
            <x-text-input id="slug" name="slug" class="mt-1 block w-full" :value="old('slug', $categoria->slug)" />
        </div>
        <div>
            <x-input-label for="categoria_padre_id" value="Categoría padre" />
            <select id="categoria_padre_id" name="categoria_padre_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">— Ninguna —</option>
                @foreach ($padres as $padre)
                    <option value="{{ $padre->id }}" @selected(old('categoria_padre_id', $categoria->categoria_padre_id) == $padre->id)>{{ $padre->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="descripcion" value="Descripción" />
            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>
        <div>
            <x-input-label for="imagen" value="Imagen" />
            @if ($categoria->imagen)
                <img src="{{ Storage::disk('public')->url($categoria->imagen) }}" alt="" class="mt-2 h-24 w-24 rounded object-cover">
            @endif
            <input id="imagen" type="file" name="imagen" accept="image/*" class="mt-2 block text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="estado" value="0">
            <input type="checkbox" name="estado" value="1" class="rounded border-gray-300" @checked(old('estado', $categoria->estado))>
            Activa (visible en la tienda)
        </label>

        <div class="flex gap-3">
            <x-admin.boton>Guardar</x-admin.boton>
            <a href="{{ route('admin.categorias.index') }}" class="rounded-md border border-stone-300 px-4 py-2 text-sm">Cancelar</a>
        </div>
    </form>
</x-admin-layout>
