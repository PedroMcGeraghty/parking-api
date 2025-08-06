<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\AuthController;

Route::post('/parkings', [ParkingController::class, 'store']);
Route::get('/parkings/{id}', [ParkingController::class, 'show']);
Route::get('/parkings/closest', [ParkingController::class, 'closest']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/parkings', [ParkingController::class, 'store']);
});
