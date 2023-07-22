<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class CreateProvider extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:50',
                'unique:providers,name'
            ],
            'email' => [
                'required',
                'max:255',
                'unique:providers,email'
            ],
            'address' => [
                'required',
                'max:255'
            ],
            'phone_number' => [
                'required',
                'max:15',
                'regex:/^(0?)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/',
                'unique:providers,phone_number'
            ],
        ];
    }
}
