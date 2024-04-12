<?php

namespace App\Livewire;

use App\Http\Requests\FlightRequest;
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

    public array $payload = [
        'departure_date' => '2015-07-01',
        'flight_number' => 'CX255',
        'departure_airport' => 'HKG',
        'arrival_airport' => 'LHR',
        'distance' => 5991,
        'airline' => 'CPA',
    ];

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

        $flight = FlightProvider::create($this->user->id, $this->payload);

        // Add single entry to the back of the array
        $this->flights[] = $flight;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.hello-world');
    }
}
