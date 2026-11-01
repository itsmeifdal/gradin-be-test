<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Courier>
 */
class CourierFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->numerify('08##########'),
            'level' => fake()->numberBetween(1, 5),
            'registered_at' => fake()->dateTimeBetween('-2 years'),
        ];
    }
}
