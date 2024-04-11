<div>
    <section>
        <h2>Login</h2>
        <input type="email" wire:model.live="email">
        <input type="password" wire:model.live="password">

        <button type="button" wire:click="login">Login</button>

        <h6>Token: {{ $token }}</h6>
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
