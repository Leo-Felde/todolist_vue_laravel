<?php

namespace tests\app\Http\Controller;

use App\Models\Tarefa;
use App\Models\TarefaUsuario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class TarefaUsuarioControllerTest extends TestCase
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
    #[Covers(\App\Http\Controllers\TarefaUsuarioController::class)]
    public function test_get_colaboradores_by_tarefa_returns_404_when_tarefa_not_found()
    {
        // Tenta acessar uma tarefa inexistente
        $response = $this->getJson('/api/tarefas/9999/colaboradores');

        // Verifica se retorna um erro 404
        $response->assertStatus(404)
                 ->assertJson(['error' => 'Tarefa não encontrada']);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaUsuarioController::class)]
    public function test_get_colaboradores_by_tarefa_returns_message_when_no_colaboradores()
    {
        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // Cria uma tarefa sem colaboradores
        $tarefaUsuario = TarefaUsuario::create([
            'id_tarefa' => $tarefa->id,
            'id_dono' => $this->user->id,
        ]);

        // Acessa a tarefa para buscar colaboradores
        $response = $this->getJson("/api/tarefas/{$tarefaUsuario->id_tarefa}/colaboradores");

        // Verifica se a resposta contém a mensagem de "nenhum colaborador encontrado"
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Nenhum colaborador encontrado']);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\TarefaUsuarioController::class)]
    public function test_get_colaboradores_by_tarefa_returns_colaboradores_when_found()
    {
        // Mock de usuários
        $usuario1 = User::create(['nome' => 'João Silva', 'email' => 'joao@example.com', 'senha' => 'senhadeteste123']);
        $usuario2 = User::create(['nome' => 'Maria Oliveira', 'email' => 'maria@example.com', 'senha' => 'senhadeteste123']);

        // Mock de uma tarefa
        $tarefa = Tarefa::factory()->create();

        // Cria uma tarefa com colaboradores
        $tarefaUsuario = TarefaUsuario::create([
            'id_tarefa' => $tarefa->id,
            'id_dono' => $this->user->id,
            'colaboradores' => [$usuario1->id, $usuario2->id]
        ]);

        // Acessa a tarefa para buscar colaboradores
        $response = $this->getJson("/api/tarefas/{$tarefa->id}/colaboradores");

        // Verifica se os colaboradores são retornados corretamente
        $response->assertStatus(200)
                 ->assertJsonCount(2) // Verifica que dois colaboradores foram retornados
                 ->assertJsonFragment(['nome' => 'João Silva'])
                 ->assertJsonFragment(['nome' => 'Maria Oliveira']);
    }
}
