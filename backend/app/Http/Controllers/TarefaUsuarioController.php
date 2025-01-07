<?php

namespace App\Http\Controllers;

use App\Models\TarefaUsuario;
use Illuminate\Validation\ValidationException;

class TarefaUsuarioController extends Controller
{
    public function index()
    {
        return TarefaUsuario::all();
    }

    public function store(array $data)
    {
        $validatedData = $this->validateStoreData($data);

        // Se o array de colaboradores estiver vazio, substitua por null
        if (empty($validatedData['colaboradores'])) {
            $validatedData['colaboradores'] = null;
        }
    
        $tarefaUsuario = TarefaUsuario::create($validatedData);
    
        return $tarefaUsuario;
    }

    public function show($id)
    {
        return TarefaUsuario::findOrFail($id);
    }

    // Busca todos os registros de colaboradores associados à tarefa
    public function getColaboradoresByTarefa($id_tarefa)
    {
        $tarefaUsuario = TarefaUsuario::where('id_tarefa', $id_tarefa)->first();

        if (!$tarefaUsuario) {
            return response()->json(['error' => 'Tarefa não encontrada'], 404);
        }

        if (empty($tarefaUsuario->colaboradores)) {
            return response()->json(['message' => 'Nenhum colaborador encontrado'], 200);
        }
        
        $colaboradores = \App\Models\User::whereIn('id', $tarefaUsuario->colaboradores)
            ->select('id', 'nome', 'email')
            ->get();

        return response()->json($colaboradores, 200);
    }

    public function update(array $data, $id)
    {
        $tarefaUsuario = TarefaUsuario::findOrFail($id);

        $validatedData = $this->validateStoreData($data);

        $tarefaUsuario->update($validatedData);

        return $tarefaUsuario;
    }

    public function destroy($id)
    {
        TarefaUsuario::destroy($id);

        return true;
    }

    private function validateStoreData(array $data)
    {
        // Validação manual dos dados
        $validator = \Validator::make($data, [
            'id_dono' => 'required|exists:usuarios,id',
            'id_tarefa' => 'required|exists:tarefas,id',
            'colaboradores' => 'nullable|array',
            'colaboradores.*' => 'integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }
}
