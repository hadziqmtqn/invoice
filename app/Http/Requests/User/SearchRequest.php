<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable'],
            'sort' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
