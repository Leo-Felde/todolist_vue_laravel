<?php

namespace App\Http\Controllers;

use App\Models\TarefaUsuario;
use Illuminate\Http\Request;

class TarefaUsuarioController extends Controller
{
    public function index()
    {
        return TarefaUsuario::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dono' => 'required|exists:usuarios,id',
            'id_tarefa' => 'required|exists:tarefas,id',
            'colaboradores' => 'nullable|array',
        ]);

        $tarefaUsuario = TarefaUsuario::create($request->all());

        return response()->json($tarefaUsuario, 201);
    }

    public function show($id)
    {
        return TarefaUsuario::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tarefaUsuario = TarefaUsuario::findOrFail($id);
        $tarefaUsuario->update($request->all());

        return response()->json($tarefaUsuario, 200);
    }

    public function destroy($id)
    {
        TarefaUsuario::destroy($id);

        return response()->json(null, 204);
    }
}
