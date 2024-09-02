<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'username' => 'Lieya',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'notification_token' => null,
                'phone' => '',
                'password' => Hash::make('admin12345'), 
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
