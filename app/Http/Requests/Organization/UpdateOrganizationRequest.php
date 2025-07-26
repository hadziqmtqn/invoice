<?php

namespace App\Http\Requests\Organization;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'organization_id' => ['required', 'unique:organizations,organization_id,' . $this->route('organization')->slug . ',slug'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'organization_id' => 'id organisasi'
        ];
    }
}
