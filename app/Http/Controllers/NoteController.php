<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Notes;
use App\Models\Flights;
use App\Http\Requests\NoteRequest;

class NoteController extends Controller
{
  /**
   * @OA\Get(
   *    path="/api/notes",
   *    summary="Get all notes from sepcific user",
   *    tags={"Notes"},
   *    security={{"token": {}}},
   *    @OA\Parameter(
   *         name="flight id",
   *         description="Flight ID for note",
   *         in="query",
   *         required=false,
   *         @OA\Schema(
   *             type="number"
   *         )
   *    ),
   *    @OA\Response(response=200, description="Successful operation"),
   *    @OA\Response(response=401, description="Token is invalid"),
   *    @OA\Response(response=404, description="Unable to retrieve note")
   * )
  */
  public function index(Request $request) {

      $user = JWTAuth::parseToken()->authenticate();

      $notes = Notes::where('user_id', $user->id)->get();

      $flight_id = $request->query('flight_id');

      if ($flight_id) {
        $notes = $notes->where('flight_id', '=', $flight_id);
      }

      return array_merge($notes->toArray());
  }

  /**
   * @OA\Get(
   *    path="/api/notes/{id}",
   *    summary="Get a sepcific note",
   *    tags={"Notes"},
   *    security={{"token": {}}},
   *    @OA\Parameter(
   *         name="id",
   *         description="Note ID",
   *         in="path",
   *         required=true,
   *         @OA\Schema(
   *             type="integer"
   *         )
   *    ),
   *    @OA\Response(response=200, description="Successful operation"),
   *    @OA\Response(response=401, description="Token is invalid"),
   *    @OA\Response(response=404, description="Unable to retrieve note")
   * )
  */
  public function get(string $id) {

      $user = JWTAuth::parseToken()->authenticate();

      $note = Notes::find($id);

      if (!empty($note) && $note->user_id === $user->id) {
        return response()->json(
          $note, 200);
      } else {
          return response()->json([
              'message' => 'Note not found',
          ], 404);
      }
  }

  /**
   * @OA\Post(
   *    path="/api/notes",
   *    summary="Create a new note",
   *    tags={"Notes"},
   *    security={{"token": {}}},
   *    @OA\RequestBody(
   *        description="Notes payload format",
   *        @OA\MediaType(
   *            mediaType="application/json",
   *            @OA\Schema(
   *                type="object",
   *                @OA\Property(
   *                    property="title",
   *                    description="Title of Note",
   *                    type="string",
   *                ),
   *                @OA\Property(
   *                    property="body",
   *                    description="Write the note",
   *                    type="string",
   *                ),
   *                @OA\Property(
   *                    property="flight_id",
   *                    description="Flight id",
   *                    type="number",
   *                )
   *            )
   *        )    
   *    ),    
   *    @OA\Response(response=201, description="Successful created note"),
   *    @OA\Response(response=401, description="Token is invalid"),
   *    @OA\Response(response=404, description="Unable to retrieve note")
   * )
  */
  public function create(NoteRequest $request) {

      $user = JWTAuth::parseToken()->authenticate();

      $flight = Flights::find($request->flight_id);

      if (empty($flight) or $flight->user_id !== $user->id) {
        return response()->json([
          'message' => 'Forbidden from accessing this flight'
        ], 403);
      }
  
      $note = Notes::create([
          'title' => $request->title,
          'body' => $request->body,
          'user_id' => $user->id,
          'flight_id' => $request->flight_id
      ]);
  
      return response()->json($note, 201);
  }

  /**
   * @OA\Put(
   *     path="/api/notes/{id}",
   *     summary="Update an existing note",
   *     tags={"Notes"},
   *     security={{"token": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         description="Note ID",
   *         in="path",
   *         required=true,
   *         @OA\Schema(
   *             type="integer"
   *         )
   *     ),
   *     @OA\RequestBody(
   *         description="Notes payload format",
   *         @OA\MediaType(
   *            mediaType="application/json",
   *            @OA\Schema(
   *                type="object",
   *                @OA\Property(
   *                    property="title",
   *                    description="Title of Note",
   *                    type="string",
   *                ),
   *                @OA\Property(
   *                    property="body",
   *                    description="Write the note",
   *                    type="string",
   *                ),
   *                @OA\Property(
   *                    property="flight_id",
   *                    description="Flight id",
   *                    type="number",
   *                )
   *            )
   *         )
   *     ),
   *     @OA\Response(response=200, description="Successfully updated note"),
   *     @OA\Response(response=401, description="Token is invalid"),
   *     @OA\Response(response=404, description="Unable to retrieve note")
   * )
  */
  public function update(string $id, NoteRequest $request) {

      $user = JWTAuth::parseToken()->authenticate();
  
      $note = Notes::find($id);

      if (empty($note) or $note->user_id !== $user->id) {
        return response()->json([
          'message' => 'Note not found'
        ], 404);
      }

      $flight = Flights::find($request->flight_id);

      if (empty($flight) or $flight->user_id != $user->id) {
        return response()->json([
          'message' => 'Forbidden from accessing this flight'
        ], 403);
      }

      $note->title = $request->title;
      $note->body = $request->body;
      $note->flight_id = $request->flight_id;
      $note->save();
    
      return response()->json($note, 200);
  }

  /**
   * @OA\Delete(
   *     path="/api/notes/{id}",
   *     summary="Delete a specific note from user's list of notes",
   *     tags={"Notes"},
   *     security={{"token": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         description="Note ID",
   *         in="path",
   *         required=true,
   *         @OA\Schema(
   *             type="integer"
   *         )
   *     ),
   *     @OA\Response(response=204, description="Successfully deleted note"),
   *     @OA\Response(response=401, description="Token is invalid"),
   *     @OA\Response(response=404, description="Unable to retrieve note")
   * )
  */
  public function delete(string $id) {
      
      $user = JWTAuth::parseToken()->authenticate();
  
      $note = Notes::find($id);

      if (!empty($note) && $note->user_id === $user->id) {
        $note->delete();
      } else {
        return response()->json([
          'message' => 'Note not found'
        ], 404);
      }
  
      return response()->json([], 204);
  }
}
