<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Kos',
            'email' => 'admin@kosconnect.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);
    }
}
