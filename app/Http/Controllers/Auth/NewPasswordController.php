<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Post(
 *     path="/reset-password",
 *     operationId="resetPassword",
 *     tags={"Authentication"},
 *     summary="Reset user password",
 *     description="Handles an incoming request to reset the user's password.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User's password reset information",
 *
 *         @OA\JsonContent(
 *             required={"token", "email", "password", "password_confirmation"},
 *
 *             @OA\Property(property="token", type="string", description="Password reset token"),
 *             @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *             @OA\Property(property="password", type="string", format="password", description="New password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmation of the new password"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="passwords.reset"),
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
 *             @OA\Property(property="password", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="token", type="array", @OA\Items(type="string")),
 *         )
 *     ),
 * )
 */
class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }
}
