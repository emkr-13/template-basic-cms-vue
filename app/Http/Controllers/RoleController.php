<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::query()
                ->where('name', '!=', RoleEnum::SUPER_ADMIN->value)
                ->withCount(['users', 'permissions'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Roles/Form', ['permissionGroups' => $this->permissionGroups(), 'role' => null]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->string('name')->toString(), 'guard_name' => 'web']);
        $role->syncPermissions($this->allowedPermissions($request->input('permissions', [])));
        $this->forgetPermissionCache();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role): Response
    {
        $this->guardSystemRole($role);

        return Inertia::render('Roles/Form', [
            'role' => ['id' => $role->id, 'name' => $role->name, 'permissions' => $role->permissions->pluck('name')->values()],
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->guardSystemRole($role);
        $role->update(['name' => $request->string('name')->toString()]);
        $role->syncPermissions($this->allowedPermissions($request->input('permissions', [])));
        $this->forgetPermissionCache();

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->guardSystemRole($role);

        if ($role->users()->exists()) {
            return back()->withErrors(['role' => 'Role yang masih digunakan user tidak dapat dihapus.']);
        }

        $role->delete();
        $this->forgetPermissionCache();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function guardSystemRole(Role $role): void
    {
        abort_if($role->name === RoleEnum::SUPER_ADMIN->value, 403);
    }

    /** @param array<int, string> $permissions */
    private function allowedPermissions(array $permissions): array
    {
        $requested = array_values(array_unique($permissions));
        $user = request()->user();

        if ($user->hasRole(RoleEnum::SUPER_ADMIN->value)) {
            return $requested;
        }

        $allowed = $user->getAllPermissions()->pluck('name')->all();
        abort_unless(empty(array_diff($requested, $allowed)), 403);

        return $requested;
    }

    /** @return array<int, array{name: string, permissions: array<int, array{name: string, label: string}>}> */
    private function permissionGroups(): array
    {
        $allowed = request()->user()->hasRole(RoleEnum::SUPER_ADMIN->value)
            ? PermissionEnum::values()
            : request()->user()->getAllPermissions()->pluck('name')->all();

        return collect(PermissionEnum::cases())
            ->filter(fn (PermissionEnum $permission): bool => in_array($permission->value, $allowed, true))
            ->groupBy(fn (PermissionEnum $permission): string => $permission->module())
            ->map(fn ($permissions, string $module): array => [
                'name' => $module,
                'permissions' => $permissions->map(fn (PermissionEnum $permission): array => ['name' => $permission->value, 'label' => $permission->label()])->values()->all(),
            ])->values()->all();
    }

    private function forgetPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
