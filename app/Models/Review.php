<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $table = "reviews";
    protected $fillable = [
        'user_id',
        'producto_id',
        'valoracion',
        'comentario'
    ];

    // Una Review es escrita por UN Usuario.
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Una review le pertenece a UN producto.
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
