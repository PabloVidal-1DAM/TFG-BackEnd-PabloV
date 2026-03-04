<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'producto_id' => Producto::inRandomOrder()->first()->id,
            'valoracion' => $this->faker->numberBetween(1, 5), // el rango de estrellas a poner.
            'comentario' => $this->faker->optional(0.7)->paragraph(), // 70% de probabilidad de que deje texto o no.
        ];
    }
}
