<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProveedorSeeder::class,
            CategoriaPadreSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,
            PedidoSeeder::class,
            ItemPedidoSeeder::class,
            ReviewSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class
        ]);
    }
}
