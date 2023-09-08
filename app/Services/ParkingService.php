<?php
namespace App\Services;

use App\Models\CarParking;

class ParkingService
{
    public function createParking($carId, $slotId)
    {
        // Implement the logic to create a bid here
        $carParking = new CarParking([
            'car_id' => $carId,
            'parking_slot_id' => $slotId,
        ]);

        $carParking->save();

        return $carParking;
    }
}