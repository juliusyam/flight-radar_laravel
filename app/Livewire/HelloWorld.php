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
    public string $email = '';
    public string $password = '';
    public $user = null;
    public string $token = '';

    public int $count = 0;

    public array $flights = [];
    public $flightStats = null;

    public array $emptyPayload = [
        'departure_date' => '',
        'flight_number' => '',
        'departure_airport' => '',
        'arrival_airport' => '',
        'distance' => 0,
        'airline' => '',
    ];

    public array $payload;

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

        $this->flights = Flights::all()->where('user_id', $user->id)->toArray();

        $this->flightStats = FlightProvider::getFlightStats($user->id);
    }

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function addFlight() {
        if (empty($this->user)) {
            return;
        }

        $flight = FlightProvider::create($this->user->id, $this->payload);

        $this->flights[] = $flight;

        $this->payload = $this->emptyPayload;
    }

    public function __construct() {
        $this->payload = $this->emptyPayload;
    }

    public function render()
    {
        return view('livewire.hello-world');
    }
}
