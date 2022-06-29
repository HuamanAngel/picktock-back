<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = ['cat_name','cat_image','cat_has_subcategory'];

    public function categoriePictograma()
    {
        return $this->hasMany(Pictograma::class,'cat_id','id');
    }

    public function categorieSubcategorie()
    {
        return $this->hasMany(Subcategorie::class,'cat_id','id');
    }    
}
