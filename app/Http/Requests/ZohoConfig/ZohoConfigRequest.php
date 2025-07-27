<?php

namespace App\Http\Requests\ZohoConfig;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class ZohoConfigRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'code' => ['required'],
            'client_id' => ['required'],
            'client_secret' => ['required'],
            'redirect_uri' => ['required'],
            'refresh_token' => ['nullable']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
