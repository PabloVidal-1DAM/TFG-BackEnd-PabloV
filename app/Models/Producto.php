<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $table = 'productos';
    protected $fillable = [
        'proveedor_id',
        'user_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_url'
    ];

    // Un producto Pertenece a UN proveedor.
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Un Producto le pertenece al usuario admin que lo ha creado.
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un Producto tiene MUCHAS categorías.
    public function categorias(){
        return $this->belongsToMany(Categoria::class, 'categoria_producto', 'producto_id', 'categoria_id');
    }

    // Un Producto tiene MUCHAS reviews de los usuarios.
    public function reviews(){
        return $this->hasMany(Review::class, 'producto_id');
    }

    // Un producto puede estar en MUCHAS líneas de pedidos distintas.
    public function itemsPedido(){
        return $this->hasMany(ItemPedido::class, 'producto_id');
    }
}
