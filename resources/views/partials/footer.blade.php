{{-- Pie de página de la tienda. --}}
<footer class="bg-helena-oscuro text-white/80">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
        <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <a href="{{ route('inicio') }}" class="inline-block" aria-label="{{ config('tienda.nombre') }}">
                    @include('partials.logo', ['claro' => true])
                </a>
                <p class="mt-5 max-w-[14rem] text-xs leading-relaxed text-white/70">Cuidado consciente para tu cabello.</p>
                <div class="mt-5 flex items-center gap-3 text-xs">
                    <span class="font-medium text-white">Síguenos:</span>
                    @include('partials.iconos-redes', ['clase' => 'h-4 w-4'])
                </div>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-white">Explora</p>
                <ul class="mt-4 space-y-1.5 text-xs">
                    <li><a href="{{ route('inicio') }}" class="hover:text-white">Inicio</a></li>
                    <li><a href="{{ route('inicio') }}#nosotros" class="hover:text-white">Nosotros</a></li>
                    <li><a href="{{ route('inicio') }}#autocuidado" class="hover:text-white">Autocuidado</a></li>
                    <li><a href="{{ route('inicio') }}#cuidado-capilar" class="hover:text-white">Cuidado capilar</a></li>
                    <li><a href="{{ route('tienda.catalogo') }}" class="hover:text-white">Tienda</a></li>
                    <li><a href="{{ route('contacto.create') }}" class="hover:text-white">Contacto</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-white">Háblanos</p>
                <ul class="mt-4 space-y-4 text-xs">
                    <li><a href="mailto:{{ config('tienda.email') }}" class="hover:text-white">{{ config('tienda.email') }}</a></li>
                    @if (config('tienda.direccion'))
                        <li>{{ config('tienda.direccion') }}<br>{{ config('tienda.ciudad') }}</li>
                    @endif
                    <li>
                        @foreach (array_filter([config('tienda.telefono'), config('tienda.telefono_2')]) as $telefono)
                            <a href="tel:{{ $telefono }}" class="block hover:text-white">{{ $telefono }}</a>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-14 border-t border-white/20 pt-6 text-center text-[11px] text-white/50">
            © {{ date('Y') }} {{ config('tienda.nombre') }}. Todos los derechos reservados.
        </div>
    </div>
</footer>
