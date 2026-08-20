<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ROLE_VIEW = 'role.view';
    case ROLE_CREATE = 'role.create';
    case ROLE_UPDATE = 'role.update';
    case ROLE_DELETE = 'role.delete';
    case USER_VIEW = 'user.view';
    case USER_CREATE = 'user.create';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';
    case USER_EXPORT_PDF = 'user.export.pdf';
    case USER_EXPORT_EXCEL = 'user.export.excel';

    /** @return list<string> */
    public static function values(): array
    {
        return array_map(static fn (self $permission): string => $permission->value, self::cases());
    }

    public function module(): string
    {
        return str($this->value)->before('.')->headline()->toString().' Management';
    }

    public function label(): string
    {
        return str($this->value)->after('.')->replace('.', ' ')->headline()->toString();
    }
}
