<?php

namespace Database\Factories;

use App\Models\CarruselImagen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CarruselImagen>
 */
class CarruselImagenFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imagen' => 'carrusel/'.fake()->unique()->slug(2).'.jpg',
            'descripcion' => fake()->sentence(3),
            'orden' => fake()->numberBetween(1, 10),
            'activo' => true,
        ];
    }

    public function oculta(): static
    {
        return $this->state(['activo' => false]);
    }
}
