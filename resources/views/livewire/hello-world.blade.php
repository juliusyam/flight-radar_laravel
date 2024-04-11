<div>
    <section>
        <h2>Name Input</h2>
        <input type="text" wire:model.live="name">
        <h6>Hello from {{ $name }}.</h6>
    </section>

    <br>

    <section>
        <h2>Number Updates</h2>
        <button type="button" wire:click="increment">Increment</button>
        <button type="button" wire:click="decrement">Decrement</button>
        <h6>Count: {{ $count }}</h6>
    </section>

    <br>

    <section>
        @foreach ($flights as $flight)
            <div>
                <h4>Date: {{ $flight->departure_date }}</h4>
                <h3>Airline: {{ $flight->airline }}</h3>
                <h4>Journey: {{ $flight->departure_airport }} - {{ $flight->arrival_airport }} </h4>
                <br>
            </div>
        @endforeach
    </section>
</div>
