<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Post(
 *     path="/login",
 *     operationId="login",
 *     tags={"Authentication"},
 *     summary="Authenticate a user",
 *     description="Handles an incoming authentication request, authenticates the user, and generates a new session.",
 *     security={ {"sanctum": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User credentials",
 *
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=204,
 *         description="Authentication successful",
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized: Invalid credentials",
 *     ),
 * )
 *
 * @OA\Post(
 *     path="/logout",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     summary="Destroy an authenticated session",
 *     description="Handles destroying an authenticated session by logging out the user, invalidating the session, and regenerating the session token.",
 *     security={ {"sanctum": {} }},

 *
 *     @OA\Response(
 *         response=204,
 *         description="Session destroyed successfully",
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized: User not authenticated",
 *     ),
 * )
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * Authenticates a user based on the provided credentials and generates a new session.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     *
     * Logs out the currently authenticated user, invalidates the session, and regenerates the session token.
     */
    public function destroy(Request $request): Response
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
