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

//        $index = array_search($flightId, array_column($this->flights, 'id'));

        $flight = FlightProvider::update($flightId, $payload);

        // TODO: Breaking, fix issue
//        array_splice($this->flights, $index, 1, $flight);

        // TODO: To Remove, temporary implementation to re fetch dat on edit
        $this->flights = Flights::all()->where('user_id', $this->user->id)->toArray();
    }

    #[On('delete-flight')]
    public function deleteFlight(int $flightId) {
        if (empty($this->user)) {
            return;
        }

        $flight = Flights::find($flightId);

        $index = array_search($flightId, array_column($this->flights, 'id'));

        if (!empty($flight)) {
            $flight->delete();

            array_splice($this->flights, $index, 1);
        }
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
