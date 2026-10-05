<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Color>
 */
class ColorFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = ucfirst(fake()->unique()->colorName());

        return [
            'nombre' => $nombre,
            'slug' => Str::slug($nombre),
            'codigo_hex' => fake()->hexColor(),
            'estado' => true,
        ];
    }
}
