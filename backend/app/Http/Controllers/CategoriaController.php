<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar categorias
    public function index(Request $request)
    {
        $categorias = Categoria::query();

        if ($request->filled('titulo')) {
            $categorias->where('titulo', 'ILIKE', '%' . $request->titulo . '%');
        }
        
        $categorias = $categorias->get();

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
    public function show($id)
    {
        return Categoria::findOrFail($id);
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
