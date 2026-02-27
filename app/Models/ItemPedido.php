<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    /** @use HasFactory<\Database\Factories\ItemPedidoFactory> */
    use HasFactory;

    protected $table = "item_pedidos";
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_historico'
    ];

    // Una línea de pedido le pertenece a un pedido concreto
    public function pedido(){
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    // Una línea de pedido pertenece a UN producto concreto.
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Función que calcula el total de una línea en el carrito de la compra por ejemplo, así no se tiene que rehacer en el frontend.
    public function subTotal(){
        return $this->cantidad * $this->precio_historico;
    }

}
