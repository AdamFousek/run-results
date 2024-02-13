<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Illuminate\Runner>
 */
class RunnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'day' => Hash::make((string)$this->faker->numberBetween(1, 31)),
            'month' => Hash::make((string)$this->faker->numberBetween(1, 12)),
            'year' => $this->faker->numberBetween(1900, date('Y')),
            'city' => $this->faker->city,
            'club' => $this->faker->company,
        ];
    }
}
