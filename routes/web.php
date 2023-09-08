<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingSlotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/park-car', [ParkingSlotController::class, 'showParkingForm'])->name('show-parking-form');
Route::post('/park-car-post', [ParkingSlotController::class, 'parkCar'])->name('park-car');
Route::get('/unlock-car', [ParkingSlotController::class, 'unlockParkCar'])->name('unlock-car');
