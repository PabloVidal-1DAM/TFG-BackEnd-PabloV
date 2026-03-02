<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\User;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Se traen todas las reviews, junto al usuario y el producto que han valorado, y se pagina el resultado de 15 en 15.
        $reviews = Review::with(['user', 'producto'])->paginate(15);
        return response()->json($reviews);
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
    public function store(StoreReviewRequest $request)
    {
        // Se validan los datos pasados.
        $datosValidados = $request->validated();

        // se le asigna al campo del id de usuario el que ha iniciado sesión en ese momento.
        $datosValidados['user_id'] = User::first()->id;

        // Ahora si, con esos datos se crea una review nueva en la BD.
        $review = Review::create($datosValidados);

        return response()->json([
            'message' => 'Review publicada con éxito',
            'data' => $review->load(['user', 'producto'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // Además de las reviews, se muestra el usuario que la ha creado y el producto que reseña.
        return response()->json($review->load(['user', 'producto']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Se validan los datos y se usan para modificar la review.
        $review->update($request->validated());

        return response()->json([
            'message' => 'Review actualizada con éxito',
            'data' => $review->load(['user', 'producto'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if(!$review->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar la review.",
                "code" => 500
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Se ha eliminado la review correctamente.",
                "code" => 200
            ], 200);
        }
    }
}
