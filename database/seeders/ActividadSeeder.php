<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Review;

class ActividadSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = User::role('usuario')->get();
        $productos = Producto::all();

        // Si no hay clientes o productos, no hacemos nada
        if ($clientes->isEmpty() || $productos->isEmpty()) return;

        // 1. GENERAR RESEÑAS
        $comentarios = [
            '¡Excelente calidad! Lo recomiendo totalmente para mi negocio.',
            'Muy buen material, aunque el envío tardó un poco.',
            'Resistente y cumple con la normativa ecológica. 5 estrellas.',
            'Perfecto para nuestros zumos. No gotean nada.',
            'Buen precio y buena atención.'
        ];

        foreach ($productos as $producto) {
            // Cogemos 2 o 3 clientes aleatorios para opinar sobre cada producto
            $clientesOpinan = $clientes->random(rand(2, 3));

            foreach ($clientesOpinan as $cliente) {
                Review::firstOrCreate(
                    ['user_id' => $cliente->id, 'producto_id' => $producto->id],
                    [
                        'valoracion' => rand(3, 5), // Valoraciones realistas (3 a 5 estrellas)
                        'comentario' => $comentarios[array_rand($comentarios)]
                    ]
                );
            }
        }

        // 2. GENERAR PEDIDOS
        foreach ($clientes as $cliente) {
            // Cada cliente hace 1 o 2 pedidos
            $numPedidos = rand(1, 2);

            for ($i = 0; $i < $numPedidos; $i++) {
                $pedido = Pedido::create([
                    'user_id' => $cliente->id,
                    'estado' => collect(['pendiente', 'enviado', 'entregado'])->random(),
                    'total' => 0 // Lo calculamos ahora
                ]);

                // Añadimos entre 1 y 3 líneas de productos distintos al pedido
                $productosPedido = $productos->random(rand(1, 3));
                $totalPedido = 0;

                foreach ($productosPedido as $prod) {
                    $cantidad = rand(1, 5);
                    ItemPedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $prod->id,
                        'cantidad' => $cantidad,
                        'precio_historico' => $prod->precio
                    ]);
                    $totalPedido += ($prod->precio * $cantidad);
                }

                // Actualizamos el total real del pedido
                $pedido->update(['total' => $totalPedido]);
            }
        }
    }
}
