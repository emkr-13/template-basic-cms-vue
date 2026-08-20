<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('INITIAL_SUPER_ADMIN_EMAIL');
        $password = env('INITIAL_SUPER_ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('Initial Super Admin was skipped: set INITIAL_SUPER_ADMIN_EMAIL and INITIAL_SUPER_ADMIN_PASSWORD.');

            return;
        }

        $user = User::withTrashed()->firstOrCreate(
            ['email' => $email],
            [
                'name' => env('INITIAL_SUPER_ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make($password),
                'status' => UserStatusEnum::ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        if ($user->trashed()) {
            $user->restore();
        }

        $user->syncRoles([RoleEnum::SUPER_ADMIN->value]);
    }
}
