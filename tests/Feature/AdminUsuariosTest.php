<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUsuariosTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::findOrCreate('admin');

        return User::factory()->create()->assignRole('admin');
    }

    public function test_el_admin_ve_los_usuarios(): void
    {
        $cliente = User::factory()->create(['name' => 'Ana Cliente']);

        $this->actingAs($this->admin())
            ->get(route('admin.usuarios.index'))
            ->assertOk()
            ->assertSee($cliente->name);
    }

    public function test_el_admin_da_y_quita_el_rol_de_administrador(): void
    {
        $admin = $this->admin();
        $cliente = User::factory()->create();

        $this->actingAs($admin)->patch(route('admin.usuarios.update', $cliente), ['admin' => 1]);
        $this->assertTrue($cliente->fresh()->hasRole('admin'));

        $this->actingAs($admin)->patch(route('admin.usuarios.update', $cliente), ['admin' => 0]);
        $this->assertFalse($cliente->fresh()->hasRole('admin'));
    }

    public function test_el_admin_no_puede_quitarse_su_propio_rol(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.usuarios.update', $admin), ['admin' => 0])
            ->assertSessionHasErrors('admin');

        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_un_cliente_no_puede_gestionar_usuarios(): void
    {
        $cliente = User::factory()->create();

        $this->actingAs($cliente)
            ->patch(route('admin.usuarios.update', $cliente), ['admin' => 1])
            ->assertForbidden();

        $this->assertFalse($cliente->fresh()->hasRole('admin'));
    }
}
