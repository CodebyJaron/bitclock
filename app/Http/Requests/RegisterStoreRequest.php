<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'invite_token' => ['required'],
            "password" => ['required', 'string', 'min:6', 'max:25'],
            "repeatedPassword" => ['required','same:password'],
        ];
    }
}
