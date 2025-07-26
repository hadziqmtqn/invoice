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
            'organization_id' => ['required', 'integer', 'exists:organizations,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
