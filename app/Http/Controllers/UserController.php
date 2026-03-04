<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Requests\ShowUsersRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShowUsersRequest $request)
    {
        $usuarios = User::paginate(15);
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $datosValidados = $request->validated();

        // Se encripta la contraseña antes de guardar el usuario en la BD
        $datosValidados['password'] = Hash::make($request->password);

        // Automáticamente al crear un usuario se le añade el rol de usuario base.
        $usuario = User::create($datosValidados)->assignRole('usuario');

        if(!$usuario){
            return response()->json([
                "error" => true,
                "message" => "Error al crear el usuario en la BD."
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Usuario creado correctamente.",
                "data" => $usuario
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowUserRequest $request, User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function verify(LoginUserRequest $request){
        $resultado = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if(!$resultado){
            return response()->json([
                "error" => true,
                "message" => "No se ha podido autenticar al usuario."
            ], 401);
        }else{
            $usuario = Auth::user();
            $token = $usuario->createToken('auth-token')->plainTextToken;
            $roles = $usuario->getRoleNames();
            return response()->json([
                "error" => false,
                "message" => "Usuario autenticado correctamente.",
                "token" => $token,
                "token_type" => "Bearer",
                "rol" => $roles
            ],200);
        }
    }

    public function logout(Request $request){
        // Se obtiene el usuario logeado en la sesión y se elimina su token de sesión.
        $user = Auth::user();
        if(!$user->tokens()->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se ha podido cerrar sesión del usuario."
            ], 500);
        }else{
            return response()->json([
                "error" => false,
                "message" => "Sesión cerrada correctamente."
            ], 200);
        }
    }
}
