<?php

namespace tests\app\Http\Controller;

use App\Models\SubTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class SubTarefaControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock de autenticação
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user); 
    }

    use RefreshDatabase;
    
    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_can_create_a_sub_tarefa()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // POST para criar uma sub_tarefa
        $response = $this->postJson('/api/sub-tarefas', [
            'id_tarefa' => $tarefa->id,
            'id_subtarefas' => [1, 2, 3],
        ]);

        // Verifica se a subtarefa foi criada corretamente
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'id_tarefa' => $tarefa->id,
            'id_subtarefas' => [1, 2, 3],
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_validates_required_fields_on_create()
    {
        // POST sem os campos obrigatórios
        $response = $this->postJson('/api/sub-tarefas', []);

        // Verifica se retornaram erros de validação
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['id_tarefa', 'id_subtarefas']);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_can_fetch_a_sub_tarefa()
    {
        // Mock de uma sub_tarefa
        $subTarefa = SubTarefa::factory()->create();

        // GET para buscar a sub_tarefa
        $response = $this->getJson("/api/sub-tarefas/{$subTarefa->id}");

        // Verifica se os dados retornados estão corretos
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $subTarefa->id,
            'id_tarefa' => $subTarefa->id_tarefa,
            'id_subtarefas' => $subTarefa->id_subtarefas,
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_can_update_a_sub_tarefa()
    {
        // Mock de uma sub_tarefa
        $subTarefa = SubTarefa::factory()->create();

        // PUT para atualizar os dados
        $response = $this->putJson("/api/sub-tarefas/{$subTarefa->id}", [
            'id_subtarefas' => [4, 5],
        ]);

        // Verifica se a subtarefa foi atualizada corretamente
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $subTarefa->id,
            'id_subtarefas' => [4, 5],
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_can_delete_a_sub_tarefa()
    {
        // Mock de uma sub_tarefa
        $subTarefa = SubTarefa::factory()->create();

        // DELETE para excluir a sub_tarefa
        $response = $this->deleteJson("/api/sub-tarefas/{$subTarefa->id}");

        // Valida se não retornou erro
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Subtarefa excluída com sucesso',
        ]);

        // Verifica se a sub_tarefa foi realmente excluída
        $this->assertDatabaseMissing('sub_tarefas', [
            'id' => $subTarefa->id,
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\SubTarefaController::class)]
    public function it_validates_minimum_integer_array_in_subtarefas()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // POST com um array vazio para `id_subtarefas`
        $response = $this->postJson('/api/sub-tarefas', [
            'id_tarefa' => $tarefa->id,
            'id_subtarefas' => [],
        ]);

        // Valida que o array deve conter pelo menos um inteiro
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['id_subtarefas']);
    }
}
