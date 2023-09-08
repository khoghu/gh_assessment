<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = "cars";

    /**
     * Relationship method to retrieve the latest car parking.
     *
     * @return array
     */
    public function latestParking()
    {
        return $this->hasOne(CarParking::class, 'car_id')->latest();
    }
}
