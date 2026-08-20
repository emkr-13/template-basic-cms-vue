<?php

namespace Tests\Feature\Console;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakeSuperAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_super_admin_via_command_options(): void
    {
        $this->artisan('make:super-admin', [
            '--name' => 'Admin Utama',
            '--email' => 'superadmin@example.com',
            '--password' => 'password123',
        ])
            ->expectsOutputToContain('Super Admin [superadmin@example.com] has been successfully created/updated.')
            ->assertExitCode(0);

        $user = User::where('email', 'superadmin@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('Admin Utama', $user->name);
        $this->assertEquals(UserStatusEnum::ACTIVE, $user->status);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasRole(RoleEnum::SUPER_ADMIN->value));
    }

    public function test_can_create_super_admin_interactively(): void
    {
        $this->artisan('make:super-admin')
            ->expectsQuestion('Super Admin Name', 'Interaktif Admin')
            ->expectsQuestion('Super Admin Email Address', 'interactive@example.com')
            ->expectsQuestion('Super Admin Password', 'password123')
            ->expectsOutputToContain('Super Admin [interactive@example.com] has been successfully created/updated.')
            ->assertExitCode(0);

        $user = User::where('email', 'interactive@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('Interaktif Admin', $user->name);
        $this->assertTrue($user->hasRole(RoleEnum::SUPER_ADMIN->value));
    }

    public function test_validates_invalid_email_option(): void
    {
        $this->artisan('make:super-admin', [
            '--name' => 'Admin Utama',
            '--email' => 'bukan-email',
            '--password' => 'password123',
        ])
            ->expectsOutputToContain('The email must be a valid email address.')
            ->assertExitCode(1);
    }
}
