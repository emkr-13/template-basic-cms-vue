<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
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

    public function test_super_admin_can_view_activity_monitor(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'test.action',
            'description' => 'Test log description',
        ]);

        $response = $this->actingAs($superAdmin)->get(route('audit-logs.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('AuditLogs/Index')
            ->has('logs.data', 1)
            ->has('stats')
        );
    }

    public function test_non_super_admin_cannot_access_activity_monitor(): void
    {
        $regularUser = User::factory()->create();

        $response = $this->actingAs($regularUser)->get(route('audit-logs.index'));

        $response->assertForbidden();
    }

    public function test_login_records_activity_log(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'Password123!',
        ]);

        $this->post(route('login.store'), [
            'email' => 'user@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'auth.login',
        ]);
    }

    public function test_user_management_changes_are_recorded(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);
        $managedUser = User::factory()->create();

        $this->actingAs($superAdmin)->put(route('users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => $managedUser->email,
            'status' => UserStatusEnum::DISABLED->value,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $superAdmin->id,
            'action' => 'user.updated',
            'subject_type' => User::class,
            'subject_id' => $managedUser->id,
        ]);
    }
}
