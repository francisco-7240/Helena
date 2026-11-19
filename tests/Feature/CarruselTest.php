<?php

namespace Tests\Feature;

use App\Models\CarruselImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CarruselTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::findOrCreate('admin');

        return User::factory()->create()->assignRole('admin');
    }

    public function test_la_portada_muestra_las_imagenes_visibles_del_carrusel_en_orden(): void
    {
        CarruselImagen::factory()->create(['imagen' => 'carrusel/segunda.jpg', 'orden' => 2]);
        CarruselImagen::factory()->create(['imagen' => 'carrusel/primera.jpg', 'orden' => 1]);
        CarruselImagen::factory()->oculta()->create(['imagen' => 'carrusel/oculta.jpg']);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSeeInOrder(['carrusel/primera.jpg', 'carrusel/segunda.jpg'])
            ->assertDontSee('carrusel/oculta.jpg')
            ->assertDontSee(contenido('inicio.hero.texto'));
    }

    public function test_un_banner_con_enlace_y_version_para_celular_se_muestra_completo(): void
    {
        CarruselImagen::factory()->create([
            'imagen' => 'carrusel/horizontal.jpg',
            'imagen_movil' => 'carrusel/Banner vertical.jpg',
            'enlace' => '/tienda',
        ]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('href="/tienda"', false)
            ->assertSee('media="(max-width: 767px)" srcset="'.asset('storage/carrusel/Banner%20vertical.jpg').'"', false);
    }

    public function test_sin_imagenes_la_portada_usa_la_imagen_de_contenido(): void
    {
        $this->get(route('inicio'))
            ->assertOk()
            ->assertDontSee('aria-roledescription="carrusel"', false)
            ->assertSee(contenido('inicio.hero.texto'));
    }

    public function test_el_admin_agrega_varias_imagenes_con_su_nombre_original(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.carrusel.store'), [
            'imagenes' => [UploadedFile::fake()->image('Productos.png'), UploadedFile::fake()->image('Shampoo.png')],
        ])->assertRedirect(route('admin.carrusel.index'));

        $imagenes = CarruselImagen::orderBy('orden')->get();
        $this->assertSame(['carrusel/Productos.png', 'carrusel/Shampoo.png'], $imagenes->pluck('imagen')->all());
        $this->assertSame([1, 2], $imagenes->pluck('orden')->all());
        Storage::disk('public')->assertExists(['carrusel/Productos.png', 'carrusel/Shampoo.png']);
    }

    public function test_solo_se_aceptan_imagenes(): void
    {
        $this->actingAs($this->admin())->post(route('admin.carrusel.store'), [
            'imagenes' => [UploadedFile::fake()->create('lista.pdf', 10, 'application/pdf')],
        ])->assertSessionHasErrors('imagenes.0');

        $this->assertDatabaseCount('carrusel_imagenes', 0);
    }

    public function test_el_admin_cambia_el_orden_y_oculta_una_imagen(): void
    {
        $imagen = CarruselImagen::factory()->create(['orden' => 1]);

        $this->actingAs($this->admin())->patch(route('admin.carrusel.update', $imagen), [
            'descripcion' => 'Línea capilar Helena',
            'orden' => 5,
            'activo' => '0',
        ])->assertSessionHasNoErrors();

        $imagen->refresh();
        $this->assertSame(5, $imagen->orden);
        $this->assertFalse($imagen->activo);
        $this->assertSame('Línea capilar Helena', $imagen->descripcion);
    }

    public function test_el_admin_sube_y_quita_la_version_para_celular(): void
    {
        Storage::fake('public');
        $imagen = CarruselImagen::factory()->create(['orden' => 1]);
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.carrusel.update', $imagen), [
            'orden' => 1,
            'activo' => '1',
            'imagen_movil' => UploadedFile::fake()->image('Banner celular.jpg', 1080, 1920),
        ])->assertSessionHasNoErrors();

        $this->assertSame('carrusel/Banner celular.jpg', $imagen->fresh()->imagen_movil);
        Storage::disk('public')->assertExists('carrusel/Banner celular.jpg');

        $this->actingAs($admin)->patch(route('admin.carrusel.update', $imagen), [
            'orden' => 1,
            'activo' => '1',
            'quitar_movil' => '1',
        ]);

        $this->assertNull($imagen->fresh()->imagen_movil);
        Storage::disk('public')->assertMissing('carrusel/Banner celular.jpg');
    }

    public function test_el_enlace_debe_ser_una_ruta_o_una_direccion_web(): void
    {
        $imagen = CarruselImagen::factory()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.carrusel.update', $imagen), ['orden' => 1, 'enlace' => 'javascript:alert(1)'])
            ->assertSessionHasErrors('enlace');

        $this->actingAs($admin)->patch(route('admin.carrusel.update', $imagen), ['orden' => 1, 'enlace' => 'https://helena.test/promo'])
            ->assertSessionHasNoErrors();

        $this->assertSame('https://helena.test/promo', $imagen->fresh()->enlace);
    }

    public function test_el_admin_elimina_una_imagen_y_su_archivo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('carrusel/vieja.jpg', 'contenido');
        $imagen = CarruselImagen::factory()->create(['imagen' => 'carrusel/vieja.jpg']);

        $this->actingAs($this->admin())->delete(route('admin.carrusel.destroy', $imagen));

        $this->assertModelMissing($imagen);
        Storage::disk('public')->assertMissing('carrusel/vieja.jpg');
    }

    public function test_un_cliente_no_puede_gestionar_el_carrusel(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.carrusel.store'), ['imagenes' => [UploadedFile::fake()->image('x.png')]])
            ->assertForbidden();
    }
}
