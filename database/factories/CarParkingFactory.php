<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;
use App\Models\ParkingSlot;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarParking>
 */
class CarParkingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'car_id' => Car::all()->random()->id,
            'parking_slot_id' => ParkingSlot::all()->random()->id
        ];
    }
}
