<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Post(
 *     path="/forgot-password",
 *     operationId="sendResetLink",
 *     tags={"Authentication"},
 *     summary="Send password reset link",
 *     description="Handles an incoming request to send a password reset link to the user's email.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User's email for password reset link",
 *
 *         @OA\JsonContent(
 *             required={"email"},
 *
 *             @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Password reset link sent successfully",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="passwords.sent"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="email", type="array", @OA\Items(type="string")),
 *         )
 *     ),
 * )
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }
}
