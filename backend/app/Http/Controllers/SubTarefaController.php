<?php

namespace App\Http\Controllers;

use App\Models\SubTarefa;
use Illuminate\Http\Request;

class SubTarefaController extends Controller
{
    public function index()
    {
        return SubTarefa::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tarefa' => 'required|exists:tarefas,id',
            'id_subtarefas' => 'required|array|min:1',
            'id_subtarefas.*' => 'integer',
        ]);

        $subTarefa = SubTarefa::create($validated);

        return response()->json($subTarefa, 201);
    }

    public function show($id)
    {
        return SubTarefa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_tarefa' => 'sometimes|exists:tarefas,id',
            'id_subtarefas' => 'sometimes|array|min:1',
            'id_subtarefas.*' => 'integer',
        ]);

        $subTarefa = SubTarefa::findOrFail($id);
        $subTarefa->update($validated);

        return response()->json($subTarefa);
    }

    public function destroy($id)
    {
        $subTarefa = SubTarefa::findOrFail($id);
        $subTarefa->delete();

        return response()->json(['message' => 'Subtarefa excluída com sucesso'], 200);
    }
}
