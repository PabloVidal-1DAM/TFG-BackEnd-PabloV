<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;

    protected $table = "proveedors";
    protected $fillable = [
        'nombre',
        'cif',
        'email',
        'telefono',
        'direccion'
    ];

    // Un proveedor provee MUCHOS productos.
    public function productos(){
        return $this->hasMany(Producto::class, 'proveedor_id');
    }
}
