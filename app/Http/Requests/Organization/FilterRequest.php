<?php

namespace App\Http\Requests\Organization;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
