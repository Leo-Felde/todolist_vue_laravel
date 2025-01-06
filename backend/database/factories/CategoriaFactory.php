<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    public function definition(): array

    {
        return [
            'titulo' => $this->faker->word,
            'descricao' => $this->faker->word,
            'cor' => $this->faker->hexColor,
        ];
    }
}
