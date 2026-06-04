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
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = $request->query('per_page', 15);
        // with('roles') es clave para que React sepa si es admin o usuario y poder cambiarlo en la vista de administración.
        $usuarios = User::with('roles')->orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $datosValidados = $request->validated();
        $datosValidados['password'] = Hash::make($request->password);

        // Se crea el usuario
        $usuario = User::create($datosValidados);

        if(!$usuario){
            return response()->json([
                "error" => true,
                "message" => "Error al crear el usuario en la BD."
            ], 500);
        }else{
            // Si el Request trae un 'rol' (lo cual solo es posible si eres admin),
            // se le asigna, si no, se le da el rol 'usuario' por defecto.
            $rol = $request->has('rol') ? $request->rol : 'usuario';
            $usuario->assignRole($rol);

            $token = $usuario->createToken('auth-token')->plainTextToken;

            return response()->json([
                "error" => false,
                "message" => "Usuario creado correctamente.",
                "user" => $usuario,
                "token" => $token
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            // OJO: Hay que ignorar el ID del usuario actual para que le deje guardar su propio email
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:255',
            'rol' => 'required|string|exists:roles,name'
        ]);

        $user->nombre = $request->nombre;
        $user->email = $request->email;
        $user->telefono = $request->telefono;

        // Si el admin escribió una contraseña nueva, la encriptamos y la guardamos
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Actualizo su rol con Spatie Permission
        $user->syncRoles([$request->rol]);

        return response()->json([
            "message" => "Usuario actualizado con éxito",
            "data" => $user->load('roles')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!$user->delete()){
            return response()->json([
                "error" => true,
                "message" => "No se pudo eliminar el usuario."
            ], 500);
        }

        return response()->json([
            "error" => false,
            "message" => "Usuario eliminado correctamente."
        ], 200);
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
                "user" => $usuario,
                "token" => $token,
                "token_type" => "Bearer",
                "rol" => $roles
            ],200);
        }
    }

    public function logout(Request $request){
        // $request->user() obtiene el usuario usando el token que manda React
        $user = $request->user();

        // Elimina ÚNICAMENTE el token de esta sesión/dispositivo
        $user->currentAccessToken()->delete();

        return response()->json([
            "error" => false,
            "message" => "Sesión cerrada correctamente."
        ], 200);
    }
}
