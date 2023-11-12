<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Put(
 *     path="/password",
 *     operationId="updatePassword",
 *     tags={"Authentication"},
 *     summary="Update user password",
 *     description="Handles an incoming request to update the user's password.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User's password update information",
 *
 *         @OA\JsonContent(
 *             required={"current_password", "password", "password_confirmation"},
 *
 *             @OA\Property(property="current_password", type="string", format="password", description="Current password"),
 *             @OA\Property(property="password", type="string", format="password", description="New password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmation of the new password"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Password updated successfully",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="password-updated"),
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
 *             @OA\Property(property="current_password", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="password", type="array", @OA\Items(type="string")),
 *         )
 *     ),
 * )
 */
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['status' => 'password-updated']);
    }
}
