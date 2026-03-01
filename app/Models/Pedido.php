<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory, HasUuids;

    protected $table = "pedidos";
    protected $fillable = [
        'user_id',
        'estado',
        'total'
    ];

    // Un Pedido le pertenece a UN Usuario, el que lo crea.
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un pedido tiene MUCHAS líneas de pedido asociadas de distintos usuarios.
    public function items(){
        return $this->hasMany(ItemPedido::class, 'pedido_id');
    }
}
