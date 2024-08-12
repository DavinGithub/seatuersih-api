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
            ['kecamatan' => 'Kecamatan Bae', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Dawe', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Gebog', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Jati', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Jekulo', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Kaliwungu', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Kudus', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Mejobo', 'kabupaten_id' => 1],
            ['kecamatan' => 'Kecamatan Undaan', 'kabupaten_id' => 1],
        ];

        DB::table('kecamatans')->insert($data);
    }
}
