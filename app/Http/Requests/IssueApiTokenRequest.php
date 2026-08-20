<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueApiTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'string', 'max:100'],
            'client_secret' => ['required', 'string', 'max:255'],
        ];
    }
}
