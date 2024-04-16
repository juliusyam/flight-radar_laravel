<?php

namespace App\Livewire;

use App\Models\Flights;
use App\Providers\FlightProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
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

    #[On('add-flight')]
    public function addFlight(array $payload) {
        if (empty($this->user)) {
            return;
        }

        $flight = FlightProvider::create($this->user->id, $payload);

        $this->flights[] = $flight;
    }

    #[On('edit-flight')]
    public function editFlight(int $flightId, array $payload) {
        if (empty($this->user)) {
            return;
        }

        $flight = FlightProvider::update($flightId, $payload);

        $this->flights[] = $flight;
    }

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.hello-world', [
            'flights' => $this->flights,
        ]);
    }
}
