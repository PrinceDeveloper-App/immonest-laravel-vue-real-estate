<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'bedrooms' => fake()->numberBetween(1, 7),
            'bathrooms' => fake()->numberBetween(1, 7),
            'area' => fake()->numberBetween(500, 5000),
            'city' => fake()->city(),
            'code' => fake()->postcode(),
            'street' => fake()->streetName(),
            'street_number' => fake()->numberBetween(1, 1000),
            'price' => fake()->numberBetween(50_000, 2_000_000),
            'by_user_id' => User::factory(),
        ];
    }
}
