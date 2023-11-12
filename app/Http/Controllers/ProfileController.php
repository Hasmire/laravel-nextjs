<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Put(
 *     path="/profile",
 *     operationId="updateProfile",
 *     tags={"User Profile"},
 *     summary="Update user's profile",
 *     description="Update the user's profile information.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User's profile data",
 *
 *         @OA\JsonContent(ref="#/components/schemas/ProfileUpdateRequest")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="profile-updated")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 * )
 *
 * @OA\Delete(
 *     path="/profile",
 *     operationId="deleteProfile",
 *     tags={"User Profile"},
 *     summary="Delete the user's account",
 *     description="Deletes the user's account after validating the current password.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User's current password for verification",
 *
 *         @OA\JsonContent(
 *             required={"password"},
 *
 *             @OA\Property(property="password", type="string", format="password", example="current_password")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile deleted successfully",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="profile-deleted")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized: Invalid current password",
 *     ),
 * )
 */
class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json(['status' => 'profile-updated']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'profile-deleted']);
    }
}
