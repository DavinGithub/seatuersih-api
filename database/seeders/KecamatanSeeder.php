<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Data untuk laundry_id 1
            ['kecamatan' => 'Kecamatan Bae', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Dawe', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Gebog', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Jati', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Jekulo', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Kaliwungu', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Kudus', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Mejobo', 'laundry_id' => 1],
            ['kecamatan' => 'Kecamatan Undaan', 'laundry_id' => 1],

            // Data untuk laundry_id 2
            ['kecamatan' => 'Kecamatan Bae', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Dawe', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Gebog', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Jati', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Jekulo', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Kaliwungu', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Kudus', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Mejobo', 'laundry_id' => 2],
            ['kecamatan' => 'Kecamatan Undaan', 'laundry_id' => 2],
        ];

        DB::table('kecamatans')->insert($data);
    }
}
