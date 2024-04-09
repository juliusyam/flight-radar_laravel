<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::get('/flights', [FlightController::class, 'index']);
Route::get('/flights/{id}', [FlightController::class, 'get']);
Route::post('/flights', [FlightController::class, 'create']);
Route::put('/flights/{id}', [FlightController::class, 'update']);
Route::delete('/flights/{id}', [FlightController::class, 'delete']);
