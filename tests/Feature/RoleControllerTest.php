<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_cannot_access_role_routes(): void
    {
        $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

        $this->get(route('roles.index'))->assertRedirect(route('login'));
        $this->get(route('roles.create'))->assertRedirect(route('login'));
        $this->post(route('roles.store'), ['name' => 'tester'])->assertRedirect(route('login'));
        $this->get(route('roles.edit', $role))->assertRedirect(route('login'));
        $this->put(route('roles.update', $role), ['name' => 'manager2'])->assertRedirect(route('login'));
        $this->delete(route('roles.destroy', $role))->assertRedirect(route('login'));
    }

    public function test_user_without_permissions_cannot_access_role_routes(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

        $this->actingAs($user)->get(route('roles.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('roles.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('roles.store'), ['name' => 'tester'])->assertStatus(403);
        $this->actingAs($user)->get(route('roles.edit', $role))->assertStatus(403);
        $this->actingAs($user)->put(route('roles.update', $role), ['name' => 'manager2'])->assertStatus(403);
        $this->actingAs($user)->delete(route('roles.destroy', $role))->assertStatus(403);
    }

    public function test_super_admin_can_access_role_index_excluding_super_admin_role(): void
    {
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole(RoleEnum::SUPER_ADMIN->value);

        Role::create(['name' => 'staff', 'guard_name' => 'web']);

        $response = $this->actingAs($superAdminUser)->get(route('roles.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Roles/Index')
            ->has('roles', 1)
            ->where('roles.0.name', 'staff')
        );
    }

    public function test_cannot_edit_update_or_delete_super_admin_system_role(): void
    {
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole(RoleEnum::SUPER_ADMIN->value);
        $superAdminRole = Role::findByName(RoleEnum::SUPER_ADMIN->value, 'web');

        $this->actingAs($superAdminUser)->get(route('roles.edit', $superAdminRole))->assertStatus(403);
        $this->actingAs($superAdminUser)->put(route('roles.update', $superAdminRole), ['name' => 'hacked_admin'])->assertStatus(403);
        $this->actingAs($superAdminUser)->delete(route('roles.destroy', $superAdminRole))->assertStatus(403);
    }

    public function test_non_super_admin_cannot_grant_unpossessed_permissions_privilege_escalation_guard(): void
    {
        $user = User::factory()->create();
        // Give user role.create and user.view permissions only
        $user->givePermissionTo(PermissionEnum::ROLE_CREATE->value);
        $user->givePermissionTo(PermissionEnum::USER_VIEW->value);

        // User attempts to create a role with user.delete permission (which they do NOT have)
        $response = $this->actingAs($user)->post(route('roles.store'), [
            'name' => 'malicious_role',
            'permissions' => [PermissionEnum::USER_VIEW->value, PermissionEnum::USER_DELETE->value],
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('roles', ['name' => 'malicious_role']);
    }

    public function test_cannot_delete_role_assigned_to_users(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        $role = Role::create(['name' => 'support', 'guard_name' => 'web']);
        $assignedUser = User::factory()->create();
        $assignedUser->assignRole($role);

        $response = $this->actingAs($superAdmin)->delete(route('roles.destroy', $role));

        $response->assertSessionHasErrors(['role']);
        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    public function test_authorized_user_can_create_update_and_delete_unused_role(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        // 1. Create Role
        $createResponse = $this->actingAs($superAdmin)->post(route('roles.store'), [
            'name' => 'auditor',
            'permissions' => [PermissionEnum::USER_VIEW->value, PermissionEnum::ROLE_VIEW->value],
        ]);

        $createResponse->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'auditor']);

        $role = Role::findByName('auditor', 'web');
        $this->assertTrue($role->hasPermissionTo(PermissionEnum::USER_VIEW->value));
        $this->assertTrue($role->hasPermissionTo(PermissionEnum::ROLE_VIEW->value));

        // 2. Update Role
        $updateResponse = $this->actingAs($superAdmin)->put(route('roles.update', $role), [
            'name' => 'senior_auditor',
            'permissions' => [PermissionEnum::USER_VIEW->value],
        ]);

        $updateResponse->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'senior_auditor']);

        $role->refresh();
        $this->assertTrue($role->hasPermissionTo(PermissionEnum::USER_VIEW->value));
        $this->assertFalse($role->hasPermissionTo(PermissionEnum::ROLE_VIEW->value));

        // 3. Delete Role
        $deleteResponse = $this->actingAs($superAdmin)->delete(route('roles.destroy', $role));

        $deleteResponse->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
