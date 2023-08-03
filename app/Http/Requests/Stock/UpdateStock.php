<?php

namespace App\Http\Requests\Stock;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStock extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_id' => ['sometimes', 'exists:locations,id'],
            'expiry_date' => ['sometimes', 'date', 'after:' . Carbon::now()]
        ];
    }
}
