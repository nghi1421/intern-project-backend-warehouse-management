<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class CreatePosition extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique.positions,name']
        ];
    }
}
