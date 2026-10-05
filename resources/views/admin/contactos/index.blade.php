<x-admin-layout titulo="Mensajes de contacto">
    <div class="space-y-4">
        @forelse ($contactos as $contacto)
            <article class="rounded-xl bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold">{{ $contacto->nombre }}</p>
                        <p class="text-sm text-stone-500">
                            <a href="mailto:{{ $contacto->email }}" class="hover:underline">{{ $contacto->email }}</a>
                            @if ($contacto->telefono) · {{ $contacto->telefono }} @endif
                            · {{ $contacto->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <form method="POST" action="{{ route('admin.contactos.update', $contacto) }}">
                            @csrf
                            @method('PATCH')
                            <select name="estado" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                                @foreach (['pendiente' => 'Pendiente', 'respondido' => 'Respondido', 'archivado' => 'Archivado'] as $valor => $texto)
                                    <option value="{{ $valor }}" @selected($contacto->estado === $valor)>{{ $texto }}</option>
                                @endforeach
                            </select>
                        </form>
                        <x-admin.eliminar :action="route('admin.contactos.destroy', $contacto)" />
                    </div>
                </div>
                <p class="mt-3 whitespace-pre-line text-sm text-stone-700">{{ $contacto->mensaje }}</p>
            </article>
        @empty
            <p class="rounded-xl bg-white p-6 text-stone-500 shadow-sm">No hay mensajes.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $contactos->links() }}</div>
</x-admin-layout>
