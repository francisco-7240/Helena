<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_muestra_productos_activos(): void
    {
        $activo = Producto::factory()->conStock()->destacado()->create();
        $inactivo = Producto::factory()->inactivo()->create();

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee($activo->nombre)
            ->assertDontSee($inactivo->nombre);
    }

    public function test_el_catalogo_filtra_por_categoria_y_busqueda(): void
    {
        $bolsos = Categoria::factory()->create(['nombre' => 'Bolsos', 'slug' => 'bolsos']);
        $tote = Producto::factory()->for($bolsos)->create(['nombre' => 'Bolso tote']);
        $sandalia = Producto::factory()->create(['nombre' => 'Sandalia trenzada']);

        $this->get(route('tienda.categoria', $bolsos))
            ->assertOk()
            ->assertSee($tote->nombre)
            ->assertDontSee($sandalia->nombre);

        $this->get(route('tienda.catalogo', ['buscar' => 'Sandalia']))
            ->assertOk()
            ->assertSee($sandalia->nombre)
            ->assertDontSee($tote->nombre);
    }

    public function test_el_detalle_de_producto_se_muestra(): void
    {
        $producto = Producto::factory()->conStock()->create(['precio' => 50000, 'precio_oferta' => 40000]);

        $this->get(route('tienda.producto', $producto))
            ->assertOk()
            ->assertSee($producto->nombre)
            ->assertSee('40.000')
            ->assertSee('Agregar al carrito');
    }

    public function test_un_producto_inactivo_no_se_puede_ver(): void
    {
        $producto = Producto::factory()->inactivo()->create();

        $this->get(route('tienda.producto', $producto))->assertNotFound();
    }

    public function test_un_visitante_puede_enviar_un_mensaje_de_contacto(): void
    {
        $this->post(route('contacto.store'), [
            'nombre' => 'Ana',
            'email' => 'ana@example.com',
            'mensaje' => 'Hola, ¿tienen envíos?',
        ])->assertRedirect(route('contacto.create'));

        $this->assertDatabaseHas('contactos', ['email' => 'ana@example.com', 'estado' => 'pendiente']);
    }
}
