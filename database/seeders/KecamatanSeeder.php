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
            ['kecamatan' => 'Kecamatan Bae'],
            ['kecamatan' => 'Kecamatan Dawe'],
            ['kecamatan' => 'Kecamatan Gebog'],
            ['kecamatan' => 'Kecamatan Jati'],
            ['kecamatan' => 'Kecamatan Jekulo'],
            ['kecamatan' => 'Kecamatan Kaliwungu'],
            ['kecamatan' => 'Kecamatan Kudus'],
            ['kecamatan' => 'Kecamatan Mejobo'],
            ['kecamatan' => 'Kecamatan Undaan'],
        ];

        DB::table('kecamatans')->insert($data);
    }
}
