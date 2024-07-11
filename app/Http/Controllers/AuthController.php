<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register account",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         description="Register an account",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Successfully created account"),
     *     @OA\Response(response=422, description="Validation failed. Email is not unique")
     * )
     */
    public function register(AuthRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login account",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         description="Login to an existing account",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successfully created account"),
     *     @OA\Response(response=422, description="Validation failed. Email is not unique")
     * )
     */
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            throw new UnauthorizedHttpException(__('auth.failed'));
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/refresh",
     *     summary="Refresh JWT Token",
     *     tags={"Authentication"},
     *     security={{"token": {}}},
     *     @OA\Response(response=200, description="Successfully retrieved a new valid JWT Token"),
     *     @OA\Response(response=400, description="Token is not provided"),
     *     @OA\Response(response=403, description="JWT Token is invalid, unable to generate new JWT Token")
     * )
     */
    public function refresh() {
        $token = JWTAuth::getToken();

        if (!$token) {
            throw new BadRequestHttpException(__('auth.token_not_found'));
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException) {
            throw new AccessDeniedHttpException(__('auth.token_invalid'));
        }

        return response()->json([
            'token' => $token,
        ]);
    }
}
