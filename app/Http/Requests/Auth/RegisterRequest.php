<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "first_name" => ["required"],
            "last_name" => ["required"],
            "email" => [
                "required",
                Rule::unique('users'),
            ],
            "phone_number" => [
                "required",
                Rule::unique('users'),
            ],
            'role' => [
                "required",
                new Enum(UserType::class)
            ],
            'referred_by' => [
                "required",
                Rule::exists("users", "phone_number")
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
            ],
        ];
    }
}
