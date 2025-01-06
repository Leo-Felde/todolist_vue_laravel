<?php

namespace tests\app\Http\Controller;

use App\Models\Tarefa;
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

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaController::class)]
    public function it_can_create_a_task()
    {
        // Mock de uma categoria
        $categoria = Categoria::factory()->create();

        // Dados válidos para criar uma tarefa
        $data = [
            'titulo' => 'Nova Tarefa',
            'descricao' => 'Descrição da tarefa',
            'status' => 'pendente',
            'id_categoria' => $categoria->id,
        ];

        // POST para criar uma nova tarefa
        $response = $this->postJson('/api/tarefas', $data);

        // Verifica se a tarefa foi criada com sucesso
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'titulo' => 'Nova Tarefa',
            'descricao' => 'Descrição da tarefa',
            'status' => 'pendente',
            'id_categoria' => $categoria->id,
        ]);

        // Verifica se a tabela de relação foi criada corretamente
        $this->assertDatabaseHas('tarefas_usuarios', [
            'id_dono' => $this->user->id,
            'id_tarefa' => $response->json('id'),
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
