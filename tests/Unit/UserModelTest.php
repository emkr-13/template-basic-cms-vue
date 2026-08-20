<?php

namespace Tests\Unit;

use App\Enums\PermissionEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_casts_status_to_user_status_enum(): void
    {
        $user = User::factory()->create([
            'status' => UserStatusEnum::INVITATION_PENDING,
        ]);

        $this->assertInstanceOf(UserStatusEnum::class, $user->status);
        $this->assertEquals(UserStatusEnum::INVITATION_PENDING, $user->status);
        $this->assertEquals('invitation_pending', $user->status->value);
    }

    public function test_user_supports_spatie_has_roles_trait(): void
    {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => PermissionEnum::USER_VIEW->value, 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('editor'));
        $this->assertTrue($user->hasPermissionTo(PermissionEnum::USER_VIEW->value));
    }

    public function test_user_soft_deletes(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
        $this->assertNotNull(User::withTrashed()->find($userId));
        $this->assertNull(User::find($userId));
    }
}
