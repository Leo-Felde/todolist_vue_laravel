<?php

namespace tests\app\Http\Controller;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class CategoriaControllerTest extends TestCase
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
    #[Covers(\App\Http\Controllers\CategoriaController::class)]
    public function it_can_list_all_categorias()
    {
        // Mock de algumas categorias
        $categorias = Categoria::factory()->count(3)->create();

        // GET para listar as categorias
        $response = $this->getJson('/api/categorias');

        // Verifica se o status da resposta é OK
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se as categorias retornadas correspondem aos dados criados
        $response->assertJsonCount(3);
        foreach ($categorias as $categoria) {
            $response->assertJsonFragment([
                'id' => $categoria->id,
                'titulo' => $categoria->titulo,
                'descricao' => $categoria->descricao,
                'cor' => $categoria->cor,
            ]);
        }
    }

    #[Test]
    #[Covers(\App\Http\Controllers\CategoriaController::class)]
    public function it_can_create_a_categoria()
    {
        // Dados válidos para criação
        $data = [
            'titulo' => 'Nova Categoria',
            'descricao' => 'Descrição da categoria',
            'cor' => '#FFFFFF',
        ];

        // POST para criar a categoria
        $response = $this->postJson('/api/categorias', $data);

        // Verifica se o status da resposta é 201 (Criado)
        $response->assertStatus(Response::HTTP_CREATED);

        // Verifica se os dados retornados estão corretos
        $response->assertJsonFragment([
            'titulo' => 'Nova Categoria',
            'descricao' => 'Descrição da categoria',
            'cor' => '#FFFFFF',
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\CategoriaController::class)]
    public function it_can_show_a_categoria()
    {
        // Mock de uma categoria
        $categoria = Categoria::factory()->create();

        // GET para buscar a categoria
        $response = $this->getJson("/api/categorias/{$categoria->id}");

        // Verifica se o status da resposta é OK
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se os dados retornados estão corretos
        $response->assertJsonFragment([
            'id' => $categoria->id,
            'titulo' => $categoria->titulo,
            'descricao' => $categoria->descricao,
            'cor' => $categoria->cor,
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\CategoriaController::class)]
    public function it_can_update_a_categoria()
    {
        // Mock de uma categoria
        $categoria = Categoria::factory()->create();

        // Dados para atualização
        $data = [
            'titulo' => 'Categoria Atualizada',
            'descricao' => 'Descrição atualizada',
            'cor' => '#000000',
        ];

        // PUT para atualizar a categoria
        $response = $this->putJson("/api/categorias/{$categoria->id}", $data);

        // Verifica se o status da resposta é OK
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se os dados retornados estão corretos
        $response->assertJsonFragment([
            'titulo' => 'Categoria Atualizada',
            'descricao' => 'Descrição atualizada',
            'cor' => '#000000',
        ]);
    }

    #[Test]
    #[Covers(\App\Http\Controllers\CategoriaController::class)]
    public function it_can_delete_a_categoria()
    {
        // Mock de uma categoria
        $categoria = Categoria::factory()->create();

        // DELETE para excluir a categoria
        $response = $this->deleteJson("/api/categorias/{$categoria->id}");

        // Verifica se o status da resposta é OK
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se a mensagem de sucesso está correta
        $response->assertJson([
            'message' => 'Categoria excluída excluída com sucesso',
        ]);

        // Verifica se a categoria foi realmente excluída
        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id,
        ]);
    }
}
