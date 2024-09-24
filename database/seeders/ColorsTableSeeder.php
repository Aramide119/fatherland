<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            [
                'id'    => 1,
                'name' => 'Black',
            ],
            [
                'id'    => 2,
                'name' => 'Blue',
            ],
            [
                'id'    => 3,
                'name' => 'Yellow',
            ],
            [
                'id'    =>4,
                'name' => 'Ash',
            ],
            [
                'id'    => 5,
                'name' => 'Biege',
            ],
            [
                'id'    => 6,
                'name' => 'White',
            ],
            [
                'id'    => 7,
                'name' => 'Pink',
            ],
            [
                'id'    => 8,
                'name' => 'Purple',
            ],
            [
                'id'    => 9,
                'name' => 'Grey',
            ],
            [
                'id'    => 10,
                'name' => 'Red',
            ],
            [
                'id'    => 11,
                'name' => 'Peach',
            ],
            [
                'id'    => 12,
                'name' => 'Green',
            ],
        ];

        Color::insert($colors);
    }
}
