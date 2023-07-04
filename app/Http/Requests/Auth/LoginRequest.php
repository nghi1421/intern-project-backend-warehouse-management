<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:8', 'max:256'],
            'password' => ['required', 'string', 'min:8', 'max:256'],
            'remember' => ['sometimes', 'boolean']
        ];
    }
}
