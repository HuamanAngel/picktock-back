<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = [
            'name' => ['Categorie 1', 'Categorie 2', 'Categorie 3', 'Categorie 4'],
            'images' => [
                'https://thumbs.dreamstime.com/b/dibujo-animado-de-un-hombre-avatar-dise%C3%B1o-vector-sonriente-caricatura-persona-medios-sociales-humanos-y-retrato-tema-ilustraci%C3%B3n-190798222.jpg',
                'https://previews.123rf.com/images/mikhanna/mikhanna1703/mikhanna170300342/73554060-de-dibujos-animados-esboz%C3%B3-objeto-.jpg',
                'https://i.pinimg.com/474x/1a/fe/07/1afe07a18dd147d2c7e3a24d3998f64b--koalas-safari.jpg',
                'https://previews.123rf.com/images/kniaziev/kniaziev1504/kniaziev150400033/39496471-emociones-conjunto-de-expresiones-faciales-de-dibujos-animados-etc-ilustraci%C3%B3n-vectorial.jpg'
            ]
        ];
        for ($i = 0; $i < count($categories['name']); $i++) {
            DB::table('categories')->insert([
                'cat_name' => $categories['name'][$i],
                'cat_image' => $categories['images'][$i],
            ]);
        }
    }
}
