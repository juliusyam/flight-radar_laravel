<?php

namespace App\Providers;

use App\Models\Flight;

class FlightProvider {
    public static function getFlightStats(int $userId): array {

        $flights = Flight::all()->where('user_id', $userId);

        $airports = array_merge(
            array_column($flights->toArray(), 'departure_airport'),
            array_column($flights->toArray(), 'arrival_airport'),
        );

        return [
            'total_flights' => $flights->count(),
            'total_distance' => array_sum(array_column($flights->toArray(), 'distance')),
            'top_airports' => array_count_values($airports),
            'top_airlines' => array_count_values(array_column($flights->toArray(), 'airline')),
        ];
    }

    public static function create(int $userId, array $payload): Flight {

        $flight = new Flight;
        $flight->departure_date = $payload['departure_date'];
        $flight->flight_number = $payload['flight_number'];
        $flight->departure_airport = $payload['departure_airport'];
        $flight->arrival_airport = $payload['arrival_airport'];
        $flight->distance = $payload['distance'];
        $flight->airline = $payload['airline'];
        $flight->user_id = $userId;
        $flight->save();

        return $flight;
    }

    public static function update(int $flightId, array $payload): Flight {

        $flight = Flight::find($flightId);

        $flight->departure_date = $payload['departure_date'];
        $flight->flight_number = $payload['flight_number'];
        $flight->departure_airport = $payload['departure_airport'];
        $flight->arrival_airport = $payload['arrival_airport'];
        $flight->distance = $payload['distance'];
        $flight->airline = $payload['airline'];
        $flight->save();

        return $flight;
    }
}
