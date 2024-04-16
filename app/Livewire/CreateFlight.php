<?php

namespace App\Livewire;

use App\Http\Requests\FlightRequest;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CreateFlight extends Component
{
    #[Reactive]
    public array $flights;

    public int | null $selectedFlightId = null;

    public string $departure_date = '';
    public string $flight_number = '';
    public string $departure_airport = '';
    public string $arrival_airport = '';
    public int $distance = 0;
    public string $airline = '';

    protected function rules(): array {
        return (new FlightRequest())->rules();
    }

    public function addFlight() {
        $this->validate();

        $this->dispatch('add-flight', payload: [
            'departure_date' => $this->departure_date,
            'flight_number' => $this->flight_number,
            'departure_airport' => $this->departure_airport,
            'arrival_airport' => $this->arrival_airport,
            'distance' =>$this->distance,
            'airline' => $this->airline,
        ]);

        $this->departure_date = '';
        $this->flight_number = '';
        $this->departure_airport = '';
        $this->arrival_airport = '';
        $this->distance = 0;
        $this->airline = '';
    }

    public function selectFlightFromList(int $flightId) {
        if ($this->selectedFlightId === $flightId) {
            $this->selectedFlightId = null;
        } else {
            $this->selectedFlightId = $flightId;
        }
    }

    public function render()
    {
        return view('livewire.create-flight', [
            'flights' => $this->flights,
        ]);
    }
}
