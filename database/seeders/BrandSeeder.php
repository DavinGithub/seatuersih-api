<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            ['brand' => 'Adidas', 'count' => 0],
            ['brand' => 'Nike', 'count' => 0],
            ['brand' => 'Converse', 'count' => 0],
            ['brand' => 'Puma', 'count' => 0],
            ['brand' => 'Skechers', 'count' => 0],
        ]);
    }
}

