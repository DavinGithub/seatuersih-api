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
        $this->call(LaundrySeeder::class);

        $this->call(KabupatenSeeder::class);

        $this->call(KecamatanSeeder::class);

        $this->call(BrandSeeder::class);

        $this->call(AdminSeeder::class);
    }
}
