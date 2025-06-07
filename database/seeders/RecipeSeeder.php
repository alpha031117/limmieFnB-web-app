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
           'name' => 'Creamy Garlic Parmesan Pasta',
            'description' => 'A rich and creamy pasta dish with garlic and parmesan cheese that’s perfect for a quick weeknight dinner or special occasion.',
            'difficulty' => 'Easy',
            'duration' => 30,
            'rating' => 4.80,
            'category' => 'Italian',
            'prep_time' => 10,
            'cook_time' => 20,
            'instruction' => '1. Bring salted water to boil. Cook fettuccine al dente. Reserve pasta water. 2. Melt butter, sauté garlic 1-2 mins. 3. Add heavy cream, simmer 3-4 mins. 4. Whisk in Parmesan, season. 5. Toss pasta with sauce, add reserved water if needed. 6. Garnish and serve.',
            'ingredients' => 'Fettuccine pasta, Butter, Garlic, Heavy cream, Parmesan cheese, Salt, Pepper, Parsley, Red pepper flakes',
            'nutrition' => '550 calories, 30g fat, 40g carbohydrates, 15g protein',
            'servings' => '4',
            'chef_id' => 2, // Assuming you have a user with ID 1
            'image_url' => 'recipes/creamy_garlic_parmesan_pasta.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
