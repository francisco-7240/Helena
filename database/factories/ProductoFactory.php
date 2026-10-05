<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = ucfirst(fake()->unique()->words(3, true));

        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => $nombre,
            'slug' => Str::slug($nombre),
            'sku' => strtoupper(fake()->unique()->bothify('HEL-####??')),
            'descripcion' => fake()->paragraph(),
            'precio' => fake()->randomFloat(2, 10, 200),
            'precio_oferta' => null,
            'estado' => true,
            'destacado' => false,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['estado' => false]);
    }

    public function destacado(): static
    {
        return $this->state(['destacado' => true]);
    }

    /**
     * Crea una variante sin color con el stock indicado.
     */
    public function conStock(int $stock = 10): static
    {
        return $this->has(
            ProductoColor::factory()->state(['stock' => $stock, 'es_predeterminado' => true]),
            'productoColores'
        );
    }
}
