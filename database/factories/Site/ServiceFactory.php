<?php

namespace Database\Factories\Site;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'name' => fake()->name(),
            'summary' => fake()->text(100),
            'body' => fake()->text(500),
            'sort' => fake()->numberBetween(1, 100),
        ];
    }
}
