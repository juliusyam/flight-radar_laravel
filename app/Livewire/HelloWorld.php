<?php

namespace App\Livewire;

use App\Models\Flights;
use App\Providers\FlightProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class HelloWorld extends Component
{
    public $email = '';
    public $password = '';
    public $user = null;

    public $token = '';

    public $count = 0;

    public $flights = [];
    public $flightStats = null;

    public function login() {
        $token = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$token) {
            Log::debug('Unable to authenticate');
            return;
        }

        $this->token = $token;

        $user = JWTAuth::user();

        $this->user = $user;

        $this->flights = Flights::all()->where('user_id', $user->id);

        Log::info(FlightProvider::getFlightStats($user->id));

        $this->flightStats = FlightProvider::getFlightStats($user->id);
    }

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
//        $this->flights = Flights::all();
        return view('livewire.hello-world');
    }
}
