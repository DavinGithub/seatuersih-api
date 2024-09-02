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
            ['brand' => 'Adidas'],
            ['brand' => 'Nike'],
            ['brand' => 'Converse'],
            ['brand' => 'Puma'],
            ['brand' => 'Skechers'],
        ]);
    }
}
