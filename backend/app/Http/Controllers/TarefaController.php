<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    // Listar tarefas
    public function index()
    {
        return Tarefa::all();
    }

    // Criar nova tarefa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:80',
            'descricao' => 'nullable|string|max:200',
            'data_conclusao' => 'nullable|date',
            'status' => 'required|string|max:30',
            'id_categoria' => 'required|integer|exist----------s:categorias,id',
        ]);

        $tarefa = Tarefa::create($validated);
        return response()->json($tarefa, 201);
    }

    // Carregar uma tarefa
    public function show(Tarefa $tarefa)
    {
        return $tarefa;
    }

    // Atualizar tarefa
    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:80',
            'descricao' => 'nullable|string|max:200',
            'data_conclusao' => 'nullable|date',
            'status' => 'nullable|string|max:30',
            'id_categoria' => 'nullable|integer|exists:categorias,id',
        ]);

        $tarefa->update($validated);
        return response()->json($tarefa, 200);
    }

    // Excluir tarefa
    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();
        return response()->json(['message' => 'Tarefa excluída excluída com sucesso'], 200);
    }
}
