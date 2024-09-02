<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('brands')->insert([
            [
                'id' => 1,
                'kabupaten' => 'Adidas',
            ],
            [
                'id' => 2,
                'kabupaten' => 'Nike',
            ],
            [
                'id' => 3,
                'kabupaten' => 'Converse',
            ],
            [
                'id' => 4,
                'kabupaten' => 'Puma',
            ],
            [
                'id' => 5,
                'kabupaten' => 'Skechers',
            ],
        ]);
    }
}
