<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequest;
use App\Models\Flights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FlightController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/flights",
     *     summary="Get a list of flights created by the user",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve user from database")
     * )
     */
    public function index(Request $request) {

        $user = JWTAuth::parseToken()->authenticate();

        $flights = Flights::all()->where('user_id', $user->id);

        $airline = $request->query('airline');

        if ($airline) {
            $flights = $flights->where('airline', '>=', $airline);
        }

        $airport = $request->query('airport');

        if ($airport) {
            $departingFlights = $flights->where('departure_airport', '=', $airport);
            $arrivingFlights = $flights->where('arrival_airport', '=', $airport);

            $flights = $departingFlights->merge($arrivingFlights);
        }

        return array_merge($flights->toArray());
    }

    /**
     * @OA\Get(
     *     path="/api/flight-stats",
     *     summary="Get an abundance of flight stats from user's flight history",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve user from database")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/flights/{id}",
     *     summary="Get a specific flight",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Flight ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve flight")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/flights",
     *     summary="Create a new flight",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\RequestBody(
     *         description="Flight payload format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="departure_date",
     *                     description="Departure Date in ISO 8601 Format",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="flight_number",
     *                     description="Flight number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="departure_airport",
     *                     description="Departure airport in IATA Code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="arrival_airport",
     *                     description="Departure airport in IATA Code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="distance",
     *                     description="Flight distance in miles",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="airline",
     *                     description="Airline in ICAO Code",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Successfully created flight"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve flight")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/flights/{id}",
     *     summary="Update an existing flight",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Flight ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Flight payload format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="departure_date",
     *                     description="Departure Date in ISO 8601 Format",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="flight_number",
     *                     description="Flight number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="departure_airport",
     *                     description="Departure airport in IATA Code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="arrival_airport",
     *                     description="Departure airport in IATA Code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="distance",
     *                     description="Flight distance in miles",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="airline",
     *                     description="Airline in ICAO Code",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful updated flight"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve flight")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/flights/{id}",
     *     summary="Delete a specific flight from user's flight list",
     *     tags={"Flights"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Flight ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=204, description="Successfully deleted flight"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve flight")
     * )
     */
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
