<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pictograma;
use App\Models\PictogramaPublic as ModelsPictogramaPublic;
use App\Models\User;
use App\Models\Favorite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;
use PictogramaPublic;


class PictogramaController extends Controller
{
    //
    public function index()
    {
        $pictogramas = auth()->user()->userPictograma;
        return response()->json(['res' => true, 'quantity' => $pictogramas->count(), 'data' => $pictogramas], 200);
    }

    public function store(Request $request)
    {
        // pic_visibility : 1 es publico, 2 es privado
        $request->validate([
            'pic_title' => 'required|string|max:30|min:2',
            'pic_visibility' => 'required|integer|between:1,2',
            'pic_image' => 'required|image|mimes:jpeg,png,jpg,jfif|max:2048',
            'cat_id' => 'required|integer|min:0|exists:categories,id',
        ]);

        $image_name = $request->file('pic_image')->getRealPath();
        // Guarda y Obtiene URL de imagen en Cloudinary
        $image_url =  getUrlImage($image_name,250, 250);

        $categoria = auth()->user()->userCategory;
        // $categoria = $categoria->intermediateCategories;
        $categoria = $categoria->where('cat_id', $request->cat_id)->first();
        $categoria = $categoria->intermediateCategories;
        // Valor por defecto
        if($categoria->cat_has_subcategory){
            // $subcategoria = $categoria->categorieSubcategorie;
            if($request->sub_cat_id == null){                
                return response()->json(['res' => false, 'message' => 'Debe seleccionar una subcategoria'], 422);                                
            }else{
                auth()->user()->userPictograma()->create([
                    'pic_title' => $request->pic_title,
                    'pic_visibility' => $request->pic_visibility,
                    'pic_url_image' => $image_url,
                    'cat_id' => $request->cat_id,
                    'sub_cat_id'=> $request->sub_cat_id,
                ]);
            }    
            return response()->json(['res' => true,'msg'=>"Se creo exitosamente"], 201);
        }
        auth()->user()->userPictograma()->create([
            'pic_title' => $request->pic_title,
            'pic_visibility' => $request->pic_visibility,
            'pic_url_image' => $image_url,
            'cat_id' => $request->cat_id,
        ]);
        return response()->json(['res' => true,'msg'=>"Se creo exitosamente"], 201);

    }


    public function show($id)
    {
        $array = [
            'id' => $id,
        ];
        $fieldCreate = [
            'id' => 'required|integer|min:0',
        ];
        
        $validations = Validator::make($array, $fieldCreate);
        if ($validations->fails()) {
            return response()->json(['res' => false, 'errors' => $validations->errors()], 422);
        }
        $pictogramas = auth()->user()->userPictograma;
        if ($pictogramas->count() > 0) {
            $pictograma = $pictogramas->where('id', $id)->first();
            if (isset($pictograma)) {
                return response()->json(['res' => true, 'data' => $pictograma], 200);
            } else {
                return response()->json(['res' => false, 'data' => 'No se encontro el pictograma'], 200);
            }
        } else {
            return response()->json(['res' => false, 'msg' => 'No tienes ningun pictograma'], 200);
        }
    }    
}
