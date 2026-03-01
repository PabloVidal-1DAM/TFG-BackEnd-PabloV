<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtiene todas las categorías que el Seeder de categoría ha creado
        $categorias = Categoria::all();

        Producto::factory(30)->create()->each(function ($producto) use ($categorias) {
            // Si hay categorías en la base de datos, le asigno entre 1 y 3 al azar
            if ($categorias->isNotEmpty()) {
                $categoriasAleatorias = $categorias->random(rand(1, 3))->pluck('id');
                $producto->categorias()->attach($categoriasAleatorias);
            }
        });
    }
}
