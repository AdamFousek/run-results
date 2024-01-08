<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Race>
 */
class RaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->text,
            'date' => $this->faker->dateTime,
            'location' => $this->faker->city,
            'distance' => $this->faker->randomFloat(10, 0, 100000),
            'surface' => $this->faker->randomElement(['road', 'trail', 'track']),
            'type' => $this->faker->slug,
        ];
    }
}
