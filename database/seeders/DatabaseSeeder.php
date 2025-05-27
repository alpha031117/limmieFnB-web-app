<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Public User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        User::factory()->create([
            'name' => 'Public User',
            'email' => 'user-2@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $this->call(RecipeSeeder::class);
    }
}
