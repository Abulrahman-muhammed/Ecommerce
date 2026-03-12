<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ProductStatusEnum;
use Illuminate\Support\Str;
use App\Models\Category;
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
        $name = $this->faker->unique()->word();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => $this->faker->randomElement(ProductStatusEnum::values()),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'compare_price' => $this->faker->randomFloat(2, 1, 100),
            'is_featured' => $this->faker->boolean(),
            'quantity' => $this->faker->numberBetween(0, 100),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'category_id' => Category::inRandomOrder()->first()?->id, // Set to null by default, can be assigned later
        ];
    }
}
