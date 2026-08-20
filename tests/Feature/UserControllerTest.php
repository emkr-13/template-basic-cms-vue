<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('role:init', [
            '--name' => 'Super Admin',
            '--email' => 'admin@example.com',
            '--password' => 'password123',
        ]);
    }

    public function test_guest_cannot_access_user_routes(): void
    {
        $user = User::factory()->create();

        $this->get(route('users.index'))->assertRedirect(route('login'));
        $this->get(route('users.create'))->assertRedirect(route('login'));
        $this->post(route('users.store'), [])->assertRedirect(route('login'));
        $this->get(route('users.edit', $user))->assertRedirect(route('login'));
        $this->put(route('users.update', $user), [])->assertRedirect(route('login'));
        $this->delete(route('users.destroy', $user))->assertRedirect(route('login'));
        $this->get(route('users.export.pdf'))->assertRedirect(route('login'));
        $this->get(route('users.export.excel'))->assertRedirect(route('login'));
    }

    public function test_user_without_permissions_cannot_access_user_routes(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        $this->actingAs($user)->get(route('users.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('users.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('users.store'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'credential_delivery' => 'temporary_password',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(403);
        $this->actingAs($user)->get(route('users.edit', $targetUser))->assertStatus(403);
        $this->actingAs($user)->put(route('users.update', $targetUser), [
            'name' => 'John Updated',
            'email' => 'john@example.com',
            'status' => UserStatusEnum::ACTIVE->value,
        ])->assertStatus(403);
        $this->actingAs($user)->delete(route('users.destroy', $targetUser))->assertStatus(403);
        $this->actingAs($user)->get(route('users.export.pdf'))->assertStatus(403);
        $this->actingAs($user)->get(route('users.export.excel'))->assertStatus(403);
    }

    public function test_non_super_admin_cannot_edit_or_delete_super_admin_user(): void
    {
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole(RoleEnum::SUPER_ADMIN->value);

        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo(PermissionEnum::USER_UPDATE->value);
        $managerRole->givePermissionTo(PermissionEnum::USER_DELETE->value);

        $managerUser = User::factory()->create();
        $managerUser->assignRole($managerRole);

        // Attempt edit
        $this->actingAs($managerUser)->get(route('users.edit', $superAdminUser))->assertStatus(403);

        // Attempt update
        $this->actingAs($managerUser)->put(route('users.update', $superAdminUser), [
            'name' => 'Hacked Super Admin',
            'email' => $superAdminUser->email,
            'status' => UserStatusEnum::ACTIVE->value,
        ])->assertStatus(403);

        // Attempt delete
        $this->actingAs($managerUser)->delete(route('users.destroy', $superAdminUser))->assertStatus(403);
    }

    public function test_user_cannot_update_or_delete_self_via_user_management(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        // Attempt self update
        $updateResponse = $this->actingAs($superAdmin)->put(route('users.update', $superAdmin), [
            'name' => 'Updated Self',
            'email' => $superAdmin->email,
            'status' => UserStatusEnum::ACTIVE->value,
        ]);
        $updateResponse->assertStatus(422);

        // Attempt self delete
        $deleteResponse = $this->actingAs($superAdmin)->delete(route('users.destroy', $superAdmin));
        $deleteResponse->assertStatus(422);
    }

    public function test_last_super_admin_guard_blocks_demoting_last_super_admin(): void
    {
        $admin1 = User::factory()->create();
        $admin1->assignRole(RoleEnum::SUPER_ADMIN->value);

        $admin2 = User::factory()->create();
        $admin2->assignRole(RoleEnum::SUPER_ADMIN->value);

        $staffRole = Role::create(['name' => 'staff', 'guard_name' => 'web']);

        // Demote admin1 while admin2 exists -> works (count becomes 1)
        $this->actingAs($admin2)->put(route('users.update', $admin1), [
            'name' => $admin1->name,
            'email' => $admin1->email,
            'status' => UserStatusEnum::ACTIVE->value,
            'role' => 'staff',
        ])->assertRedirect(route('users.index'));

        // Now admin2 is the LAST super admin in the system!
        // Admin2 tries to change admin2's role -> returns 422 (cannot update self)
        $this->actingAs($admin2)->put(route('users.update', $admin2), [
            'name' => $admin2->name,
            'email' => $admin2->email,
            'status' => UserStatusEnum::ACTIVE->value,
            'role' => 'staff',
        ])->assertStatus(422);
    }

    public function test_non_super_admin_cannot_assign_super_admin_or_unpossessed_permissions_privilege_escalation(): void
    {
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo(PermissionEnum::USER_CREATE->value);
        $managerRole->givePermissionTo(PermissionEnum::USER_VIEW->value);

        $managerUser = User::factory()->create();
        $managerUser->assignRole($managerRole);

        // Attempt to create a user with super_admin role -> returns 403
        $response = $this->actingAs($managerUser)->post(route('users.store'), [
            'name' => 'Escalated Admin',
            'email' => 'escalated@example.com',
            'credential_delivery' => 'temporary_password',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => RoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'escalated@example.com']);
    }

    public function test_authorized_user_can_index_search_and_filter_users(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        User::factory()->create(['name' => 'Alice Smith', 'email' => 'alice@example.com', 'status' => UserStatusEnum::ACTIVE]);
        User::factory()->create(['name' => 'Bob Jones', 'email' => 'bob@example.com', 'status' => UserStatusEnum::DISABLED]);

        // Search test
        $searchResponse = $this->actingAs($superAdmin)->get(route('users.index', ['search' => 'Alice']));
        $searchResponse->assertOk();
        $searchResponse->assertInertia(fn ($page) => $page
            ->component('Users/Index')
            ->has('users.data', 1)
            ->where('users.data.0.name', 'Alice Smith')
        );

        // Filter status test
        $filterResponse = $this->actingAs($superAdmin)->get(route('users.index', ['status' => UserStatusEnum::DISABLED->value]));
        $filterResponse->assertOk();
        $filterResponse->assertInertia(fn ($page) => $page
            ->component('Users/Index')
            ->has('users.data', 1)
            ->where('users.data.0.name', 'Bob Jones')
        );
    }

    public function test_can_create_user_with_direct_password_or_invitation(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        // 1. Direct Password Delivery
        $response1 = $this->actingAs($superAdmin)->post(route('users.store'), [
            'name' => 'Direct User',
            'email' => 'direct@example.com',
            'credential_delivery' => 'temporary_password',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response1->assertRedirect(route('users.index'));
        $directUser = User::where('email', 'direct@example.com')->first();
        $this->assertNotNull($directUser);
        $this->assertEquals(UserStatusEnum::ACTIVE, $directUser->status);
        $this->assertTrue($directUser->must_change_password);

        // 2. Email Invitation Delivery
        Password::shouldReceive('sendResetLink')->once()->andReturn(Password::RESET_LINK_SENT);

        $response2 = $this->actingAs($superAdmin)->post(route('users.store'), [
            'name' => 'Invited User',
            'email' => 'invited@example.com',
            'credential_delivery' => 'invitation',
        ]);

        $response2->assertRedirect(route('users.index'));
        $invitedUser = User::where('email', 'invited@example.com')->first();
        $this->assertNotNull($invitedUser);
        $this->assertEquals(UserStatusEnum::INVITATION_PENDING, $invitedUser->status);
        $this->assertFalse($invitedUser->must_change_password);
        $this->assertNotNull($invitedUser->invited_at);
        $this->assertNotNull($invitedUser->invitation_sent_at);
    }

    public function test_can_update_user_and_assign_role(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        $user = User::factory()->create(['name' => 'Old Name', 'status' => UserStatusEnum::ACTIVE]);
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

        $response = $this->actingAs($superAdmin)->put(route('users.update', $user), [
            'name' => 'New Name',
            'email' => $user->email,
            'status' => UserStatusEnum::DISABLED->value,
            'role' => 'editor',
        ]);

        $response->assertRedirect(route('users.index'));
        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals(UserStatusEnum::DISABLED, $user->status);
        $this->assertTrue($user->hasRole('editor'));
    }

    public function test_can_delete_user_soft_delete(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        $targetUser = User::factory()->create();

        $response = $this->actingAs($superAdmin)->delete(route('users.destroy', $targetUser));

        $response->assertRedirect(route('users.index'));
        $this->assertSoftDeleted('users', ['id' => $targetUser->id]);
    }

    public function test_can_export_users_pdf_and_excel(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        // PDF Export
        $pdfResponse = $this->actingAs($superAdmin)->get(route('users.export.pdf'));
        $pdfResponse->assertOk();
        $pdfResponse->assertHeader('content-type', 'application/pdf');

        // Excel Export
        $excelResponse = $this->actingAs($superAdmin)->get(route('users.export.excel'));
        $excelResponse->assertOk();
    }
}
