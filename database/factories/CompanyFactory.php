<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'email' => $this->faker->companyEmail,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'radius_km' => $this->faker->randomFloat(2, 1, 50),
            'time_in' => $this->faker->time(),
            'time_out' => $this->faker->time(),
        ];
    }
}
