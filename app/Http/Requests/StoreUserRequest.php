<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'credential_delivery' => ['required', 'in:invitation,temporary_password'],
            'password' => ['required_if:credential_delivery,temporary_password', 'nullable', 'confirmed', Password::defaults()],
        ];
    }
}
