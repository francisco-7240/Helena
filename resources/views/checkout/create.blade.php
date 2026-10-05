<x-tienda-layout titulo="Finalizar compra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="font-serif text-4xl font-semibold">Finalizar compra</h1>

        @guest
            <p class="mt-3 text-sm text-stone-600">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-helena-verde hover:underline">Inicia sesión</a> para ver tus pedidos más tarde.
            </p>
        @endguest

        <form method="POST" action="{{ route('checkout.store') }}" class="mt-8 grid gap-10 lg:grid-cols-[1fr_380px]">
            @csrf
            <div class="space-y-8">
                <section class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-lg">Datos de contacto y envío</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="nombre" value="Nombre completo" />
                            <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $usuario?->name)" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" name="email" class="mt-1 block w-full" :value="old('email', $usuario?->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="telefono" value="Teléfono" />
                            <x-text-input id="telefono" name="telefono" class="mt-1 block w-full" :value="old('telefono')" required />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="direccion" value="Dirección" />
                            <x-text-input id="direccion" name="direccion" class="mt-1 block w-full" :value="old('direccion')" required />
                            <x-input-error :messages="$errors->get('direccion')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="ciudad" value="Ciudad" />
                            <x-text-input id="ciudad" name="ciudad" class="mt-1 block w-full" :value="old('ciudad')" required />
                            <x-input-error :messages="$errors->get('ciudad')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="codigo_postal" value="Código postal (opcional)" />
                            <x-text-input id="codigo_postal" name="codigo_postal" class="mt-1 block w-full" :value="old('codigo_postal')" />
                            <x-input-error :messages="$errors->get('codigo_postal')" class="mt-1" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="notas" value="Notas del pedido (opcional)" />
                            <textarea id="notas" name="notas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notas') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-lg">Método de pago</h2>
                    <div class="mt-4 space-y-3">
                        @foreach (\App\Models\Pedido::METODOS_PAGO as $valor => $texto)
                            <label class="flex items-center gap-3 rounded-md border border-stone-200 p-4 cursor-pointer has-[:checked]:border-stone-900">
                                <input type="radio" name="metodo_pago" value="{{ $valor }}" class="text-stone-900 focus:ring-stone-900" @checked(old('metodo_pago', 'transferencia') === $valor)>
                                <span class="text-sm font-medium">{{ $texto }}</span>
                            </label>
                        @endforeach
                        <x-input-error :messages="$errors->get('metodo_pago')" class="mt-1" />
                    </div>
                </section>
            </div>

            <aside class="rounded-xl bg-white p-6 shadow-sm h-fit">
                <h2 class="font-semibold text-lg">Tu pedido</h2>
                <ul class="mt-4 divide-y divide-stone-100 text-sm">
                    @foreach ($lineas as $linea)
                        <li class="flex justify-between gap-4 py-3">
                            <span>
                                {{ $linea->producto->nombre }}
                                @if ($linea->variante->color)
                                    <span class="text-stone-500">({{ $linea->variante->color->nombre }})</span>
                                @endif
                                × {{ $linea->cantidad }}
                            </span>
                            <span class="whitespace-nowrap">@precio($linea->subtotal)</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 border-t border-stone-200 pt-4">
                    <x-tienda.resumen :subtotal="$subtotal" :envio="$envio" :total="$total" />
                </div>
                <button class="mt-6 w-full rounded-full bg-helena-oscuro px-6 py-3 text-sm font-semibold text-white hover:bg-helena-verde">Confirmar pedido</button>
            </aside>
        </form>
    </div>
</x-tienda-layout>
