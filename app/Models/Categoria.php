<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $table = "categorias";
    protected $fillable = [
        'categoria_padre_id',
        'nombre',
        'descripcion'
    ];

    // Una categoría le pertenece a UNA categoría padre.
    public function categoriaPadre(){
        return $this->belongsTo(CategoriaPadre::class, 'categoria_padre_id');
    }

    // Una categoría tiene MUCHOS productos que la usan.
    public function productos(){
        return $this->belongsToMany(Producto::class, 'categoria_producto', 'categoria_id', 'producto_id');
    }
}
