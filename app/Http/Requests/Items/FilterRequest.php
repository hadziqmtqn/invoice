<?php

namespace App\Http\Requests\Items;

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
            'filter_by' => ['nullable', 'in:Status.All,Status.Active,Status.Inactive'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort_column' => ['nullable', 'string', 'in:name,rate'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
