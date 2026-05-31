<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function resumen(Request $request)
    {
        // Seguridad extra: Solo administradores
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Cálculos ultrarrápidos directamente en la Base de Datos
        $totalUsuarios = User::count();
        $totalPedidos = Pedido::count();

        // Asumimos que los pedidos cancelados no suman dinero (si tuvieras ese estado)
        $ingresosTotales = Pedido::sum('total');
        $ticketMedio = Pedido::avg('total') ?? 0;

        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $productosBajoStock = Producto::where('stock', '<', 20)->count(); // Aviso si quedan menos de 20

        // Se Agrupan los pedidos por su estado de entrega para mostrar un gráfico en el frontend.
        $pedidosPorEstado = Pedido::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->pluck('cantidad', 'estado');

        return response()->json([
            'total_usuarios' => $totalUsuarios,
            'total_pedidos' => $totalPedidos,
            'ingresos_totales' => round($ingresosTotales, 2),
            'ticket_medio' => round($ticketMedio, 2),
            'pedidos_pendientes' => $pedidosPendientes,
            'productos_bajo_stock' => $productosBajoStock,
            'grafico_pedidos' => [
                'pendiente' => $pedidosPorEstado['pendiente'] ?? 0,
                'enviado' => $pedidosPorEstado['enviado'] ?? 0,
                'entregado' => $pedidosPorEstado['entregado'] ?? 0,
            ]
        ], 200);
    }
}
