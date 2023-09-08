<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\ParkingSlot::factory(15)->create();
        \App\Models\Car::factory(20)->create();
        //\App\Models\CarParking::factory(20)->create();
    }
}
