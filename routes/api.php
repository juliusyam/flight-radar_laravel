<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\NoteController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Models\Note;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([EnsureTokenIsValid::class])->group(function () {
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
