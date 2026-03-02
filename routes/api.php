<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaPadreController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas que no requieren autentificación para usarse:

// Catálogo de productos (Solo para consultar: index y show)
   Route::apiResource('productos', ProductoController::class)->only(['index', 'show']);

// Categorías (Para pintar el menú de navegación)
    Route::apiResource('categorias', CategoriaController::class)->parameters([
        'categorias' => 'categoria'  // para que no intente adivinar la variable en singular, ya que me estaba dando errores.
    ]);

    Route::apiResource('reviews', ReviewController::class); // Ya es una palabra inglesa, por lo que no hace falta darle parametro de nombre singular.

// Rutas que si necesitan de autentificación para usarse:
 Route::middleware('auth:sanctum')->group(function () {

     // Gestión del catálogo (Crear, editar, borrar productos)
     Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);

     // El usuario gestiona sus compras y opiniones
     Route::apiResource('pedidos', PedidoController::class);
     // Aquí va reviews una vez dejes de testear.

     // Panel interno de administración
     Route::apiResource('proveedores', ProveedorController::class)->parameters([
         'proveedores' => 'proveedor'
     ]);
     Route::apiResource('categoria-padres', CategoriaPadreController::class)->parameters([
         'categoria-padres' => 'categoriaPadre'
     ]);

 });
