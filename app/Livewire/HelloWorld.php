<?php

namespace App\Livewire;

use App\Models\Flight;
use App\Providers\FlightProvider;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Component;

class HelloWorld extends Component
{
    #[Session(key: 'user')]
    public $user;

    public int $count = 0;

    public array $flights = [];
    public $flightStats = null;

    public function logout()
    {
        $this->user = null;

        $this->redirect('/');
    }

    #[On('add-flight')]
    public function addFlight(array $payload)
    {
        if (empty($this->user)) {
            return;
        }

        $flight = FlightProvider::create($this->user->id, $payload);

        $this->flights[] = $flight;
    }

    #[On('edit-flight')]
    public function editFlight(int $flightId, array $payload)
    {
        if (empty($this->user)) {
            return;
        }

//        $index = array_search($flightId, array_column($this->flights, 'id'));

        $flight = FlightProvider::update($flightId, $payload);

        // TODO: Breaking, fix issue
//        array_splice($this->flights, $index, 1, $flight);

        // TODO: To Remove, temporary implementation to re fetch dat on edit
        $this->flights = Flight::all()->where('user_id', $this->user->id)->toArray();
    }

    #[On('delete-flight')]
    public function deleteFlight(int $flightId)
    {
        if (empty($this->user)) {
            return;
        }

        $flight = Flight::find($flightId);

        $index = array_search($flightId, array_column($this->flights, 'id'));

        if (!empty($flight)) {
            $flight->delete();

            array_splice($this->flights, $index, 1);
        }
    }

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        if ($this->user) {
            $this->flights = Flight::all()->where('user_id', $this->user->id)->toArray();

            $this->flightStats = FlightProvider::getFlightStats($this->user->id);
        }

        return view('livewire.hello-world', [
            'flights' => $this->flights,
        ]);
    }
}
