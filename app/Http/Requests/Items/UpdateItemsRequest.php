<?php

namespace App\Http\Requests\Items;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemsRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
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
