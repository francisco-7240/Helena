<x-tienda-layout titulo="Contacto">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 md:grid-cols-2">
        <div>
            <h1 class="font-serif text-4xl font-semibold">Contáctanos</h1>
            <p class="mt-4 text-stone-600">¿Tienes alguna pregunta sobre un producto, tu pedido o quieres una recomendación? Escríbenos y te responderemos a la brevedad.</p>
            <dl class="mt-8 space-y-4 text-sm">
                <div>
                    <dt class="font-semibold">Correo</dt>
                    <dd class="text-stone-600">{{ config('tienda.email') }}</dd>
                </div>
                @if (config('tienda.telefono'))
                    <div>
                        <dt class="font-semibold">Teléfono</dt>
                        <dd class="text-stone-600">{{ config('tienda.telefono') }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <form method="POST" action="{{ route('contacto.store') }}" class="rounded-xl bg-white p-6 shadow-sm space-y-4">
            @csrf
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', auth()->user()?->name)" required />
                <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="Correo electrónico" />
                <x-text-input id="email" type="email" name="email" class="mt-1 block w-full" :value="old('email', auth()->user()?->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="telefono" value="Teléfono (opcional)" />
                <x-text-input id="telefono" name="telefono" class="mt-1 block w-full" :value="old('telefono')" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="mensaje" value="Mensaje" />
                <textarea id="mensaje" name="mensaje" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('mensaje') }}</textarea>
                <x-input-error :messages="$errors->get('mensaje')" class="mt-1" />
            </div>
            <button class="w-full rounded-full bg-helena-oscuro px-6 py-3 text-sm font-semibold text-white hover:bg-helena-verde">Enviar mensaje</button>
        </form>
    </div>
</x-tienda-layout>
