<?php

use App\Http\Middleware\GetBrowserLanguage;
use App\Livewire\Dashboard;
use App\Livewire\ShowFlights;
use App\Livewire\UserLogin;
use Illuminate\Support\Facades\Route;

Route::middleware([GetBrowserLanguage::class])->group(function () {
    Route::get('/', UserLogin::class);
    Route::get('/dashboard', Dashboard::class);
    Route::get('/flights', ShowFlights::class);
});
