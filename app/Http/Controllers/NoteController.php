<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteEditRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Note;
use App\Models\Flight;
use App\Http\Requests\NoteRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoteController extends Controller
{
    /**
    * @OA\Get(
    *    path="/api/flights/{id}/notes",
    *    summary="Get all notes from sepcific a specific flight",
    *    tags={"Note"},
    *    security={{"token": {}}},
    *    @OA\Parameter(
    *        name="id",
    *        description="Flight ID",
    *        in="path",
    *        required=true,
    *        @OA\Schema(
    *            type="integer"
    *        )
    *    ),
    *    @OA\Response(response=200, description="Successful operation"),
    *    @OA\Response(response=401, description="Token is invalid"),
    *    @OA\Response(response=404, description="Unable to retrieve notes")
    * )
    */
    public function index(int $flightId) {

        $user = JWTAuth::parseToken()->authenticate();

        $flight = FlightController::getAndValidateFlightAccess($flightId, $user->id);

        return response()->json($flight->notes);
    }

    /**
    * @OA\Get(
    *    path="/api/notes/{id}",
    *    summary="Get a sepcific note",
    *    tags={"Note"},
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
    public function get(int $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $note = NoteController::getAndValidateNoteAccess($id, $user->id);

        return response()->json($note);
    }

    /**
    * @OA\Post(
    *    path="/api/notes",
    *    summary="Create a new note",
    *    tags={"Note"},
    *    security={{"token": {}}},
    *    @OA\RequestBody(
    *        description="Note payload format",
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

        FlightController::getAndValidateFlightAccess($request->flight_id, $user->id);

        $note = Note::create([
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
    *     tags={"Note"},
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
    *         description="Note payload format",
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
    *                )
    *            )
    *         )
    *     ),
    *     @OA\Response(response=200, description="Successfully updated note"),
    *     @OA\Response(response=401, description="Token is invalid"),
    *     @OA\Response(response=404, description="Unable to retrieve note")
    * )
    */

  public function update(int $id, NoteEditRequest $request) {

        $user = JWTAuth::parseToken()->authenticate();

        $note = NoteController::getAndValidateNoteAccess($id, $user->id);

        $flight = $note->flight;

        if (empty($flight)) {
          throw new NotFoundHttpException(__('flight.not_found', ['id' => $note->flight_id]));
        }

        if ($flight->user_id !== $user->id) {
          throw new AccessDeniedHttpException(__('flight.access_forbidden', ['id' => $note->flight_id]));
        }

        // TODO: Cannot update flight_id for note, it is permanent
        $note->title = $request->title;
        $note->body = $request->body;
        $note->save();

        return response()->json($note);
    }

    /**
    * @OA\Delete(
    *     path="/api/notes/{id}",
    *     summary="Delete a specific note from user's list of notes",
    *     tags={"Note"},
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
    public function delete(int $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $note = NoteController::getAndValidateNoteAccess($id, $user->id);

        $note->delete();

        return response()->json([], 204);
    }

    private static function getAndValidateNoteAccess(int $noteId, int $userId): Note {

        $note = Note::find($noteId);

        if (empty($note)) {
          throw new NotFoundHttpException(__('note.not_found', ['id' => $noteId]));
        }

        if ($note->user_id !== $userId) {
          throw new AccessDeniedHttpException(__('note.access_forbidden', ['id' => $noteId]));
        }

        return $note;
    }
}
