<?php

namespace Database\Factories;

use App\Models\CarBrand;
use App\Models\CarRate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    { 
        $this->faker->addProvider(new \Faker\Provider\Fakecar($this->faker));
        $v = $this->faker->vehicleArray();
        
        return [
            'car_rate_id' => CarRate::factory(),
            'car_brand_id' => CarBrand::factory(),
            'branch_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 10),
            'quantity' => $this->faker->numberBetween(1, 10),
            'plate_number' => $this->faker->vehicleRegistration('[0-3]{1}[0-9]{3}_[0-9]{11}'),
            'model' => $this->faker->vehicleModel,
            'vehicle_identification_number' => $this->faker->vin,
            'description' => $this->faker->text(180),
            'remarks' => $this->faker->text(100),
            'seats' => $this->faker->vehicleSeatCount,
            'mileage' => $this->faker->numberBetween(0, 60000),
            'year' => $this->faker->biasedNumberBetween(1998,2017, 'sqrt'),
            'transmission' => $this->faker->vehicleGearBoxType,
            'for_rent_status' => 'Approved'
        ];
    }
}
