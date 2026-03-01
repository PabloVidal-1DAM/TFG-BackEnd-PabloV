<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CategoriaPadre extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaPadreFactory> */
    use HasFactory, HasUuids;

    protected $table = 'categoria_padres';
    protected $fillable = ['nombre'];

    // Una categoria padre tiene MUCHAS categorías o más bien subcategorías.
    public function categorias(){
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }
}
