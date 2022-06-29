<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategorie extends Model
{
    use HasFactory;
    protected $table = "subcategories";
    protected $fillable = ['sub_name', 'sub_image','cat_id'];
}
