@props(['action', 'mensaje' => '¿Seguro que deseas eliminar este registro?'])
<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm(@js($mensaje))">
    @csrf
    @method('DELETE')
    <button class="text-rose-700 hover:underline">{{ $slot->isEmpty() ? 'Eliminar' : $slot }}</button>
</form>
