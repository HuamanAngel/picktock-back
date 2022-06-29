<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategorie extends Model
{
    use HasFactory;
    protected $table = 'user_categories';
    
    protected $fillable = [
        'use_id', 'cat_id'
    ];

    public function intermediateCategories()
    {
        return $this->belongsTo(Categorie::class,'cat_id');
    }   
}
