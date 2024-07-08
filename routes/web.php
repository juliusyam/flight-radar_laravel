<?php

use App\Livewire\Dashboard;
use App\Livewire\ShowFlights;
use App\Livewire\UserLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', UserLogin::class);

Route::get('/dashboard', Dashboard::class);

Route::get('/flights', ShowFlights::class);
