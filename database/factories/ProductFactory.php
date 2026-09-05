<?php

namespace Database\Factories;

use App\Enums\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'name' => fake()->words(2, true),
            'category' => fake()->randomElement(ProductCategory::cases())->value,
            'description' => fake()->sentence(),
            'base_price' => fake()->numberBetween(5000, 100000),
            'min_size_m2' => 0.25,
            'unit_label' => 'm2',
            'is_custom_dimension' => false,
            'requires_design_file' => false,
        ];
    }
}
