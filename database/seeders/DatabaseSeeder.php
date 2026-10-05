<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('cliente');

        $admin = User::firstOrCreate(
            ['email' => 'admin@helena.test'],
            ['name' => 'Administrador Helena', 'password' => 'password', 'email_verified_at' => now()],
        );
        $admin->assignRole('admin');

        $cliente = User::firstOrCreate(
            ['email' => 'cliente@helena.test'],
            ['name' => 'Cliente Helena', 'password' => 'password', 'email_verified_at' => now()],
        );
        $cliente->assignRole('cliente');

        $this->call(CatalogoSeeder::class);
    }
}
