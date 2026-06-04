<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductoRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // 1. Se Inicia la consulta base con las relaciones y contadores para las reviews
        $query = Producto::with('categorias')
            ->withAvg('reviews', 'valoracion')
            ->withCount('reviews');

        // 2. Filtro de búsqueda por Nombre
        $query->when($request->query('buscar'), function ($q, $buscar) {
            $q->where('nombre', 'like', '%' . $buscar . '%');
        });

        // 3. Filtro por Categoría
        // (Entra en la tabla intermedia/pivote si se recibe el ID de categoría)
        $query->when($request->query('categoria'), function ($q, $categoria) {
            $q->whereHas('categorias', function ($q2) use ($categoria) {
                $q2->where('categorias.id', $categoria);
            });
        });

        // 4. Lógica de Ordenación Dinámica
        $orden = $request->query('orden');
        if ($orden === 'precio_asc') {
            $query->orderBy('precio', 'asc');
        } elseif ($orden === 'precio_desc') {
            $query->orderBy('precio', 'desc');
        } elseif ($orden === 'nombre_asc') {
            $query->orderBy('nombre', 'asc');
        } elseif ($orden === 'nombre_desc') {
            $query->orderBy('nombre', 'desc');
        } else {
            // Por defecto, u orden de inserción si no viene parámetro
            $query->orderBy('created_at', 'desc');
        }

        // Ejecutamos la paginación de 9 elementos que tenías configurada.
        // El método ->appends() acopla los filtros a los botones del paginador.
        $perPage = $request->query('per_page', 9);

        $productos = $query->paginate($perPage)->appends($request->query());

        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        // Se obtienen los datos ya validados.
        $datosValidados = $request->validated();

        // Se le asigna al admin que ha hecho la petición (el que ha iniciado sesión)
        $datosValidados['user_id'] = $request->user()->id;

        // Lógica para interceptar y guardar la imagen física en el storage
        if ($request->hasFile('imagen')) {
            // Guarda la imagen en storage/app/public/productos y devuelve la ruta generada
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            // Finalmente se usa el nombre de la ruta para que se guarde en la B.D y la referencia en el frontend correctamente.
            $datosValidados['imagen_url'] = $rutaImagen;
        }

        // Se crea el producto en la b.d
        $producto = Producto::create($datosValidados);

        // Se añaden las categorias a la tabla intermediaria (tabla pivote).
        $producto->categorias()->attach($request->categorias);

        return response()->json([
            "message" => "Producto creado con éxito",
            "data" => $producto->load(["categorias", "proveedor"])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        // Añado.categoriaPadre a 'categorias'
        $producto->load(['categorias.categoriaPadre', 'proveedor', 'reviews.user']);

        // Se calcula la media de las estrellas (creará el campo reviews_avg_valoracion)
        $producto->loadAvg('reviews', 'valoracion');

        // Calcula  el total de reseñas (creará el campo reviews_count)
        $producto->loadCount('reviews');

        return response()->json($producto);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        // Se cogen los datos de la petición y se validan
        $datosValidados = $request->validated();

        // Comprueba si el frontend ha enviado una nueva imagen física
        if ($request->hasFile('imagen')) {

            // Se borra la imagen antigua del servidor para no acumular basura
            if ($producto->imagen_url) {
                Storage::disk('public')->delete($producto->imagen_url);
            }

            // Guarda la nueva imagen en storage/app/public/productos
            $rutaImagen = $request->file('imagen')->store('productos', 'public');

            // Se le dice a Laravel que guarde esa ruta generada en la columna de la BD para ese producto
            $datosValidados['imagen_url'] = $rutaImagen;
        }

        // Se actualizan los datos del producto (incluyendo la nueva ruta de la imagen si se subió)
        $producto->update($datosValidados);

        // Se sincronizan las categorías
        if($request->has('categorias')){
            $producto->categorias()->sync($request->categorias);
        }

        return response()->json([
            "message" => "Producto actualizado con éxito",
            "data" => $producto->load(["categorias", "proveedor"]),
            "code" => 200
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductoRequest $request, Producto $producto)
    {
        // Se guarda la ruta de la imagen ANTES de borrar el producto
        $rutaImagen = $producto->imagen_url;

        // Luego, se intenta borrar el producto de la base de datos
        if(!$producto->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el producto.",
                "code" => 500
            ], 500);
        }else{
            // Si se borró bien el producto de la BD y tenía una imagen, la borramos del storage de laravel
            if ($rutaImagen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rutaImagen);
            }

            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado el producto y su imagen correctamente.",
                "code" => 200
            ], 200);
        }
    }

    public function destacados()
    {
        // 1. with('categorias') -> Para pintar las etiquetas verdes
        // 2. withAvg y withCount -> Para que las estrellas funcionen igual que en el catálogo
        // 3. withCount('itemsPedido') -> Para saber lo más vendido
        // 4. orderByDesc y take(3) -> Ordenamos y nos quedamos con el Top 3

        $productos = Producto::with('categorias')
            ->withAvg('reviews', 'valoracion')
            ->withCount('reviews')
            ->withCount('itemsPedido')
            ->orderByDesc('items_pedido_count')
            ->take(3)
            ->get();

        return response()->json($productos);
    }
}
