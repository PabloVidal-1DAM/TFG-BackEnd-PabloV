<?php

namespace Database\Factories;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Coge un ID al azar. Si la tabla está vacía, la propia fábrica crea uno nuevo.
            'proveedor_id' => Proveedor::inRandomOrder()->value('id') ?? Proveedor::factory(),
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),

            'nombre' => $this->faker->unique()->words(3, true),
            'descripcion' => $this->faker->paragraph(),
            'precio' => $this->faker->randomFloat(2, 10, 5000),
            'stock' => $this->faker->numberBetween(0, 1000),
            'imagen_url' => 'productos/imagenPrueba.jpg',
        ];
    }
}
