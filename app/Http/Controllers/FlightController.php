<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequest;
use App\Models\Flights;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FlightController extends Controller
{
    public function index() {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (TokenExpiredException|TokenInvalidException|JWTException) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

        $flights = Flights::all()->where('user_id', $user->id);
        return $flights;
    }

    public function get(string $id) {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (TokenExpiredException|TokenInvalidException|JWTException) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

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

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (TokenExpiredException|TokenInvalidException|JWTException) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

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

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (TokenExpiredException|TokenInvalidException|JWTException) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

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

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (TokenExpiredException|TokenInvalidException|JWTException) {
            return response()->json(['message' => 'Token expired or invalid'], 401);
        }

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
