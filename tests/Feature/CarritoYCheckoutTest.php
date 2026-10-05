<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoYCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function datosEnvio(): array
    {
        return [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'telefono' => '3001234567',
            'direccion' => 'Calle 1 # 2-3',
            'ciudad' => 'Bogotá',
            'metodo_pago' => 'transferencia',
        ];
    }

    public function test_agregar_al_carrito_no_supera_el_stock(): void
    {
        $variante = Producto::factory()->conStock(3)->create()->productoColores->first();

        $this->post(route('carrito.agregar'), ['producto_color_id' => $variante->id, 'cantidad' => 5])
            ->assertRedirect(route('carrito.index'))
            ->assertSessionHas('carrito', [$variante->id => 3]);
    }

    public function test_no_se_puede_agregar_un_producto_agotado(): void
    {
        $variante = Producto::factory()->conStock(0)->create()->productoColores->first();

        $this->post(route('carrito.agregar'), ['producto_color_id' => $variante->id, 'cantidad' => 1])
            ->assertSessionHasErrors('producto_color_id')
            ->assertSessionMissing('carrito');
    }

    public function test_se_puede_actualizar_y_eliminar_del_carrito(): void
    {
        $variante = Producto::factory()->conStock(10)->create()->productoColores->first();
        $this->withSession(['carrito' => [$variante->id => 1]]);

        $this->patch(route('carrito.actualizar', $variante->id), ['cantidad' => 4])
            ->assertSessionHas('carrito', [$variante->id => 4]);

        $this->delete(route('carrito.eliminar', $variante->id))
            ->assertSessionHas('carrito', []);
    }

    public function test_el_checkout_crea_el_pedido_y_descuenta_stock(): void
    {
        config(['tienda.envio' => 10, 'tienda.envio_gratis_desde' => 100]);
        $producto = Producto::factory()->conStock(5)->create(['precio' => 30, 'precio_oferta' => 25]);
        $variante = $producto->productoColores->first();
        $usuario = User::factory()->create();

        $respuesta = $this->actingAs($usuario)
            ->withSession(['carrito' => [$variante->id => 2]])
            ->post(route('checkout.store'), $this->datosEnvio());

        $pedido = Pedido::sole();
        $respuesta->assertRedirect(route('checkout.confirmacion', $pedido))->assertSessionMissing('carrito');

        $this->assertSame($usuario->id, $pedido->user_id);
        $this->assertEquals(50, $pedido->subtotal);
        $this->assertEquals(10, $pedido->envio);
        $this->assertEquals(60, $pedido->total);
        $this->assertDatabaseHas('pedido_items', [
            'pedido_id' => $pedido->id,
            'producto_color_id' => $variante->id,
            'cantidad' => 2,
            'precio' => 25,
        ]);
        $this->assertSame(3, $variante->fresh()->stock);

        $this->get(route('checkout.confirmacion', $pedido))->assertOk()->assertSee($pedido->codigo);
    }

    public function test_el_envio_es_gratis_desde_el_monto_configurado(): void
    {
        config(['tienda.envio' => 10, 'tienda.envio_gratis_desde' => 100]);
        $variante = Producto::factory()->conStock(5)->create(['precio' => 60])->productoColores->first();

        $this->withSession(['carrito' => [$variante->id => 2]])
            ->post(route('checkout.store'), $this->datosEnvio());

        $this->assertEquals(0, Pedido::sole()->envio);
    }

    public function test_el_checkout_falla_si_no_hay_stock_suficiente(): void
    {
        $variante = Producto::factory()->conStock(1)->create()->productoColores->first();

        $this->withSession(['carrito' => [$variante->id => 3]])
            ->post(route('checkout.store'), $this->datosEnvio())
            ->assertSessionHasErrors('carrito');

        $this->assertDatabaseCount('pedidos', 0);
        $this->assertSame(1, $variante->fresh()->stock);
    }

    public function test_el_checkout_valida_los_datos_de_envio(): void
    {
        $variante = Producto::factory()->conStock()->create()->productoColores->first();

        $this->withSession(['carrito' => [$variante->id => 1]])
            ->post(route('checkout.store'), ['metodo_pago' => 'bitcoin'])
            ->assertSessionHasErrors(['nombre', 'email', 'telefono', 'direccion', 'ciudad', 'metodo_pago']);
    }

    public function test_la_confirmacion_de_un_pedido_ajeno_no_es_visible(): void
    {
        $pedido = Pedido::factory()->create();

        $this->get(route('checkout.confirmacion', $pedido))->assertNotFound();
    }

    public function test_un_cliente_solo_ve_sus_propios_pedidos(): void
    {
        $cliente = User::factory()->create();
        $propio = Pedido::factory()->for($cliente)->create();
        $ajeno = Pedido::factory()->for(User::factory())->create();

        $this->actingAs($cliente)->get(route('pedidos.index'))
            ->assertOk()
            ->assertSee($propio->codigo)
            ->assertDontSee($ajeno->codigo);

        $this->actingAs($cliente)->get(route('pedidos.show', $ajeno))->assertNotFound();
    }
}
