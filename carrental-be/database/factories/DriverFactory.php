<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $first_name = $this->faker->firstName($gender);

        return [
            'first_name' => $first_name,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'gender' => $gender,
            'contact_number' => $this->faker->mobileNumber(),
            'birthday' => $this->faker->dateTimeBetween('Y-m-d', '1999-03-20'),
            'profile_img' => $this->faker->image('public\images', 640, 480, null, false, true, $first_name),
            'valid_id' => $this->faker->image('public\images', 800, 600, null, false, false, 'VALID ID'),
        ];
    }
}
