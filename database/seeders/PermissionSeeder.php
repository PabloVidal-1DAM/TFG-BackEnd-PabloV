<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(["name" => "ver-catalogo"]); // Lo hace todo el mundo.
        Permission::create(["name" => "gestionar-catalogo"]); // Añadir, borrar y Eliminar productos, categorías y categorías padre, proveedores... | el Admin.

        Permission::create(["name" => "hacer-pedido"]); // Solo puede | el Cliente.
        Permission::create(["name" => "ver-mis-pedidos"]); // Solo puede | el Cliente.
        Permission::create(["name" => "gestionar-pedido"]); // Ver todos los pedidos, cambiar su estado | El Admin.

        Permission::create(["name" => "crear-review"]); // Para crear una review sobre un producto | lo hace el Cliente.
        Permission::create(["name" => "administrar-review"]); // Para editar campos de esta o borrarla | puede tanto El Cliente como El Admin.

        Permission::create(["name" => "ver-usuario"]);
    }
}
