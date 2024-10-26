<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('PROD-###'),
            'name' => $this->faker->word(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(10),
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['active', 'out_of_stock', 'discontinued']),
            'refrigerated' => $this->faker->boolean(),
        ];
    }
}
