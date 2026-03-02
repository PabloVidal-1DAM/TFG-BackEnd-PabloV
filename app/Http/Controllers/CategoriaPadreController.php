<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaPadreRequest;
use App\Http\Requests\UpdateCategoriaPadreRequest;
use App\Models\CategoriaPadre;

class CategoriaPadreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = CategoriaPadre::paginate(15);
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
    public function store(StoreCategoriaPadreRequest $request)
    {
        // Se obtienen los datos ya validados y se usan para crear una nueva categoría padre.
        $categoriaPadre = CategoriaPadre::create($request->validated());

        return response()->json([
            'message' => 'Categoría Padre creada con éxito',
            'data' => $categoriaPadre
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaPadre $categoriaPadre)
    {
        return response()->json($categoriaPadre);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaPadre $categoriaPadre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaPadreRequest $request, CategoriaPadre $categoriaPadre)
    {
        // Siempre y cuando los datos sean válidos se actualiza la categoría padre.
        $categoriaPadre->update($request->validated());

        return response()->json([
            'message' => 'Categoría Padre actualizada con éxito',
            'data' => $categoriaPadre
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaPadre $categoriaPadre)
    {
        if(!$categoriaPadre->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar la categoría padre.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado la categoría padre correctamente.",
                "code" => 200
            ], 200);
        }
    }
}
