<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CreateCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
               'title'=>'ポケモン',
               'order'=>0,
            ],
            [
                'title'=>'ワンピース',
                'order'=>1,
             ],
            // [
            //     'title'=>'遊戯王',
            //     'order'=>2,
            // ],
            // [
            //     'title'=>'MTG',
            //     'order'=>3,
            // ],
            // [
            //     'title'=>'その他',
            //     'order'=>4,
            // ],
        ];
        
        foreach ($items as $key => $item) {
            Category::create($item);
        }
    }
}
