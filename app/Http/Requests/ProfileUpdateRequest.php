<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="ProfileUpdateRequest",
 *     title="Profile Update Request",
 *     description="Request body for updating user profile information.",
 *
 *     @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email address"),
 *     @OA\Property(property="address", type="string", example="123 Main St", description="User's address"),
 *     @OA\Property(property="number", type="string", example="555-1234", description="User's phone number"),
 *     @OA\Property(property="license", type="string", example="ABC123", description="User's license information"),
 * )
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'address' => ['string', 'max:255'],
            'number' => ['string', 'max:255'],
            'license' => ['string', 'max:255'],
        ];
    }
}
