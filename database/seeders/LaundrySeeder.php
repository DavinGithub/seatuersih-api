<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaundrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('laundries')->insert([
            [
                'id' => 1,
                'name' => 'Regular Clean',
                'description' => 'Proses pembersihan standar untuk sepatu yang meliputi beberapa langkah dasar untuk menghilangkan kotoran, noda, dan bau yang menempel pada sepatu.',
                'price' => '25000', // Menambahkan harga
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Deep Clean',
                'description' => 'Proses pembersihan standar untuk sepatu yang meliputi beberapa langkah dasar untuk menghilangkan kotoran, noda, dan bau yang menempel pada sepatu.',
                'price' => '35000', // Menambahkan harga
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
