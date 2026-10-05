<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 300);

        return [
            'codigo' => 'HEL-'.strtoupper(fake()->unique()->bothify('########')),
            'nombre' => fake()->name(),
            'email' => fake()->safeEmail(),
            'telefono' => fake()->numerify('##########'),
            'direccion' => fake()->streetAddress(),
            'ciudad' => fake()->city(),
            'codigo_postal' => fake()->postcode(),
            'metodo_pago' => 'transferencia',
            'subtotal' => $subtotal,
            'envio' => 0,
            'total' => $subtotal,
            'estado' => 'pendiente',
        ];
    }
}
