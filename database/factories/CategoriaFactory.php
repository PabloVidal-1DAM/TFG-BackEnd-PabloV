<?php

namespace Database\Factories;

use App\Models\CategoriaPadre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_padre_id' => CategoriaPadre::inRandomOrder()->value('id') ?? CategoriaPadre::factory(),
            'nombre' => $this->faker->unique()->words(2, true),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
