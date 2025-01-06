<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar categorias
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    // Criar nova categoria
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:30',
            'descricao' => 'required|string|max:50',
            'cor' => 'required|string|size:7',
        ]);

        $categoria = Categoria::create($validated);
        return response()->json($categoria, 201);
    }

    // Carregar uma categoria
    public function show(Categoria $categoria)
    {
        return response()->json($categoria);
    }

    // Atualizar categoria
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:30',
            'descricao' => 'sometimes|string|max:50',
            'cor' => 'sometimes|string|size:7',
        ]);

        $categoria->update($validated);
        return response()->json($categoria);
    }

    // Excluir categoria
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->json(['message' => 'Categoria excluída excluída com sucesso'], 200);
    }
}
