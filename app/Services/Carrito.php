<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;

/**
 * Carrito de compras guardado en la sesión.
 *
 * Cada línea se identifica por el id de producto_colores (variante),
 * y sólo guarda la cantidad: precio y stock se leen siempre de la base de datos.
 */
class Carrito
{
    private const CLAVE = 'carrito';

    public function __construct(private Store $session) {}

    /**
     * @return array<int, int> producto_color_id => cantidad
     */
    public function contenido(): array
    {
        return $this->session->get(self::CLAVE, []);
    }

    public function agregar(ProductoColor $variante, int $cantidad = 1): void
    {
        $contenido = $this->contenido();
        $actual = $contenido[$variante->id] ?? 0;
        $contenido[$variante->id] = min($actual + $cantidad, $variante->stock);

        $this->guardar($contenido);
    }

    public function actualizar(int $varianteId, int $cantidad): void
    {
        $contenido = $this->contenido();

        if (! isset($contenido[$varianteId])) {
            return;
        }

        if ($cantidad <= 0) {
            unset($contenido[$varianteId]);
        } else {
            $variante = ProductoColor::find($varianteId);
            $contenido[$varianteId] = min($cantidad, $variante?->stock ?? 0);
        }

        $this->guardar($contenido);
    }

    public function eliminar(int $varianteId): void
    {
        $contenido = $this->contenido();
        unset($contenido[$varianteId]);

        $this->guardar($contenido);
    }

    public function vaciar(): void
    {
        $this->session->forget(self::CLAVE);
    }

    public function cantidadTotal(): int
    {
        return array_sum($this->contenido());
    }

    public function estaVacio(): bool
    {
        return $this->contenido() === [];
    }

    /**
     * Líneas del carrito con sus modelos cargados.
     *
     * @return Collection<int, object{variante: ProductoColor, producto: Producto, cantidad: int, precio: float, subtotal: float}>
     */
    public function lineas(): Collection
    {
        $contenido = $this->contenido();

        $variantes = ProductoColor::with(['producto.imagenes', 'color'])
            ->whereIn('id', array_keys($contenido))
            ->get()
            ->filter(fn (ProductoColor $v) => $v->producto?->estado);

        return $variantes->map(function (ProductoColor $variante) use ($contenido) {
            $cantidad = $contenido[$variante->id];
            $precio = $variante->producto->precioFinal();

            return (object) [
                'variante' => $variante,
                'producto' => $variante->producto,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'subtotal' => round($precio * $cantidad, 2),
            ];
        })->values();
    }

    public function subtotal(?Collection $lineas = null): float
    {
        return round(($lineas ?? $this->lineas())->sum('subtotal'), 2);
    }

    public function envio(float $subtotal): float
    {
        if ($subtotal <= 0 || $subtotal >= config('tienda.envio_gratis_desde')) {
            return 0.0;
        }

        return (float) config('tienda.envio');
    }

    private function guardar(array $contenido): void
    {
        $this->session->put(self::CLAVE, array_filter($contenido, fn ($c) => $c > 0));
    }
}
