<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pictograma extends Model
{
    // visibility
    // Publico es publico, Privado es privado


    use HasFactory;
    protected $fillable = [
        'pic_title',
        'visibility',
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
    protected $attributes = [
        'pic_pressed_for_kid' => false,
        'pic_calification_count' => 0,
        'pic_average_calification'=> 0,
        'visibility' => 'Privado'
    ];
    public function pictogramaCategorie(){
        return $this->belongsTo( Categorie::class, 'cat_id');
    }
    public function pictogramaUser(){
        return $this->belongsTo( User::class, 'use_id');
    }      
}
