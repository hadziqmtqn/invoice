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
            'product_type' => ['nullable', 'in:goods,service'],
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
