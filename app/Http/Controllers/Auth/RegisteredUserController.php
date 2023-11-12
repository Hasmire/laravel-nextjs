<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * @OA\Post(
 *     path="/register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Handle an incoming registration request.",
 *     security={ {"sanctum": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="User registration details",
 *
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "password_confirmation"},
 *
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=204,
 *         description="User registered successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too Many Requests",
 *     ),
 * )
 */
class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return response()->noContent();
    }
}
