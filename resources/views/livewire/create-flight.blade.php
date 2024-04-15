<div>
    <section>
        <h2>Add New Flight</h2>
        <div>
            <span>Departure Date:</span>
            <input type="date" wire:model.live="payload.departure_date">
        </div>
        <div>
            <span>Flight Number:</span>
            <input type="text" wire:model.live="payload.flight_number">
        </div>
        <div>
            <span>Departure Airport (IATA):</span>
            <input type="text" wire:model.live="payload.departure_airport">
        </div>
        <div>
            <span>Arrival Airport (IATA):</span>
            <input type="text" wire:model.live="payload.arrival_airport">
        </div>
        <div>
            <span>Distance (miles):</span>
            <input type="number" min="0" wire:model.live="payload.distance">
        </div>
        <div>
            <span>Airline (ICAO):</span>
            <input type="text" wire:model.live="payload.airline">
        </div>
        <button type="button" wire:click="addFlight">Add Flight</button>
    </section>

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
