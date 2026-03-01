<?php

namespace Database\Seeders;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Review;
use App\Models\User;
use App\Models\Proveedor;
use App\Models\CategoriaPadre;
use App\Models\Categoria;
use App\Models\Producto;
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
// 1. Crear el pool de Usuarios (1 Admin y 10 Clientes)
        User::factory()->create([
            'nombre' => 'pablo',
            'email' => 'admin@tetrabios.com',
        ]);
        User::factory(10)->create();

        $this->call([
            ProveedorSeeder::class,
            CategoriaPadreSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,
            PedidoSeeder::class,
            ItemPedidoSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
