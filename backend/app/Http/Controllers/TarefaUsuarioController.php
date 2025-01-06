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
        $tarefaUsuario = TarefaUsuario::create($validatedData);

        return $tarefaUsuario;
    }

    public function show($id)
    {
        return TarefaUsuario::findOrFail($id);
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
