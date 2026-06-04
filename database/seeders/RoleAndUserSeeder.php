<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Permisos
        $permisos = ['gestionar-catalogo', 'ver-catalogo', 'hacer-pedido', 'ver-mis-pedidos', 'ver-usuario', 'crear-review', 'administrar-review'];
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Roles
        $rolAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolUsuario = Role::firstOrCreate(['name' => 'usuario']);

        $rolAdmin->givePermissionTo(Permission::all());
        $rolUsuario->givePermissionTo(['ver-catalogo', 'hacer-pedido', 'ver-mis-pedidos', 'ver-usuario', 'crear-review', 'administrar-review']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@tetrabios.com'],
            ['nombre' => 'Administrador TetraBIOS', 'password' => Hash::make('12345678A@'), 'telefono' => '600123456']
        );
        $admin->assignRole($rolAdmin);

        // Clientes falsos
        $nombresClientes = ['EcoCliente Prueba', 'María Sostenible', 'Carlos Recicla', 'BioTienda Madrid', 'Laura Green'];

        foreach ($nombresClientes as $index => $nombre) {
            $cliente = User::firstOrCreate(
                ['email' => "cliente{$index}@gmail.com"],
                ['nombre' => $nombre, 'password' => Hash::make('12345678A@')]
            );
            $cliente->assignRole($rolUsuario);
        }
    }
}
