<div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
    <section>
        <h2 class="font-black text-xl mb-2">
            @if($selectedFlightId !== null)
            {{ Lang::get('livewire.title_edit_flight') }}
            @else
            {{ Lang::get('livewire.title_add_flight') }}
            @endif
        </h2>
        <section class="grid grid-cols-2 gap-2">
            <span>{{ Lang::get('livewire.label_departure_date') }}</span>
            <input type="date" wire:model.live="departure_date" class="bg-white text-black">

            <span>{{ Lang::get('livewire.label_flight_number') }}</span>
            <input type="text" wire:model.live="flight_number" class="bg-white text-black">

            <span>{{ Lang::get('livewire.label_departure_airport') }}</span>
            <input type="text" wire:model.live="departure_airport" class="bg-white text-black">

            <span>{{ Lang::get('livewire.label_arrival_airport') }}</span>
            <input type="text" wire:model.live="arrival_airport" class="bg-white text-black">

            <span>{{ Lang::get('livewire.label_distance') }}</span>
            <input type="number" min="0" wire:model.live="distance" class="bg-white text-black">

            <span>{{ Lang::get('livewire.label_airline') }}</span>
            <input type="text" wire:model.live="airline" class="bg-white text-black">
        </section>

        @if($selectedFlightId !== null)
        <button type="button" wire:click="editFlight" class="p-4 rounded-sm bg-teal-400 text-black">
            {{ Lang::get('livewire.button_edit_flight') }}
        </button>
        @else
        <button type="button" wire:click="addFlight" class="p-4 rounded-sm bg-teal-400 text-black">
            {{ Lang::get('livewire.button_add_flight') }}
        </button>
        @endif

    </section>

    <section>
        @foreach ($flights as $flight)
        <div class="flex justify-center items-center gap-2">
            <section @class(['border-2 border-white' => $flight["id"] === $selectedFlightId])>
                <h4>{{ Lang::get('livewire.indication_date', ['date' => $flight["departure_date"]]) }}</h4>
                <h3>{{ Lang::get('livewire.indication_airline', ['airline' => $flight["airline"]]) }}</h3>
                <h4>
                    {{
                        Lang::get('livewire.indication_journey', [
                            'departure' => $flight["departure_airport"],
                            'arrival' => $flight["arrival_airport"]
                        ])
                    }}
                </h4>
            </section>
            <section>
                <button wire:click="selectFlightFromList({{ collect($flight) }})">
                    {{ Lang::get('livewire.button_select') }}
                </button>
                <button wire:click="deleteFlight({{ $flight["id"] }})">
                    {{ Lang::get('livewire.button_delete') }}
                </button>
            </section>
        </div>

        <br>
        @endforeach
    </section>
</div>
