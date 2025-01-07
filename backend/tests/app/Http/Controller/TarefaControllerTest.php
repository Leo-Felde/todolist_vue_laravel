<?php

namespace tests\app\Http\Controller;

use App\Models\Tarefa;
use App\Models\SubTarefa;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class TarefaControllerTest extends TestCase
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
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_list_all_tasks()
    {
        // Criação de algumas tarefas
        $tarefas = Tarefa::factory()->count(3)->create();

        // GET para listar todas as tarefas
        $response = $this->getJson('/api/tarefas');

        // Verifica se a resposta contém as tarefas
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3); // Verifica que existem 3 tarefas na resposta
    }

    // Teste para listar tarefas sem subtarefas com filtros
    #[Test]
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_list_tasks_without_subtasks_and_with_filters()
    {
        // Criação de tarefas
        $tarefas = Tarefa::factory()->count(3)->create();

        // Criação de uma subtarefa associada à primeira tarefa
        $subtarefa = SubTarefa::factory()->create(['id_tarefa' => $tarefas[0]->id]);

        // Requisição GET para listar tarefas sem subtarefas e com filtro de título
        $response = $this->getJson('/api/tarefas?titulo=' . $tarefas[2]->titulo);

        // Verifica se o request não teve erros
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se a subtarefa não foi listada
        $response->assertJsonMissing([
            'id' => $subtarefa->id,
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_show_a_task()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // GET para carregar uma tarefa específica
        $response = $this->getJson("/api/tarefas/{$tarefa->id}");

        // Verifica se a resposta contém a tarefa correta
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $tarefa->id,
            'titulo' => $tarefa->titulo,
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_update_a_task()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // Dados válidos para atualizar a tarefa
        $data = [
            'titulo' => 'Tarefa Atualizada',
            'descricao' => 'Descrição atualizada',
            'status' => 'concluída',
        ];

        // PUT para atualizar a tarefa
        $response = $this->putJson("/api/tarefas/{$tarefa->id}", $data);

        // Verifica se a tarefa foi atualizada corretamente
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $tarefa->id,
            'titulo' => 'Tarefa Atualizada',
            'descricao' => 'Descrição atualizada',
            'status' => 'concluída',
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_delete_a_task()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // DELETE para excluir a tarefa
        $response = $this->deleteJson("/api/tarefas/{$tarefa->id}");

        // Valida se não retornou erro
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Tarefa excluída excluída com sucesso',
        ]);

        // Verifica se a tarefa foi realmente excluída do banco
        $this->assertDatabaseMissing('sub_tarefas', [
            'id' => $tarefa->id,
        ]);

        // Verifica se o valor na tabela de relacionamento foi excluido do banco
        $this->assertDatabaseMissing('tarefas_usuarios', [
            'id_dono' => $this->user->id,
            'id_tarefa' => $tarefa->id,
        ]);
    }
}
