<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'user_nivel_tea' => 'required|numeric',
        ]);
        
        User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_nivel_tea' => $request->user_nivel_tea
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::guard('web')->attempt($credentials)) {

            return response()->json([
                'res' => false,
                'message' => 'Correo o contraseña erroneos'
            ], 401);
        }
        $user = Auth::guard('web')->user();
        // Elimina un token si ya existe, mantiene la cantidad de token en 4
        if (isset($user->tokens) && $user->tokens->count() > 3) {
            $user->tokens[0]->delete();
        }
        // Borrar todos los tokens
        // $user->tokens->each(function($tokena, $key) {
        //     $tokena->delete();
        // });

        $token = $user->createToken('authToken');

        return response()->json([
            'res' => true,
            'message' => 'Login exitoso',
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'expired' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
            'data' => $user,
        ], 200);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}
