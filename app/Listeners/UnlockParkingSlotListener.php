<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\View;
use App\Models\ParkingSlot;
use App\Models\Car;
use App\Models\CarParking;
use App\Events\UnlockParkingSlotEvent;

class UnlockParkingSlotListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UnlockParkingSlotEvent $event)
    {
        $job = $event->job;
        dd($job->completed);
        if($job->completed){
            $cars = Car::all();
            $parkingSlots = ParkingSlot::all();
            $carParkings = CarParking::all();

            $cars->load('latestParking');
            $parkingSlots->load('latestParking');
            $carParkings->load('car');
            $carParkings->load('parking_slot');

            return view('park-car', 
            [
                'parkingSlots' => $parkingSlots,
                'cars' => $cars,
                'carParkings' => $carParkings
            ]);
        }
    }
}
