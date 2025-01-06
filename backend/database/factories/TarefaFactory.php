<?php

namespace Database\Factories;

use App\Models\Tarefa;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    protected $model = Tarefa::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->word,
            'descricao' => $this->faker->sentence,
            'status' => $this->faker->word,
        ];
    }
}
