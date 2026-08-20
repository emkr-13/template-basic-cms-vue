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

    public function description(): string
    {
        return match ($this) {
            self::ROLE_VIEW => 'Access and view role list, details, and permission matrices.',
            self::ROLE_CREATE => 'Define new custom roles and configure assigned permission scopes.',
            self::ROLE_UPDATE => 'Modify names and update permission grants for existing custom roles.',
            self::ROLE_DELETE => 'Delete unassigned custom roles from the system.',
            self::USER_VIEW => 'View user directory, user profile details, and account status.',
            self::USER_CREATE => 'Create user accounts via email invitation or temporary password.',
            self::USER_UPDATE => 'Edit user account details, assign roles, and toggle access status.',
            self::USER_DELETE => 'Perform soft deletion and deactivate user accounts.',
            self::USER_EXPORT_PDF => 'Generate and download user directory report in PDF format.',
            self::USER_EXPORT_EXCEL => 'Export complete user data list as an Excel spreadsheet (.xlsx).',
        };
    }

    /**
     * Auto-resolve required view permissions for any action permission in the same module.
     *
     * @param array<int, string> $permissions
     * @return array<int, string>
     */
    public static function resolveDependencies(array $permissions): array
    {
        $resolved = collect($permissions);

        foreach ($permissions as $permission) {
            $modulePrefix = str($permission)->before('.')->toString();
            $viewPermission = "{$modulePrefix}.view";

            if (in_array($viewPermission, self::values(), true) && ! $resolved->contains($viewPermission)) {
                $resolved->push($viewPermission);
            }
        }

        return $resolved->unique()->values()->all();
    }
}
