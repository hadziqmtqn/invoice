<?php

namespace App\Http\Requests\Customer;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'sort_column' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'in:A,D'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
