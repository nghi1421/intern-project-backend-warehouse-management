<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateImport extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(0, 1, 2, 3)],
            'provider_id' => ['sometimes', 'exists:providers,id'],
            'categories' => ['sometimes', 'array', 'min:1'],
            'categories.*' => ['sometimes', 'numeric', 'exists:categories,id'],
            'amounts' => ['sometimes', 'array', 'min:1'],
            'amounts.*' => ['sometimes', 'numeric', 'min:1'],
            'unit_prices' => ['sometimes', 'array', 'min:1'],
            'unit_prices.*' => ['sometimes', 'numeric'],
        ];
    }
}
