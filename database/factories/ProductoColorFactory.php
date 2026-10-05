<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductoColor>
 */
class ProductoColorFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'color_id' => null,
            'stock' => 10,
            'es_predeterminado' => false,
        ];
    }
}
