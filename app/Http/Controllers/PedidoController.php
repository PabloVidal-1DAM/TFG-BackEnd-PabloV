<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePedidoRequest;
use App\Http\Requests\ShowPedidoRequest;
use App\Http\Requests\ShowPedidosRequest;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\ItemPedido;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShowPedidosRequest $request)
    {
        // EL request valida si el usuario tiene permiso para hacer la acción o no.
        $user = $request->user();

        // Se traen los Pedidos junto al usuario que lo crea y los productos que tiene dentro.
        $query = \App\Models\Pedido::with(['user', 'items.producto']);

        // // Si el que ha hecho el request es cliente, solo ve SUS pedidos.
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        // Se devuelve el resultado paginado de 15 en 15.
        return response()->json($query->paginate(15));
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
    public function store(StorePedidoRequest $request)
    {
        // Se crea el pedido a coste cero inicialmente.
        $pedido = Pedido::create([
            'user_id' => Auth::user()->id,
            'estado' => 'pendiente',
            'total' => 0
        ]);

        $sumaTotal = 0;

        // 2. Se recorre el array de productos que tenga el pedido para calcular el precio total.
        foreach ($request->items as $item) {
            $producto = Producto::findOrFail($item['producto_id']);

            // Se calcula el coste de esta línea y se suma al total
            $subtotal = $producto->precio * $item['cantidad'];
            $sumaTotal += $subtotal;

            // Se crea la linea de pedido ( es la tabla intermedia ).
            \App\Models\ItemPedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'cantidad' => $item['cantidad'],
                'precio_historico' => $producto->precio
            ]);
        }

        // 3. Se actualiza el pedido con el precio total ya calculado.
        $pedido->update(['total' => $sumaTotal]);

        return response()->json([
            'message' => 'Pedido tramitado de forma segura',
            'data' => $pedido
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowPedidoRequest $request, Pedido $pedido)
    {
        // traemos al usuario, los ítems del pedido, y el producto de cada ítem
        $pedido->load(['user', 'items.producto']);

        return response()->json($pedido);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePedidoRequest $request, Pedido $pedido)
    {
        // La barrera de seguridad: Si ya está entregado, se bloquea la acción.
        if ($pedido->estado === 'entregado') {
            return response()->json([
                'error' => true,
                'message' => 'No se pueden modificar los datos de un pedido que ya ha sido entregado.'
            ], 403);
        }

        // 2. Si pasa la barrera, actualizamos el pedido
        $pedido->update($request->validated());

        return response()->json([
            'message' => 'Pedido actualizado con éxito',
            'data' => $pedido
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( DeletePedidoRequest $request, Pedido $pedido)
    {
        if(!$pedido->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el pedido.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado el pedido correctamente.",
                "code" => 200
            ], 200);
        }
    }
}
