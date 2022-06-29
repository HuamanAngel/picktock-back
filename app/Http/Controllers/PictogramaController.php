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

}
