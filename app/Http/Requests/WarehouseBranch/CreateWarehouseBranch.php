<?php

namespace App\Http\Requests\WarehouseBranch;

use Illuminate\Foundation\Http\FormRequest;

class CreateWarehouseBranch extends FormRequest
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
                'string',
                'max:50',
                'unique:warehouse_branches,name'
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
            'opening' => [
                'required',
                'boolean',
            ]
        ];
    }
}
