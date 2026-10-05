<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::findOrCreate('admin');

        return User::factory()->create()->assignRole('admin');
    }

    public function test_un_cliente_no_puede_entrar_al_panel(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_un_invitado_es_redirigido_al_login(): void
    {
        $this->get(route('admin.productos.index'))->assertRedirect(route('login'));
    }

    public function test_el_admin_ve_las_pantallas_del_panel(): void
    {
        $admin = $this->admin();
        $producto = Producto::factory()->conStock()->create();
        $pedido = Pedido::factory()->create();

        foreach ([
            route('admin.dashboard'),
            route('admin.productos.index'),
            route('admin.productos.create'),
            route('admin.productos.edit', $producto),
            route('admin.categorias.index'),
            route('admin.marcas.index'),
            route('admin.colores.index'),
            route('admin.pedidos.index'),
            route('admin.pedidos.show', $pedido),
            route('admin.contactos.index'),
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_el_admin_crea_un_producto_con_variantes_e_imagenes(): void
    {
        Storage::fake('public');
        $categoria = Categoria::factory()->create();
        [$negro, $rosa] = Color::factory()->count(2)->create();

        $this->actingAs($this->admin())->post(route('admin.productos.store'), [
            'nombre' => 'Bolso Helena',
            'categoria_id' => $categoria->id,
            'precio' => '99.90',
            'estado' => '1',
            'variantes' => [
                ['color_id' => $negro->id, 'stock' => 4],
                ['color_id' => $rosa->id, 'stock' => 2],
            ],
            'imagenes' => [UploadedFile::fake()->image('bolso.jpg')],
        ])->assertSessionHasNoErrors();

        $producto = Producto::sole();
        $this->assertSame('bolso-helena', $producto->slug);
        $this->assertSame(6, $producto->stockTotal());
        $this->assertTrue($producto->imagenes->sole()->es_portada);
        Storage::disk('public')->assertExists($producto->imagenes->sole()->imagen);
    }

    public function test_actualizar_un_producto_sincroniza_las_variantes(): void
    {
        $producto = Producto::factory()->create();
        [$negro, $rosa] = Color::factory()->count(2)->create();
        $producto->productoColores()->create(['color_id' => $negro->id, 'stock' => 5]);

        $this->actingAs($this->admin())->put(route('admin.productos.update', $producto), [
            'nombre' => $producto->nombre,
            'slug' => $producto->slug,
            'categoria_id' => $producto->categoria_id,
            'precio' => 50,
            'variantes' => [['color_id' => $rosa->id, 'stock' => 7]],
        ])->assertSessionHasNoErrors();

        $variantes = $producto->productoColores()->get();
        $this->assertCount(1, $variantes);
        $this->assertSame($rosa->id, $variantes->first()->color_id);
        $this->assertSame(7, $variantes->first()->stock);
    }

    public function test_el_precio_de_oferta_debe_ser_menor_al_precio(): void
    {
        $this->actingAs($this->admin())->post(route('admin.productos.store'), [
            'nombre' => 'Bolso',
            'categoria_id' => Categoria::factory()->create()->id,
            'precio' => 50,
            'precio_oferta' => 60,
            'variantes' => [['color_id' => null, 'stock' => 1]],
        ])->assertSessionHasErrors('precio_oferta');
    }

    public function test_cancelar_un_pedido_devuelve_el_stock(): void
    {
        $variante = Producto::factory()->conStock(2)->create()->productoColores->first();
        $pedido = Pedido::factory()->create();
        $pedido->items()->create([
            'producto_id' => $variante->producto_id,
            'producto_color_id' => $variante->id,
            'nombre_producto' => 'Bolso',
            'precio' => 10,
            'cantidad' => 3,
            'subtotal' => 30,
        ]);

        $this->actingAs($this->admin())
            ->patch(route('admin.pedidos.update', $pedido), ['estado' => 'cancelado'])
            ->assertRedirect(route('admin.pedidos.show', $pedido));

        $this->assertSame('cancelado', $pedido->fresh()->estado);
        $this->assertSame(5, $variante->fresh()->stock);
    }

    public function test_no_se_elimina_una_categoria_con_productos(): void
    {
        $categoria = Producto::factory()->create()->categoria;

        $this->actingAs($this->admin())
            ->delete(route('admin.categorias.destroy', $categoria))
            ->assertSessionHasErrors('categoria');

        $this->assertModelExists($categoria);
    }
}
