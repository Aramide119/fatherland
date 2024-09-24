<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            [
                'id'    => 1,
                'name' => 'S',
            ],
            [
                'id'    => 2,
                'name' => 'M',
            ],
            [
                'id'    => 3,
                'name' => 'L',
            ],
            [
                'id'    =>4,
                'name' => 'XL',
            ],
            [
                'id'    => 5,
                'name' => 'XXL',
            ],
            [
                'id'    => 6,
                'name' => 'XXXL',
            ],
        ];

        Size::insert($sizes);
    }
}
