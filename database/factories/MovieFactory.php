<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(60, 180), // Duration in minutes
            'poster_url' => 'https://placehold.co/400x600?text=Movie+Poster', // Placeholder URL for the movie poster
        ];
    }
}
