<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateStaff extends FormRequest
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
                'max:255'
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^(0?)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/',
                'unique.staffs,phone_number'
            ],
            'avatar' => [
                'sometimes',
                'image',
                File::types(['jpg', 'png', 'jpeg'])->max(1024 * 20),
            ],
            'address' => [
                'required',
                'string',
                'max:200',
            ],
            'gender' => [
                'required',
                Rule::in([0, 1, 2]),
            ],
            'position_id' => [
                'required',
                'numeric',
                'exists.positions,id'
            ],
            'user_id' => [
                'sometimes',
                'exists.users,id'
            ],
            'dob' => [
                'required',
                'date',
            ],
            'working' => [
                'required',
                'boolean',
            ]
        ];
    }
}
