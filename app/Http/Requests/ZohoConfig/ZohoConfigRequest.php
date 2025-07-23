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
            'code' => ['required', 'unique:zoho_configs,code,' . $this->route('zohoConfig')->id . ',id'],
            'client_id' => ['required'],
            'client_secret' => ['required'],
            'redirect_url' => ['required'],
            'refresh_token' => ['nullable']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
