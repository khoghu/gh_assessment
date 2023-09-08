<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarParking extends Model
{
    use HasFactory;

    protected $table = "car_parkings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_id',
        'parking_slot_id',
    ];

    /**
     * Relationship method to retrieve the car.
     *
     * @return array
     */
    public function car()
    {
        return $this->belongsTo(Car::class,'car_id');
    }

    /**
     * Relationship method to retrieve the parking slot.
     *
     * @return array
     */
    public function parking_slot()
    {
        return $this->belongsTo(ParkingSlot::class,'parking_slot_id');
    }
}
