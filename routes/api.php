<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/flights', [FlightController::class, 'index']);
    Route::get('/flight-stats', [FlightController::class, 'getFlightStats']);
    Route::get('/flights/{id}', [FlightController::class, 'get']);
    Route::post('/flights', [FlightController::class, 'create']);
    Route::put('/flights/{id}', [FlightController::class, 'update']);
    Route::delete('/flights/{id}', [FlightController::class, 'delete']);
});
