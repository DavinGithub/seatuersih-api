<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kabupatens')->insert([
            [
                'id' => 1,
                'kabupaten' => 'Kudus',
                'laundry_id' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'kabupaten' => 'Kudus',
                'laundry_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
