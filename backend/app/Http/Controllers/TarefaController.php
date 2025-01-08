<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Categoria;
use App\Models\TarefaUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TarefaUsuarioController;

class TarefaController extends Controller
{
    // Listar tarefas
    public function index(Request $request)
    {
        $idsSubtarefas = DB::table('sub_tarefas')->pluck('id_subtarefas');
        $idsSubtarefasArray = $idsSubtarefas->map(function ($json) {
            return json_decode($json); // Decodificando o JSON para array
        })->flatten()->toArray();
        
        $query = Tarefa::whereNotIn('id', $idsSubtarefasArray);

        if ($request->filled('titulo')) {
            $query->where('titulo', 'ILIKE', '%' . $request->titulo . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        $tarefas = $query->with('categoria')->get();
        $tarefas = $query->with('categoria')->paginate(20); // paginação max. 20 tarefas

        $tarefas->each(function ($tarefa) {
            $subtarefas = DB::table('sub_tarefas')
                ->where('id_tarefa', $tarefa->id)
                ->pluck('id_subtarefas')
                ->map(function ($json) {
                    return json_decode($json, true); // Decodificar JSON para array
                })
                ->flatten()
                ->toArray();
        
            $tarefa->subtarefas = Tarefa::whereIn('id', $subtarefas)->get();
        });

        return response()->json($tarefas, 200);
    }

    // Criar nova tarefa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:80',
            'descricao' => 'nullable|string|max:200',
            'data_conclusao' => 'nullable|date',
            'data_prazo' => 'nullable|date',
            'status' => 'required|string|max:30',
            'id_categoria' => 'nullable|integer|exists:categorias,id',
            'colaboradores' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $tarefa = Tarefa::create($validated);

            // Criação da relação na tabela tarefas_usuarios
            $tarefaUsuarioController = new TarefaUsuarioController();
            $tarefaUsuarioController->store([
                'id_dono' => auth()->id(),
                'id_tarefa' => $tarefa->id,
                'colaboradores' => $validated['colaboradores'] ?? null
            ]);
            DB::commit();

            return response()->json($tarefa, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Não foi possível criar a tarefa'], 500);
        }
    }

    // Carregar uma tarefa
    public function show($id)
    {
        $tarefa = Tarefa::with('categoria')
            ->findOrFail($id);

        return response()->json($tarefa, 200);
    }

    // Atualizar tarefa
    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:80',
            'descricao' => 'nullable|string|max:200',
            'data_conclusao' => 'nullable|date',
            'data_prazo' => 'nullable|date',
            'status' => 'nullable|string|max:30',
            'id_categoria' => 'nullable|integer|exists:categorias,id',
            'colaboradores' => 'nullable|array',
        ]);
        DB::beginTransaction();

        try {
            $tarefa->update($validated);
            
            // se alterado os colaboradores então atualiza a tabela relacionada
            if (isset($validated['colaboradores'])) {
                $tarefaUsuario = TarefaUsuario::where('id_tarefa', $tarefa->id)
                                          ->where('id_dono', auth()->id())
                                          ->first();

                if ($tarefaUsuario) {
                    $tarefaUsuarioController = new TarefaUsuarioController();
                    $tarefaUsuarioController->update(
                        ['id' => $tarefaUsuario->id],  // Envia o id do TarefaUsuario
                        ['colaboradores' => $validated['colaboradores']]  // Envia os colaboradores
                    );
                }
            }
            
            DB::commit();
            return response()->json($tarefa, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Não foi possível criar a tarefa'], 500);
        }
    }

    // Excluir tarefa
    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();
        return response()->json(['message' => 'Tarefa excluída excluída com sucesso'], 200);
    }
}
