<?php

namespace App\Console\Commands;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class RoleInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:init
                            {--name= : The name of the super admin user}
                            {--email= : The email address of the super admin user}
                            {--password= : The password for the super admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize system permissions, roles, and Super Admin account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Initializing system permissions and roles...');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value, 'web');
        }

        Role::findOrCreate(RoleEnum::SUPER_ADMIN->value, 'web');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Permissions and roles successfully initialized.');

        $name = $this->option('name');
        $email = $this->option('email');
        $passwordOption = $this->option('password');

        if (blank($name)) {
            $name = text(
                label: 'Super Admin Name',
                placeholder: 'Super Admin',
                default: 'Super Admin',
                required: true,
            );
        }

        if (blank($email)) {
            $email = text(
                label: 'Super Admin Email Address',
                placeholder: 'admin@example.com',
                required: true,
                validate: fn (string $value) => match (true) {
                    ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address must be a valid email.',
                    default => null,
                },
            );
        }

        if (blank($passwordOption)) {
            $password = password(
                label: 'Super Admin Password',
                required: true,
                validate: fn (string $value) => match (true) {
                    strlen($value) < 8 => 'The password must be at least 8 characters long.',
                    default => null,
                },
            );
        } else {
            $password = $passwordOption;
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::withTrashed()->firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'status' => UserStatusEnum::ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        if (! $user->wasRecentlyCreated) {
            $user->fill([
                'name' => $name,
                'password' => Hash::make($password),
                'status' => UserStatusEnum::ACTIVE,
            ]);

            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }

            $user->save();
        }

        if ($user->trashed()) {
            $user->restore();
        }

        $user->syncRoles([RoleEnum::SUPER_ADMIN->value]);

        $this->info("Super Admin [{$user->email}] has been successfully created/updated.");

        return self::SUCCESS;
    }
}
