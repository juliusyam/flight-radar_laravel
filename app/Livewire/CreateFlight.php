<?php

namespace App\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class CreateFlight extends Component
{
    #[Reactive]
    public array $flights;

    public array $emptyPayload = [
        'departure_date' => '',
        'flight_number' => '',
        'departure_airport' => '',
        'arrival_airport' => '',
        'distance' => 0,
        'airline' => '',
    ];

    public array $payload;

    public function addFlight() {
        $this->dispatch('add-flight', payload: $this->payload);
    }

    public function __construct() {
        $this->payload = $this->emptyPayload;
    }

    public function render()
    {
        return view('livewire.create-flight', [
            'flights' => $this->flights,
        ]);
    }
}
