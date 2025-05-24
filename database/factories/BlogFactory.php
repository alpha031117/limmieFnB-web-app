<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class BlogFactory extends Factory
{
    public function definition()
    {
        // Fix typo: use 'instruction' (change migration too if possible)
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'author_id' => \App\Models\User::factory(),
            'image_url' => 'blog/' . $this->faker->image('storage/app/public/blog', 640, 480, null, false),
        ];
    }
}