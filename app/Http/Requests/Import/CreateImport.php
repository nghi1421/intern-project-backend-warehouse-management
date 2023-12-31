<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateImport extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'staff_id' => ['required', 'exists:staffs,id'],
            'status' => ['required', Rule::in(0, 1, 2, 3)],
            'provider_id' => ['required', 'exists:providers,id'],
            'warehouse_branch_id' => ['required', 'exists:warehouse_branches,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'numeric', 'exists:categories,id'],
            'amounts' => ['required', 'array', 'min:1'],
            'amounts.*' => ['required', 'numeric', 'min:1'],
            'unit_prices' => ['required', 'array', 'min:1'],
            'unit_prices.*' => ['required', 'numeric', 'min:1'],
        ];
    }
}
