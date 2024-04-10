<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequest;
use App\Models\Flights;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FlightController extends Controller
{
    public function index() {

        $user = JWTAuth::parseToken()->authenticate();

        $flights = Flights::all()->where('user_id', $user->id);
        return $flights;
    }


    public function getFlightStats() {

        $user = JWTAuth::parseToken()->authenticate();

        $flights = Flights::all()->where('user_id', $user->id);

        $airports = array_merge(
            array_column($flights->toArray(), 'departure_airport'),
            array_column($flights->toArray(), 'arrival_airport'),
        );

        return response()->json([
            'total_flights' => $flights->count(),
            'total_distance' => array_sum(array_column($flights->toArray(), 'distance')),
            'top_airports' => array_count_values($airports),
            'top_airlines' => array_count_values(array_column($flights->toArray(), 'airline')),
        ]);
    }

    public function get(string $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = Flights::find($id);

        if (!empty($flight) && $flight->user_id === $user->id) {
            return response()->json($flight);
        } else {
            return response()->json([
                'message' => 'Flight not found',
            ], 404);
        }
    }

    public function create(FlightRequest $request) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = new Flights;
        $flight->departure_date = $request->departure_date;
        $flight->flight_number = $request->flight_number;
        $flight->departure_airport = $request->departure_airport;
        $flight->arrival_airport = $request->arrival_airport;
        $flight->distance = $request->distance;
        $flight->airline = $request->airline;
        $flight->user_id = $user->id;
        $flight->save();
        return response()->json($flight, 201);
    }

    public function update(string $id, FlightRequest $request) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = Flights::find($id);

        if (!empty($flight) && $flight->user_id === $user->id) {
            $flight->departure_date = $request->departure_date;
            $flight->flight_number = $request->flight_number;
            $flight->departure_airport = $request->departure_airport;
            $flight->arrival_airport = $request->arrival_airport;
            $flight->distance = $request->distance;
            $flight->airline = $request->airline;
            $flight->save();

            return response()->json($flight);
        } else {
            return response()->json([
                'message' => 'Flight not found',
            ], 404);
        }
    }

    public function delete(string $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = Flights::find($id);

        if (!empty($flight) && $flight->user_id === $user->id) {
            $flight->delete();

            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => 'Flight not found',
            ], 404);
        }
    }
}
