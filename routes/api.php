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
   Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);

// Rutas que si necesitan de autentificación para usarse:
 Route::middleware('auth:sanctum')->group(function () {

     // Gestión del catálogo (Crear, editar, borrar productos)
     Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);

     // El usuario gestiona sus compras y opiniones
     Route::apiResource('pedidos', PedidoController::class);
     Route::apiResource('reviews', ReviewController::class);

     // Panel interno de administración
     Route::apiResource('proveedores', ProveedorController::class);
     Route::apiResource('categoria-padres', CategoriaPadreController::class);

 });
