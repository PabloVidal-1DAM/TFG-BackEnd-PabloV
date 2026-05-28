<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductoRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request) // Inyectamos el Request
    {
        // 1. Iniciamos la consulta base con tus relaciones y contadores calcados
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

        // 5. Ejecutamos la paginación de 9 elementos que tenías configurada.
        // El método ->appends() acopla los filtros a los botones del paginador.
        $productos = $query->paginate(9)->appends($request->query());

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

        // CORRECCIÓN: Se le asigna al admin que ha hecho la petición (el que ha iniciado sesión)
        $datosValidados['user_id'] = $request->user()->id;

        // LO QUE FALTABA: Lógica para interceptar y guardar la imagen física
        if ($request->hasFile('imagen')) {
            // Guarda la imagen en storage/app/public/productos y nos devuelve la ruta generada
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            // Metemos esa ruta en el array para que se guarde en la B.D.
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
        // CAMBIO AQUÍ: Añadimos .categoriaPadre a 'categorias'
        $producto->load(['categorias.categoriaPadre', 'proveedor', 'reviews.user']);

        // 2. Calculamos la media de las estrellas (creará el campo reviews_avg_valoracion)
        $producto->loadAvg('reviews', 'valoracion');

        // 3. Calculamos el total de reseñas (creará el campo reviews_count)
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
        // Se actualizan los datos que son más básicos de la tabla productos al ser validados.
        $producto->update($request->validated());

        // En categorias, si los datos enviados incluyen más, se añaden al array junto a las antiguas.
        if($request->has('categorias')){
            $producto->categorias()->sync($request->categorias);
        }

        return response()->json([
            "message" => "Producto actualizado con éxito",
            "data" => $producto->load(["categorias", "proveedor"]),
            "code" => 500
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductoRequest $request, Producto $producto)
    {
        if(!$producto->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el producto.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado el producto correctamente.",
                "code" => 200
            ], 200);
        }
    }

    public function destacados()
    {
        // 1. with('categorias') -> Para pintar las etiquetas verdes en React
        // 2. withCount('itemsPedido') -> Usa el nombre EXACTO de tu función en el modelo
        // 3. orderByDesc('items_pedido_count') -> Laravel convierte "itemsPedido" a "items_pedido_count" automáticamente
        // 4. take(3) -> Nos quedamos con los 3 más vendidos
        // 5. get() -> Obtenemos los resultados (sin paginar)

        $productos = Producto::with('categorias')
            ->withCount('itemsPedido')
            ->orderByDesc('items_pedido_count')
            ->take(3)
            ->get();

        return response()->json($productos);
    }
}
