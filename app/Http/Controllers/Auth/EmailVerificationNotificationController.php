<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/email/verification-notification",
 *     operationId="sendEmailVerificationNotification",
 *     tags={"Authentication"},
 *     summary="Send a new email verification notification",
 *     description="Sends a new email verification notification to the authenticated user.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Verification link sent successfully",
 *
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(property="status", type="string", example="verification-link-sent"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=302,
 *         description="User has already verified their email, redirects to the home page",
 *     ),
 * )
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }
}
