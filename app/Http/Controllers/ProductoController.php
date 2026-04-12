<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductoRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with('categorias')
                             ->withAvg('reviews', 'valoracion')
                             ->withCount('reviews')
                             ->paginate(9);
        return response()->json($productos);
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
    public function store(StoreProductoRequest $request)
    {
        // Se obtienen los datos ya validados.
        $datosValidados = $request->validated();

        // Para el campo del id que crea el producto, se le asigna el que ha iniciado sesion.
        $datosValidados['user_id'] = \App\Models\User::first()->id;

        // Se crea el producto en la b.d
        $producto = Producto::create($datosValidados);

        // Se añaden las categorias a la tabla intermediaria (tabla pivote).
        $producto->categorias()->attach($request->categorias);

        return response()->json([
            "message" => "Producto creado con éxito",
            "data" => $producto->load(["categorias", "proveedor"])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        // 1. Añadimos 'reviews.user' para traernos las reseñas y el autor de cada una
        $producto->load(['categorias', 'proveedor', 'reviews.user']);

        // 2. Calculamos la media de las estrellas (creará el campo reviews_avg_valoracion)
        $producto->loadAvg('reviews', 'valoracion');

        // 3. Calculamos el total de reseñas (creará el campo reviews_count)
        $producto->loadCount('reviews');

        return response()->json($producto);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        // Se actualizan los datos que son más básicos de la tabla productos al ser validados.
        $producto->update($request->validated());

        // En categorias, si los datos enviados incluyen más, se añaden al array junto a las antiguas.
        if($request->has('categorias')){
            $producto->categorias()->sync($request->categorias);
        }

        return response()->json([
            "message" => "Producto actualizado con éxito",
            "data" => $producto->load(["categorias", "proveedor"]),
            "code" => 500
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductoRequest $request, Producto $producto)
    {
        if(!$producto->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el producto.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado el producto correctamente.",
                "code" => 200
            ], 200);
        }
    }

    public function destacados()
    {
        // 1. with('categorias') -> Para pintar las etiquetas verdes en React
        // 2. withCount('itemsPedido') -> Usa el nombre EXACTO de tu función en el modelo
        // 3. orderByDesc('items_pedido_count') -> Laravel convierte "itemsPedido" a "items_pedido_count" automáticamente
        // 4. take(3) -> Nos quedamos con los 3 más vendidos
        // 5. get() -> Obtenemos los resultados (sin paginar)

        $productos = Producto::with('categorias')
            ->withCount('itemsPedido')
            ->orderByDesc('items_pedido_count')
            ->take(3)
            ->get();

        return response()->json($productos);
    }
}
