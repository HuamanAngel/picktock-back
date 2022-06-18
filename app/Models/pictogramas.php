<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pictogramas extends Model
{
    use HasFactory;
    protected $fillable = [
        'pic_title',
        'pic_visibility',
        'pic_url_image',
        'use_id',
        'cat_id',
        'sub_cat_id',
        'pic_pressed_for_kid',
        'pic_pressed_date',
        'pic_average_calification',
        'pic_calification_count',
        'pic_sonido',
        'pic_sonido_public_id'
    ];

    public function pictogramaCategorie(){
        return $this->belongsTo( Categorie::class, 'cat_id');
    }
    public function pictogramaUser(){
        return $this->belongsTo( User::class, 'use_id');
    }    
}
