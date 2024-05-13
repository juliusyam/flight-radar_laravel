<div>
    <section class="grid">
        <h2 class="font-black text-xl mb-2">Add New Flight</h2>
        <section class="grid grid-cols-2 gap-2">
            <span>Departure Date:</span>
            <input type="date" wire:model.live="departure_date" class="bg-white text-black">
            <span>Flight Number:</span>
            <input type="text" wire:model.live="flight_number" class="bg-white text-black">
            <span>Departure Airport (IATA):</span>
            <input type="text" wire:model.live="departure_airport" class="bg-white text-black">
            <span>Arrival Airport (IATA):</span>
            <input type="text" wire:model.live="arrival_airport" class="bg-white text-black">
            <span>Distance (miles):</span>
            <input type="number" min="0" wire:model.live="distance" class="bg-white text-black">
            <span>Airline (ICAO):</span>
            <input type="text" wire:model.live="airline" class="bg-white text-black">
        </section>
        @if($selectedFlightId !== null)
        <button type="button" wire:click="editFlight" class="p-4 rounded-sm bg-teal-400">Edit Flight</button>
        @else
        <button type="button" wire:click="addFlight" class="p-4 rounded-sm bg-teal-400">Add Flight</button>
        @endif

    </section>

    <br>

    <section>
        @foreach ($flights as $flight)
        <div class="flex justify-center items-center gap-2">
            <section @class(['border-2 border-white' => $flight["id"] === $selectedFlightId])>
                <h4>Date: {{ $flight["departure_date"] }}</h4>
                <h3>Airline: {{ $flight["airline"] }}</h3>
                <h4>Journey: {{ $flight["departure_airport"] }} - {{ $flight["arrival_airport"] }} </h4>
            </section>
            <section>
                <button wire:click="selectFlightFromList({{ collect($flight) }})">Select</button>
                <button wire:click="deleteFlight({{ $flight["id"] }})">Delete</button>
            </section>
        </div>

        <br>
        @endforeach
    </section>
</div>
