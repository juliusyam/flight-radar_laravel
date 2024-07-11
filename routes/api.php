<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\NoteController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([ForceJsonResponse::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
});

Route::middleware([ForceJsonResponse::class, EnsureTokenIsValid::class])->group(function () {
    Route::get('/flights', [FlightController::class, 'index']);
    Route::get('/flight-stats', [FlightController::class, 'getFlightStats']);
    Route::get('/flights/{id}', [FlightController::class, 'get']);
    Route::post('/flights', [FlightController::class, 'create']);
    Route::put('/flights/{id}', [FlightController::class, 'update']);
    Route::delete('/flights/{id}', [FlightController::class, 'delete']);

    Route::get('/flights/{id}/notes', [NoteController::class, 'index']);
    Route::get('/notes/{id}', [NoteController::class, 'get']);
    Route::post('/notes', [NoteController::class, 'create']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'delete']);
});
