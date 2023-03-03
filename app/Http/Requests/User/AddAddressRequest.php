<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddAddressRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'landmark' => [
                'required',
            ],
            'state' => [
                'required',
            ],
            'city' => [
                'required',
            ],
            'pin_code' => [
                'required',
            ],
            'email_id' => [
                'required',
            ],
            'contact_number' => [
                'required',
            ],
        ];
    }
}
