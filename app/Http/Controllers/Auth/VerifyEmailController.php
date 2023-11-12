<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * @OA\Get(
 *     path="/verify-email/{id}/{hash}",
 *     operationId="verifyEmail",
 *     tags={"Authentication"},
 *     summary="Verify user's email address",
 *     description="Marks the authenticated user's email address as verified.",
 *     security={{"sanctum": {}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *
 *         @OA\Schema(type="string")
 *     ),
 *
 *     @OA\Parameter(
 *         name="hash",
 *         in="path",
 *         required=true,
 *         description="Verification hash",
 *
 *         @OA\Schema(type="string")
 *     ),
 *
 *     @OA\Response(
 *         response=302,
 *         description="Email address verified successfully, redirects to the home page",
 *     ),
 * )
 */
class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
        );
    }
}
