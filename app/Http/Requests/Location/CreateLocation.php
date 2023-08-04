<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLocation extends FormRequest
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
                'max:50'
            ],
            'warehouse_branch_id' => [
                'required',
                'exists:warehoure_branches,id',
                Rule::unique('locations', 'warehouse_branch_id')
                    ->where('user_id', $this->input('name')),
            ],
            'description' => ['required', 'max:255'],
        ];
    }
}