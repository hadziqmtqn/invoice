<?php

namespace App\Http\Requests\Items;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class ItemsRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric'],
            'description' => ['nullable'],
            'tax_id' => ['nullable', 'string'],
            'sku' => ['nullable'],
            'product_type' => ['nullable', 'in:goods,service']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
