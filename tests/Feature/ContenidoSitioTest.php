<?php

namespace Tests\Feature;

use App\Models\Contenido;
use App\Models\User;
use App\Services\Contenidos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContenidoSitioTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::findOrCreate('admin');

        return User::factory()->create()->assignRole('admin');
    }

    public function test_un_cliente_no_puede_editar_el_contenido(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.paginas.index'))
            ->assertForbidden();
    }

    public function test_el_admin_ve_el_editor_de_cada_pagina(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.paginas.index'))->assertOk()->assertSee('Nosotros');

        foreach (array_keys(config('paginas')) as $pagina) {
            $this->actingAs($admin)->get(route('admin.paginas.edit', $pagina))->assertOk();
        }

        $this->actingAs($admin)->get(route('admin.paginas.edit', 'no-existe'))->assertNotFound();
    }

    public function test_el_admin_cambia_un_texto_y_se_ve_en_la_pagina(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.paginas.update', 'nosotros'), [
                'nosotros__encabezado__titulo' => 'Nuestra historia',
            ])
            ->assertRedirect(route('admin.paginas.edit', 'nosotros'))
            ->assertSessionHasNoErrors();

        $this->get(route('nosotros'))
            ->assertOk()
            ->assertSee('Nuestra historia')
            ->assertDontSee('Nacimos para');
    }

    public function test_dejar_un_campo_vacio_vuelve_al_texto_original(): void
    {
        Contenido::create(['clave' => 'nosotros.encabezado.titulo', 'valor' => 'Nuestra historia']);

        $this->actingAs($this->admin())
            ->put(route('admin.paginas.update', 'nosotros'), ['nosotros__encabezado__titulo' => '']);

        $this->assertDatabaseMissing('contenidos', ['clave' => 'nosotros.encabezado.titulo']);
        $this->get(route('nosotros'))->assertSee('Nacimos para');
    }

    public function test_un_texto_igual_al_original_no_se_guarda(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.paginas.update', 'nosotros'), ['nosotros__encabezado__titulo' => 'Nacimos para']);

        $this->assertDatabaseCount('contenidos', 0);
    }

    public function test_el_admin_sube_y_restaura_una_imagen(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.paginas.update', 'nosotros'), [
            'nosotros__historia__imagen' => UploadedFile::fake()->image('fundadora.jpg'),
        ])->assertSessionHasNoErrors();

        $ruta = Contenido::where('clave', 'nosotros.historia.imagen')->value('valor');
        $this->assertStringStartsWith('storage/contenidos/', $ruta);
        Storage::disk('public')->assertExists(substr($ruta, strlen('storage/')));

        $this->actingAs($admin)->put(route('admin.paginas.update', 'nosotros'), [
            'nosotros__historia__imagen_restaurar' => '1',
        ]);

        $this->assertDatabaseMissing('contenidos', ['clave' => 'nosotros.historia.imagen']);
        Storage::disk('public')->assertMissing(substr($ruta, strlen('storage/')));
    }

    public function test_las_imagenes_se_guardan_con_su_nombre_original_sin_sobrescribir(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->put(route('admin.paginas.update', 'nosotros'), [
            'nosotros__historia__imagen' => UploadedFile::fake()->image('Foto fundadora.jpg'),
            'nosotros__mas_alla__imagen' => UploadedFile::fake()->image('Foto fundadora.jpg'),
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contenidos', ['clave' => 'nosotros.historia.imagen', 'valor' => 'storage/contenidos/Foto fundadora.jpg']);
        $this->assertDatabaseHas('contenidos', ['clave' => 'nosotros.mas_alla.imagen', 'valor' => 'storage/contenidos/Foto fundadora-1.jpg']);
        Storage::disk('public')->assertExists(['contenidos/Foto fundadora.jpg', 'contenidos/Foto fundadora-1.jpg']);
    }

    public function test_solo_se_aceptan_imagenes(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.paginas.update', 'nosotros'), [
                'nosotros__historia__imagen' => UploadedFile::fake()->create('documento.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('nosotros__historia__imagen');
    }

    public function test_la_configuracion_general_cambia_los_datos_de_contacto_y_el_envio(): void
    {
        $this->actingAs($this->admin())->put(route('admin.paginas.update', 'general'), [
            'general__contacto__email' => 'hola@helena.test',
            'general__envios__envio' => '5000',
        ])->assertSessionHasNoErrors();

        // En cada visita real la configuración se aplica al arrancar la aplicación.
        app(Contenidos::class)->aplicarConfiguracion();

        $this->assertSame('hola@helena.test', config('tienda.email'));
        $this->assertEquals(5000, config('tienda.envio'));
        $this->get(route('inicio'))->assertSee('hola@helena.test');
    }
}
