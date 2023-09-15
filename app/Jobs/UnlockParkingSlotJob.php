<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\CarParking;
use App\Events\UnlockParkingSlotEvent;

class UnlockParkingSlotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $completed = false;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentTimestamp = Carbon::now();
        $minutesAgo = $currentTimestamp->subMinutes(5);

        $carParkings = CarParking::where('created_at', '<=', $minutesAgo)->get();

        $carParkingIds = [];

        if(count($carParkings) > 0) {
            foreach($carParkings as $carParking)
            {
                $data = [
                    'car_id' => $carParking->car->id,
                    'parking_slot_id' => $carParking->parking_slot->id,
                    'car_park_id' => $carParking->id
                ];
                $carParkingIds[] = $data;
                CarParking::where('id', $carParking->id)->delete();
            }

            // Store data in the cache for 5 minutes
            Cache::put('carParkIds', $carParkingIds, now()->addMinutes(5));
        }

        // Set the completed status when the job is done
        //$this->completed = true;

        // Dispatch the JobCompleted event
        //event(new UnlockParkingSlotEvent($this));
    }
}
