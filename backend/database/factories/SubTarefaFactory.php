<?php

namespace Database\Factories;

use App\Models\Tarefa;
use App\Models\SubTarefa;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubTarefaFactory extends Factory
{
    protected $model = SubTarefa::class;
    
    public function definition()
    {
        return [
            'id_tarefa' => Tarefa::factory(),

            'id_subtarefas' => $this->faker->randomElements([1, 2, 3, 4, 5], 2),
        ];
    }
}
