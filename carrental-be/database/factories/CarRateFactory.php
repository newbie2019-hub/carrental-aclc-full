<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarRate>
 */
class CarRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'per_day' => $this->faker->numberBetween(699, 3500),
            'per_week' => $this->faker->numberBetween(999, 16000),
            'per_month' => $this->faker->numberBetween(29999, 60000),
            'with_driver' => $this->faker->numberBetween(499, 3000),
        ];
    }
}
