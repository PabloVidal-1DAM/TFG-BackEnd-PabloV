<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaPadre extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaPadreFactory> */
    use HasFactory;

    protected $table = 'categoria_padres';
    protected $fillable = ['nombre'];

    // Una categoria padre tiene MUCHAS categorías o más bien subcategorías.
    public function categorias(){
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }
}
