<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menjalankan seeder untuk laundries
        $this->call(LaundrySeeder::class);

        // Menjalankan seeder untuk kabupatens
        $this->call(KabupatenSeeder::class);

        // Menjalankan seeder untuk kecamatans
        $this->call(KecamatanSeeder::class);
    }
}
