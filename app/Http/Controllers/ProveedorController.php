<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProveedorRequest;
use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::paginate(15);
        return response()->json($proveedores);
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
    public function store(StoreProveedorRequest $request)
    {
        // Se usan los datos ya validados para crear un nuevo Proveedor en la base de datos.
        $proveedor = Proveedor::create($request->validated());

        return response()->json([
            'message' => 'Proveedor creado con éxito',
            'data' => $proveedor
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return response()->json($proveedor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedor $proveedor)
    {
        // Si los datos pasados son válidos se actualiza el proveedor.
        $proveedor->update($request->validated());

        return response()->json([
            'message' => 'Proveedor actualizado con éxito',
            'data' => $proveedor
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProveedorRequest $request, Proveedor $proveedor)
    {
        if(!$proveedor->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el proveedor.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado el proveedor correctamente.",
                "code" => 200
            ], 200);
        }
    }
}
