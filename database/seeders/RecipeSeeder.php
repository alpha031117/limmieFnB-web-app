<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory()->create([
            'name' => 'Recipe 1',
            'description' => 'Description for recipe 1',
            'difficulty' => 'Easy',
            'duration' => 30,
            'rating' => 4.5,
            'category' => 'Dessert',
            'chef_name' => 'Chef A'
        ]);

        Recipe::factory()->create([
            'name' => 'Recipe 2',
            'description' => 'Description for recipe 2',
            'difficulty' => 'Medium',
            'duration' => 45,
            'rating' => 4.0,
            'category' => 'Main Course',
            'chef_name' => 'Chef B'
        ]);

        Recipe::factory()->create([
            'name' => 'Recipe 3',
            'description' => 'Description for recipe 3',
            'difficulty' => 'Hard',
            'duration' => 60,
            'rating' => 5.0,
            'category' => 'Appetizer',
            'chef_name' => 'Chef C'
        ]);

        Recipe::factory()->create([
            'name' => 'Recipe 4',
            'description' => 'Description for recipe 4',
            'difficulty' => 'Easy',
            'duration' => 20,
            'rating' => 3.5,
            'category' => 'Snack',
            'chef_name' => 'Chef D'
        ]);

        Recipe::factory()->create([
            'name' => 'Recipe 5',
            'description' => 'Description for recipe 5',
            'difficulty' => 'Medium',
            'duration' => 50,
            'rating' => 4.2,
            'category' => 'Beverage',
            'chef_name' => 'Chef E'
        ]);
    }
}
