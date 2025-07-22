<?php

namespace App\Http\Requests\User;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->route('user')->id . ',id'],
            'password' => ['nullable', 'min:8', 'confirmed']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
