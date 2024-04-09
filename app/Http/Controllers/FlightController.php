<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequest;
use App\Models\Flights;

class FlightController extends Controller
{
    public function index() {
        $flights = Flights::all();
        return response()->json($flights);
    }

    public function get(string $id) {
        $flight = Flights::find($id);

        if (!empty($flight)) {
            return response()->json($flight);
        } else {
            return response()->json([
                'message' => 'Flight not found',
            ], 404);
        }
    }

    public function create(FlightRequest $request) {

        $flight = new Flights;
        $flight->departure_date = $request->departure_date;
        $flight->flight_number = $request->flight_number;
        $flight->departure_airport = $request->departure_airport;
        $flight->arrival_airport = $request->arrival_airport;
        $flight->distance = $request->distance;
        $flight->airline = $request->airline;
        $flight->save();
        return response()->json($flight, 201);
    }

    public function update(string $id, FlightRequest $request) {
        if (Flights::where('id', $id)->exists()) {
            $flight = Flights::find($id);

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
        if (Flights::where('id', $id)->exists()) {
            $flight = Flights::find($id);
            $flight->delete();

            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => 'Flight not found',
            ], 404);
        }
    }
}
