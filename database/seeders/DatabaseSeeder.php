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
            // Roles y Permisos antes que nada
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,       // Usuarios (Admin ID 1 + 10 Clientes)

            // 2. Luego el catálogo
            ProveedorSeeder::class,
            CategoriaPadreSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,

            // 3. Finalmente las interacciones (que usarán los usuarios del paso 1)
            PedidoSeeder::class,
            ItemPedidoSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
