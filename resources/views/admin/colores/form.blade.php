<x-admin-layout :titulo="$color->exists ? 'Editar color' : 'Nuevo color'">
    <form method="POST" action="{{ $color->exists ? route('admin.colores.update', $color) : route('admin.colores.store') }}"
          class="max-w-xl rounded-xl bg-white p-6 shadow-sm space-y-5">
        @csrf
        @if ($color->exists) @method('PUT') @endif

        <div>
            <x-input-label for="nombre" value="Nombre" />
            <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $color->nombre)" required />
        </div>
        <div>
            <x-input-label for="codigo_hex" value="Color" />
            <input id="codigo_hex" type="color" name="codigo_hex" value="{{ old('codigo_hex', $color->codigo_hex) }}" class="mt-1 h-10 w-20 rounded border border-gray-300">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="estado" value="0">
            <input type="checkbox" name="estado" value="1" class="rounded border-gray-300" @checked(old('estado', $color->estado))>
            Activo
        </label>

        <div class="flex gap-3">
            <x-admin.boton>Guardar</x-admin.boton>
            <a href="{{ route('admin.colores.index') }}" class="rounded-md border border-stone-300 px-4 py-2 text-sm">Cancelar</a>
        </div>
    </form>
</x-admin-layout>
