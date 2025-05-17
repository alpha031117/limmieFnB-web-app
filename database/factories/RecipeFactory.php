<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition()
    {
        // Fix typo: use 'instruction' (change migration too if possible)
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'difficulty' => $this->faker->randomElement(['Easy', 'Medium', 'Hard']),
            'duration' => $this->faker->numberBetween(10, 120), // in minutes
            'rating' => $this->faker->randomFloat(2, 3, 5),
            'category' => $this->faker->randomElement(['Italian', 'Main Course', 'Appetizer', 'Snack', 'Beverage']),
            'prep_time' => $this->faker->numberBetween(5, 30),
            'cook_time' => $this->faker->numberBetween(5, 90),
            'instruction' => $this->faker->paragraphs(3, true),
            'ingredients' => implode(', ', $this->faker->words(8)),
            'nutrition' => $this->faker->randomElement([
                '550 calories, 30g fat, 40g carbohydrates, 15g protein',
                '400 calories, 20g fat, 50g carbohydrates, 10g protein',
                '600 calories, 35g fat, 45g carbohydrates, 20g protein',
            ]),
            'servings' => $this->faker->numberBetween(1, 8),
            'chef_id' => \App\Models\User::factory(),
            'image_url' => 'recipes/' . $this->faker->image('storage/app/public/recipes', 640, 480, null, false),
        ];
    }
}