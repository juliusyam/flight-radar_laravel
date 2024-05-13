<div>
    <section>
        <h2>Login</h2>
        <input type="email" wire:model.live="email" class="bg-white text-black">
        <input type="password" wire:model.live="password" class="bg-white text-black">

        <button type="button" wire:click="login" class="px-2 py-1 rounded-sm bg-teal-400 text-black">Login</button>

        @if(!empty($user))
        <h6>Logged in as: {{ $user->name }}</h6>
        @endif
    </section>

    <br>

    <section>
        <h2>Number Updates</h2>
        <button type="button" wire:click="increment">Increment</button>
        <button type="button" wire:click="decrement">Decrement</button>
        <h6>Count: {{ $count }}</h6>
    </section>

    <br>

    @if(!empty($flightStats))
    <section>
        <h4>Total Flights: {{ $flightStats["total_flights"] }}</h4>
        <h4>Total Distance: {{ $flightStats["total_distance"] }} miles</h4>

        <h4>Top Airports:</h4>
        <section>
            @foreach ($flightStats["top_airports"] as $key => $value)
                <h4>{{ $key }}: {{ $value }}</h4>
            @endforeach
        </section>

        <h4>Top Airlines:</h4>
        <section>
            @foreach ($flightStats["top_airlines"] as $key => $value)
            <h4>{{ $key }}: {{ $value }}</h4>
            @endforeach
        </section>
    </section>

    <br>
    @endif

    @if(!empty($user))
        <livewire:create-flight :flights="$flights" />
    @endif
</div>
