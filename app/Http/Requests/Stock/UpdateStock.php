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
            'expiry_date' => ['sometimes', 'date', 'after:' . Carbon::now()],
        ];
    }
}
