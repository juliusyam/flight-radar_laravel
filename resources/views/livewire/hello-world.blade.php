<div>
    <section>
        @if(!empty($user))
            <h6>{{ Lang::get('livewire.message_logged_in', ['name' => $user->name]) }}</h6>
            <button type="button" wire:click="logout" class="px-2 py-1 rounded-sm bg-teal-400 text-black">
                {{ Lang::get('livewire.button_logout') }}
            </button>
        @endif
    </section>

    <br>

    <section>
        <h2>{{ Lang::get('livewire.title_number_updates') }}</h2>
        <button type="button" wire:click="increment">{{ Lang::get('livewire.button_increment') }}</button>
        <button type="button" wire:click="decrement">{{ Lang::get('livewire.button_decrement') }}</button>
        <h6>{{ Lang::get('livewire.indication_count', ['count' => $count]) }}</h6>
    </section>

    <br>

    @if(!empty($flightStats))
    <section>
        <h4>{{ Lang::get('livewire.indication_flights', ['flights' => $flightStats["total_flights"]]) }}</h4>
        <h4>{{ Lang::get('livewire.indication_distance', ['distance' => $flightStats["total_distance"]]) }}</h4>

        <h4>{{ Lang::get('livewire.indication_airports') }}</h4>
        <section>
            @foreach ($flightStats["top_airports"] as $key => $value)
                <h4>{{ $key }}: {{ $value }}</h4>
            @endforeach
        </section>

        <h4>{{ Lang::get('livewire.indication_airlines') }}</h4>
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
