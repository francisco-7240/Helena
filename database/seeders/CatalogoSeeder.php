<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Catálogo de ejemplo para la tienda Helena.
 */
class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $colores = collect([
            ['Negro', '#111827'],
            ['Blanco', '#F9FAFB'],
            ['Rosa', '#F472B6'],
            ['Beige', '#D6C7A1'],
            ['Azul', '#2563EB'],
            ['Verde', '#16A34A'],
        ])->map(fn (array $c) => Color::firstOrCreate(
            ['slug' => Str::slug($c[0])],
            ['nombre' => $c[0], 'codigo_hex' => $c[1]],
        ));

        $marcas = collect(['Helena', 'Aurora', 'Luna Bella'])->map(fn (string $nombre) => Marca::firstOrCreate(
            ['slug' => Str::slug($nombre)],
            ['nombre' => $nombre],
        ));

        $catalogo = [
            'Bolsos' => [
                ['Bolso tote clásico', 89.90, 74.90],
                ['Bandolera mini', 59.90, null],
                ['Mochila urbana', 79.00, null],
            ],
            'Accesorios' => [
                ['Pañuelo de seda', 29.90, null],
                ['Cinturón de cuero', 39.90, 32.00],
                ['Gafas de sol retro', 49.00, null],
            ],
            'Calzado' => [
                ['Sandalias trenzadas', 69.90, null],
                ['Zapatillas blancas', 85.00, 69.00],
                ['Botines de ante', 119.00, null],
            ],
            'Joyería' => [
                ['Aretes dorados', 24.90, null],
                ['Collar de perlas', 45.00, null],
                ['Pulsera de plata', 35.00, 29.90],
            ],
        ];

        foreach ($catalogo as $nombreCategoria => $productos) {
            $categoria = Categoria::firstOrCreate(
                ['slug' => Str::slug($nombreCategoria)],
                ['nombre' => $nombreCategoria, 'descripcion' => "Descubre nuestra colección de {$nombreCategoria}."],
            );

            foreach ($productos as $i => [$nombre, $precio, $oferta]) {
                $producto = Producto::firstOrCreate(
                    ['slug' => Str::slug($nombre)],
                    [
                        'categoria_id' => $categoria->id,
                        'marca_id' => $marcas->random()->id,
                        'nombre' => $nombre,
                        'sku' => 'HEL-'.strtoupper(Str::random(6)),
                        'descripcion' => "{$nombre} de la colección Helena. Diseño elegante, materiales de calidad y acabados cuidados para acompañarte todos los días.",
                        'precio' => $precio,
                        'precio_oferta' => $oferta,
                        'destacado' => $i === 0,
                    ],
                );

                if ($producto->productoColores()->exists()) {
                    continue;
                }

                foreach ($colores->random(3)->values() as $j => $color) {
                    $producto->productoColores()->create([
                        'color_id' => $color->id,
                        'stock' => random_int(0, 15),
                        'es_predeterminado' => $j === 0,
                    ]);
                }
            }
        }
    }
}
