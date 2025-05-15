<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'difficulty' => $this->faker->randomElement(['Easy', 'Medium', 'Hard']),
            'duration' => $this->faker->numberBetween(10, 120),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'category' => $this->faker->word(),
            'chef_name' => $this->faker->name(),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}