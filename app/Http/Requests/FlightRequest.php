<?php

namespace App\Http\Requests;

class FlightRequest extends StandardRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departure_date' => 'required|date',
            'flight_number' => 'required|string|max:255',
            'departure_airport' => 'required|string|max:255',
            'arrival_airport' => 'required|string|max:255',
            'distance' => 'required|integer',
            'airline' => 'required|string|max:255',
        ];
    }
}
