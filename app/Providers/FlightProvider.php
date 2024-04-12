<?php

namespace App\Providers;

use App\Models\Flights;

class FlightProvider {
    public static function getFlightStats(string $userId): array {

        $flights = Flights::all()->where('user_id', $userId);

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
}
