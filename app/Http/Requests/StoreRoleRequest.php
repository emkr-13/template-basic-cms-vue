<?php

namespace App\Http\Requests;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::notIn([RoleEnum::SUPER_ADMIN->value])],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::in(PermissionEnum::values())],
        ];
    }
}
