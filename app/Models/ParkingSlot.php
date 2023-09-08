<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $table = "parking_slots";

     /**
     * Relationship method to retrieve the latest assign slot.
     *
     * @return array
     */
    public function latestParking()
    {
        return $this->hasOne(CarParking::class, 'parking_slot_id')->latest();
    }
}
