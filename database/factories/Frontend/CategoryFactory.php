<?php

namespace Database\Factories\Frontend;

use App\Models\Frontend\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Frontend\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'icon' => 'fas fa-folder',
            'image' => null,
            'sort' => fake()->numberBetween(0, 100),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
