<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\ParkingSlot;
use App\Models\Car;
use App\Models\CarParking;
use App\Http\Requests\CreateParkingRequest;
use App\Services\ParkingService;
use App\Jobs\UnlockParkingSlotJob; 

class ParkingSlotController extends Controller
{
    protected $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function showParkingForm()
    {
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

    public function parkCar(CreateParkingRequest $request)
    {
        // Validate the request using the CreateParkingRequest class
        $validatedData = $request->validated();

        // Create the parking using the ParkingService
        $carPark = $this->parkingService->createParking(
            $validatedData['car_id'],
            $validatedData['parking_slot_id']
        );

        // Schedule the second method to run after 5 minutes
        dispatch(new UnlockParkingSlotJob())->delay(now()->addMinutes(5));

        // Redirect to home page
        return redirect()->route('show-parking-form');
    }

    public function getRemovedParking()
    {
        // Retrieve the carParkId from the storage mechanism (e.g., cache, database)
        $carParks = Cache::get('carParkIds');

        $carParkings = CarParking::all();

        if($carParks && count($carParks) > 0) {
            // Clear the cache
            Cache::forget('carParkIds');
            return response()->json(["success"=>true, "park_data"=>$carParks, "carParkings"=>$carParkings ]);
        } else {
            return response()->json(["success"=>false, "carParkings"=>$carParkings ]);
        }
    }

    public function unlockParkCar()
    {
        $currentTimestamp = Carbon::now();
        $minutesAgo = $currentTimestamp->subMinutes(1);

        $carParkings = CarParking::where('created_at', '<=', $minutesAgo)->get();

        if(count($carParkings) > 0) {
            foreach($carParkings as $carParking)
            {
                CarParking::where('id', $carParking->id)->delete();
            }
        }
        // Redirect to home page
        return redirect()->route('show-parking-form');
    }
}