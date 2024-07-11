<?php

namespace App\Http\Controllers;

use App\Events\NewFlightAdded;
use App\Http\Requests\FlightRequest;
use App\Jobs\ProcessNewFlight;
use App\Jobs\ProcessUpdatedFlight;
use App\Jobs\ProcessDeletedFlight;
use App\Models\Flight;
use App\Models\Note;
use App\Providers\FlightProvider;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FlightController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/flights",
     *     summary="Get a list of flights created by the user",
     *     tags={"Flight"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         name="airline",
     *         description="Airline in ICAO Code",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="airport",
     *         description="Airport in IATA Code",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve user from database")
     * )
     */
    public function index(Request $request) {

        $user = JWTAuth::parseToken()->authenticate();

        $flights = Flight::all()->where('user_id', $user->id);

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
     *     tags={"Flight"},
     *     security={{"token": {}}},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Token is invalid"),
     *     @OA\Response(response=404, description="Unable to retrieve user from database")
     * )
     */
    public function getFlightStats() {

        $user = JWTAuth::parseToken()->authenticate();

        $flightStats = FlightProvider::getFlightStats($user->id);

        return response()->json($flightStats);
    }

    /**
     * @OA\Get(
     *     path="/api/flights/{id}",
     *     summary="Get a specific flight",
     *     tags={"Flight"},
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
    public function get(int $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = FlightController::getAndValidateFlightAccess($id, $user->id);

        return response()->json($flight);
    }

    /**
     * @OA\Post(
     *     path="/api/flights",
     *     summary="Create a new flight",
     *     tags={"Flight"},
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

        $flight = FlightProvider::create($user->id, [
            'departure_date' => $request->departure_date,
            'flight_number' => $request->flight_number,
            'departure_airport' => $request->departure_airport,
            'arrival_airport' => $request->arrival_airport,
            'distance' => $request->distance,
            'airline' => $request->airline,
            'user_id' => $user->id,
        ]);

        // Dispatch the job with selected data
        ProcessNewFlight::dispatch($flight);

        return response()->json($flight, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/flights/{id}",
     *     summary="Update an existing flight",
     *     tags={"Flight"},
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
    public function update(int $id, FlightRequest $request) {

        $user = JWTAuth::parseToken()->authenticate();

        FlightController::getAndValidateFlightAccess($id, $user->id);

        $flight = FlightProvider::update($id, [
            'departure_date' => $request->departure_date,
            'flight_number' => $request->flight_number,
            'departure_airport' => $request->departure_airport,
            'arrival_airport' => $request->arrival_airport,
            'distance' => $request->distance,
            'airline' => $request->airline,
            'user_id' => $user->id,
        ]);

        ProcessUpdatedFlight::dispatch($flight);

        return response()->json($flight);
    }

    /**
     * @OA\Delete(
     *     path="/api/flights/{id}",
     *     summary="Delete a specific flight from user's flight list",
     *     tags={"Flight"},
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

        $flight = FlightController::getAndValidateFlightAccess($id, $user->id);

        $flight->delete();

        Note::where('flight_id', $id)->delete();

        ProcessDeletedFlight::dispatch($user->id, $flight->id);

        return response()->json([], 204);
    }

    public static function getAndValidateFlightAccess(int $flightId, int $userId): Flight {

        $flight = Flight::find($flightId);

        if (empty($flight)) {
            throw new NotFoundHttpException(__('flight.not_found', ['id' => $flightId]));
        }

        if ($flight->user_id !== $userId) {
            throw new AccessDeniedHttpException(__('flight.access_forbidden', ['id' => $flightId]));
        }

        return $flight;
    }
}
