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
        foreach ([['Natural', '#C9B79C'], ['Aguacate', '#7A8B3A'], ['Rosa', '#D9A69C']] as [$nombre, $hex]) {
            Color::firstOrCreate(['slug' => Str::slug($nombre)], ['nombre' => $nombre, 'codigo_hex' => $hex]);
        }

        $marca = Marca::firstOrCreate(['slug' => 'helena'], ['nombre' => 'Helena', 'descripcion' => 'Cuido de ti.']);

        $catalogo = [
            'Cuidado capilar' => [
                ['Raíz Viva', 'Shampoo natural. Limpieza suave para volver a lo esencial.', 42000, null, true],
                ['Acondicionador', 'Suavidad, brillo y calma en cada lavado.', 38000, null, true],
                ['Tratamiento Capilar', 'Nutrición profunda para días de reparación.', 48000, 43000, true],
                ['Keratina de Aguacate', 'Transforma la textura, conserva tu esencia.', 55000, null, true],
            ],
            'Cuidado facial' => [
                ['Jabón Facial', 'Jabón artesanal de limpieza suave para tu rostro.', 18000, null, false],
            ],
        ];

        foreach ($catalogo as $nombreCategoria => $productos) {
            $categoria = Categoria::firstOrCreate(
                ['slug' => Str::slug($nombreCategoria)],
                ['nombre' => $nombreCategoria, 'descripcion' => "Rituales de {$nombreCategoria} para volver a ti."],
            );

            foreach ($productos as [$nombre, $descripcion, $precio, $oferta, $destacado]) {
                $producto = Producto::firstOrCreate(
                    ['slug' => Str::slug($nombre)],
                    [
                        'categoria_id' => $categoria->id,
                        'marca_id' => $marca->id,
                        'nombre' => $nombre,
                        'sku' => 'HEL-'.strtoupper(Str::random(6)),
                        'descripcion' => $descripcion,
                        'precio' => $precio,
                        'precio_oferta' => $oferta,
                        'destacado' => $destacado,
                    ],
                );

                if (! $producto->productoColores()->exists()) {
                    $producto->productoColores()->create(['color_id' => null, 'stock' => 20, 'es_predeterminado' => true]);
                }
            }
        }
    }
}
