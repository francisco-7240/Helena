<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Helena') }} | Tienda</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-rose-50 text-gray-900">
        <!-- Encabezado -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-2xl font-bold tracking-wide text-rose-600">Helena</a>

                <nav class="flex items-center gap-4 text-sm font-medium">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hover:text-rose-600">Mi cuenta</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-rose-600">Iniciar sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-md bg-rose-600 px-4 py-2 text-white hover:bg-rose-700">Registrarse</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <!-- Portada -->
        <main>
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900">Bienvenida a <span class="text-rose-600">Helena</span></h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Tu tienda en línea. Descubre nuestros productos y encuentra lo que buscas.
                </p>
                <a href="#productos" class="mt-8 inline-block rounded-md bg-rose-600 px-6 py-3 text-white font-semibold hover:bg-rose-700">Ver productos</a>
            </section>

            <section id="productos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
                <h2 class="text-2xl font-semibold mb-6">Productos destacados</h2>
                <p class="text-gray-500">Próximamente.</p>
            </section>
        </main>

        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 text-center">
                &copy; {{ date('Y') }} Helena. Todos los derechos reservados.
            </div>
        </footer>
    </body>
</html>
