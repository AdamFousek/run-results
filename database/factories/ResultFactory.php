<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'starting_number' => $this->faker->numberBetween(1, 1000),
            'position' => $this->faker->numberBetween(1, 1000),
            'time' => $this->faker->time('H:i:s.v', 'now'),
            'category' => $this->faker->randomElement(['M', 'F', 'MV40', 'FV40', 'MV50', 'FV50', 'MV60', 'FV60', 'MV70', 'FV70', 'MV80', 'FV80']),
            'category_position' => $this->faker->numberBetween(1, 100),
            'DNF' => $this->faker->boolean(10),
        ];
    }
}
