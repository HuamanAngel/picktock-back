<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Pictograma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorieUser = [];
        $categories = auth()->user()->userCategory;
        foreach ($categories as $category) {
            $categorieUser[] = $category->intermediateCategories;
            if($category->intermediateCategories->cat_has_subcategory == 1){
                // Necesario para que se vean las subcategorias
                $category->intermediateCategories->categorieSubcategorie;
            }
        }
        return response()->json(['res' => true, 'quantity' => $categories->count(), 'data' => $categorieUser], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nameCategory' => 'required|string|max:30|min:0',
            'cat_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $image_name = $request->file('cat_image')->getRealPath();
        // Guarda y Obtiene URL de imagen en Cloudinary
        $image_url =  getUrlImage($image_name, 250, 250);
        $newCategory = Categorie::create([
            'cat_name' => $request->nameCategory,
            'cat_image' => $image_url,
        ]);
        auth()->user()->userCategory()->create([
            'cat_id' => $newCategory->id,
        ]);

        return response()->json(['res' => true, 'msg' => "Categoria creada correctamente"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showPictograma($id)
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

        $categorie = auth()->user()->userCategory->where('cat_id', $id)->first();
        if (!$categorie) {
            return response()->json(['res' => false, 'msg' => 'No existe la categoria'], 404);
        };
        $categorie = $categorie->intermediateCategories;
        // En caso de que tenga subcategorias
        if ($categorie->cat_has_subcategory == 1) {
            $categorie = $categorie->categorieSubcategorie;
            $categorieUser = [];
            foreach ($categorie as $category) {
                $categorieUser[$category->sub_name] = Pictograma::where('sub_cat_id', $category->id)->get();
            }
    
            return response()->json(['res' => true, 'hasSubCategory'=>true, 'data' => $categorieUser], 200);
        }
        // En caso de no tener subcategorias
        $categorie = $categorie->categoriePictograma;
        return response()->json(['res' => true, 'hasSubCategory'=>false, 'quantity' => $categorie->count(), 'data' => $categorie], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getCategoriaPictogramaPublic($id){
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
        // Usuario por defecto NO BORRAR NI MODIFICAR
        $user = User::where('email','Sancho@Sancho.Sancho')->first();
        $categorie = $user->userCategory->where('cat_id', $id)->first();
        if (!$categorie) {
            return response()->json(['res' => false, 'msg' => 'No existe la categoria'], 404);
        };
        $categorie = $categorie->intermediateCategories;
        // En caso de que tenga subcategorias
        if ($categorie->cat_has_subcategory == 1) {
            $categorie = $categorie->categorieSubcategorie;
            $categorieUser = [];
            foreach ($categorie as $category) {
                $categorieUser[$category->sub_name] = Pictograma::where('sub_cat_id', $category->id)->get();
            }
    
            return response()->json(['res' => true, 'hasSubCategory'=>true, 'data' => $categorieUser], 200);
        }
        // En caso de no tener subcategorias
        $categorie = $categorie->categoriePictograma;
        return response()->json(['res' => true, 'hasSubCategory'=>false, 'quantity' => $categorie->count(), 'data' => $categorie], 200);

    }

    public function getCategoriesPublic(){
        $categorieUser = [];
        // Usuario por defecto NO BORRAR NI MODIFICAR
        $user = User::where('email','Sancho@Sancho.Sancho')->first();
        $categories = $user->userCategory;
        foreach ($categories as $category) {
            $categorieUser[] = $category->intermediateCategories;
            if($category->intermediateCategories->cat_has_subcategory == 1){
                // Necesario para que se vean las subcategorias
                $category->intermediateCategories->categorieSubcategorie;
            }
        }
        return response()->json(['res' => true, 'quantity' => $categories->count(), 'data' => $categorieUser], 200);        
    }    
}
