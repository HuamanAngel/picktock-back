<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Pictograma;
use App\Models\Subcategorie;
use App\Models\User;
use App\Models\UserCategorie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        // User::create([
        //     'name' => $request->name,
        //     'lastname' => $request->lastname,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'user_nivel_tea' => $request->user_nivel_tea
        // ]);
        // try {
            DB::transaction(function () use ($request) {

                $user = User::create([
                    'name' => $request->name,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'user_nivel_tea' => $request->user_nivel_tea,
                ]);
                // $user->createToken('authToken');
                // visibility : 1 es publico, 2 es privado
                // Categoria
                // Categoria animales
                $newCategory = Categorie::create([
                    'cat_name' => "Animales",
                    'cat_image' => "https://image.freepik.com/vector-gratis/conjunto-coleccion-animales-dibujos-animados_29190-2951.jpg",
                ]);
                UserCategorie::create([
                    'cat_id' => $newCategory->id,
                    'use_id' => $user->id,
                ]);

                // Categorias acciones
                $newCategoryAction = Categorie::create([
                    'cat_name' => "Acciones",
                    'cat_image' => "https://previews.123rf.com/images/watcartoon/watcartoon1601/watcartoon160100056/51975385-vector-de-ni%C3%B1o-lindo-del-personaje-de-dibujos-animados-muchas-acciones-.jpg",
                    'cat_has_subcategory' => true
                ]);
                UserCategorie::create([
                    'cat_id' => $newCategoryAction->id,
                    'use_id' => $user->id,
                ]);
                
                $subcategory = Subcategorie::create([
                    'sub_name' => "Me gusta",
                    'sub_image' => "https://www.creativosonline.org/wp-content/uploads/2019/09/me-gusta.jpg",
                    'cat_id' => $newCategoryAction->id,
                ]);
                
                Subcategorie::create([
                    'sub_name' => "No me gusta",
                    'sub_image' => "https://ep01.epimg.net/verne/imagenes/2015/09/16/articulo/1442393264_464337_1442394018_noticia_normal.jpg",
                    'cat_id' => $newCategoryAction->id,
                ]);
                Subcategorie::create([
                    'sub_name' => "Me siento",
                    'sub_image' => "https://laluzdemiclase.files.wordpress.com/2015/03/a98e77f68c0715e0aacd6655a7434b17.jpg",
                    'cat_id' => $newCategoryAction->id,
                ]);

                // Agrega pictograma por defecto
                // Categoria animales
                Pictograma::create([
                    'pic_title' => "Elefante",
                    'pic_visibility' => "2",
                    'pic_url_image' => "https://previews.123rf.com/images/hermandesign2015/hermandesign20151607/hermandesign2015160700385/60992824-dibujo-animado-lindo-del-elefante-de-la-ilustraci%C3%B3n.jpg",
                    'cat_id' => $newCategory->id,
                    'use_id' => $user->id,
                    'visibility' => 2
                ]);

                Pictograma::create([
                    'pic_title' => "Leon",
                    'pic_visibility' => "2",
                    'pic_url_image' => "https://comodibujar.club/wp-content/uploads/2019/04/leon-kawaii.jpg",
                    'cat_id' => $newCategory->id,
                    'use_id' => $user->id,
                    'visibility' => 2

                ]);

                // Agrega pictogramas a subcategoria de acciones
                Pictograma::create([
                    'pic_title' => "Caminar",
                    'pic_visibility' => "2",
                    'pic_url_image' => "https://image.freepik.com/vector-gratis/dibujos-animados-nino-africano-caminando_61103-294.jpg",
                    'cat_id' => $newCategoryAction->id,
                    'sub_cat_id' => $subcategory->id,
                    'use_id' => $user->id,
                    'visibility' => 2

                ]);
                Pictograma::create([
                    'pic_title' => "Saltar",
                    'pic_visibility' => "2",
                    'pic_url_image' => "https://image.freepik.com/vector-gratis/nina-dibujos-animados-saltando-cuerda_353337-471.jpg",
                    'cat_id' => $newCategoryAction->id,
                    'sub_cat_id' => $subcategory->id,
                    'use_id' => $user->id,
                    'visibility' => 2

                ]);
            });
        // } catch (\Exception $e) {
        //     return response()->json(['error' => "Ocurrio un error en los registros de prueba"], 500);
        // }




        return response()->json([
            'res'=>true,
            'message' => 'Creado correctamente'
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
