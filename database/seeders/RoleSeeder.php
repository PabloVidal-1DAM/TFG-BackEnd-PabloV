<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Los roles que existirán en mi app por ahora son Admin y Usuario.
        $rolAdmin = Role::create(['name' => 'admin']);
        $rolUsuario = Role::create(['name' => 'usuario']);

        // El Admin lo puede hacer todo
        $rolAdmin->givePermissionTo(Permission::all());

        // Y el Usuario tiene permisos limitados
        $rolUsuario->givePermissionTo([
            'ver-catalogo',
            'hacer-pedido',
            'ver-mis-pedidos',
            'ver-usuario',
            'crear-review',
            'administrar-review' // Puede editar sus propias reviews solo.
        ]);
    }
}
