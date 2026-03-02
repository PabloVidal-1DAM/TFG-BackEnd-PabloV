<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Se traen todas las categorías junto a la categoría padre que está afiliada y se paginan de 15 en 15.
        $categorias = Categoria::with('categoriaPadre')->paginate(15);
        return response()->json($categorias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        // Se obtienen los datos validados y se usan para crear una nueva categoría en la bd.
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'message' => 'Categoría creada con éxito',
            'data' => $categoria->load('categoriaPadre')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        // Se devuelve, además de la categoria, la categoría padre a la que pertenece la que se rescata.
        return response()->json($categoria->load('categoriaPadre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        // Una vez validados los datos, se actualiza la categoria.
        $categoria->update($request->validated());

        return response()->json([
            'message' => 'Categoría actualizada con éxito',
            'data' => $categoria->load('categoriaPadre')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        if(!$categoria->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar la categoria.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado la categoria correctamente.",
                "code" => 200
            ], 200);
        }
    }
}
