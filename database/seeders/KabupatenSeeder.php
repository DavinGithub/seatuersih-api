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
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
