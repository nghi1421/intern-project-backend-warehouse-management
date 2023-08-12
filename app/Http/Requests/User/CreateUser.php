<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'min:8', 'max:255', 'unique:users,username'],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'exists:permissions,id']
        ];
    }
}