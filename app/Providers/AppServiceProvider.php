<?php

namespace App\Providers;

use App\Services\Carrito;
use App\Services\Contenidos;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(Carrito::class, fn ($app) => new Carrito($app['session.store']));
        $this->app->scoped(Contenidos::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        $this->app->make(Contenidos::class)->aplicarConfiguracion();

        Blade::directive('precio', fn (string $expresion) => "<?php echo e(\\App\\Support\\Precio::formato({$expresion})); ?>");

        View::composer('partials.header', function ($view) {
            $view->with('cantidadCarrito', app(Carrito::class)->cantidadTotal());
        });
    }
}
