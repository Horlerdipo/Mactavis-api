<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
//        dd($this->request->all());
        return [
            'quantity' => [
                'numeric',
                "required",
                "min:1",
            ],
            'include_box' => [
                'string',
                'nullable'
            ]
        ];
    }
}
