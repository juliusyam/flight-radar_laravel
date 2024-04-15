<div>
    <section class="grid">
        <h2 class="font-black text-xl mb-2">Add New Flight</h2>
        <section class="grid grid-cols-2 gap-2">
            <span>Departure Date:</span>
            <input type="date" wire:model.live="departure_date">
            <span>Flight Number:</span>
            <input type="text" wire:model.live="flight_number">
            <span>Departure Airport (IATA):</span>
            <input type="text" wire:model.live="departure_airport">
            <span>Arrival Airport (IATA):</span>
            <input type="text" wire:model.live="arrival_airport">
            <span>Distance (miles):</span>
            <input type="number" min="0" wire:model.live="distance">
            <span>Airline (ICAO):</span>
            <input type="text" wire:model.live="airline">
        </section>
        <button type="button" wire:click="addFlight">Add Flight</button>
    </section>

    <br>

    <section>
        @foreach ($flights as $flight)
        <div>
            <h4>Date: {{ $flight["departure_date"] }}</h4>
            <h3>Airline: {{ $flight["airline"] }}</h3>
            <h4>Journey: {{ $flight["departure_airport"] }} - {{ $flight["arrival_airport"] }} </h4>
            <br>
        </div>
        @endforeach
    </section>
</div>
