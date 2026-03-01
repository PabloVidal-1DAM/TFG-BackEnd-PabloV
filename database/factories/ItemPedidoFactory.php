<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemPedido>
 */
class ItemPedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Se obtiene un producto al azar para copiarle el precio en precio_historico
        $producto = Producto::inRandomOrder()->first() ?? Producto::factory()->create();

        return [
            'pedido_id' => Pedido::inRandomOrder()->value('id') ?? Pedido::factory(),
            'producto_id' => $producto->id,
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio_historico' => $producto->precio, // Congelamos el precio actual del producto en caso de que cambie y las facturas antiguas se vean afectadas.
        ];
    }
}
