<?php

namespace App\Http\Requests\Customer;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'contact_name' => ['required', 'min:3', 'max:100'],
            'company_name' => ['nullable', 'min:3', 'max:100'],
            'website' => ['nullable'],
            'notes' => ['nullable']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
