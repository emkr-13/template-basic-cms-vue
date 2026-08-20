<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Exports\UsersExport;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Users/Index', [
            'users' => $this->usersQuery($request)->paginate(15)->withQueryString()->through(fn (User $user): array => $this->userData($user)),
            'filters' => $request->only('search', 'status'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Form', ['user' => null, 'roles' => $this->assignableRoles()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $isInvitation = $data['credential_delivery'] === 'invitation';

        $user = DB::transaction(function () use ($data, $isInvitation): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $isInvitation ? Hash::make(Str::password(64)) : Hash::make($data['password']),
                'status' => $isInvitation ? UserStatusEnum::INVITATION_PENDING : UserStatusEnum::ACTIVE,
                'must_change_password' => ! $isInvitation,
                'invited_at' => $isInvitation ? now() : null,
            ]);
            if (! empty($data['role'])) {
                $user->syncRoles([$this->validatedAssignableRole($data['role'])]);
            } else {
                $user->syncRoles([]);
            }

            return $user;
        });

        if ($isInvitation) {
            $status = Password::sendResetLink(['email' => $user->email]);

            if ($status !== Password::RESET_LINK_SENT) {
                return redirect()->route('users.edit', $user)->withErrors(['email' => 'User dibuat, tetapi email undangan gagal dikirim. Kirim ulang setelah mailer dikonfigurasi.']);
            }

            $user->update(['invitation_sent_at' => now()]);
        }

        return redirect()->route('users.index')->with('success', $isInvitation ? 'User dibuat dan undangan email dikirim.' : 'User berhasil dibuat.');
    }

    public function edit(User $user): Response
    {
        $this->guardUserManagement($user);

        return Inertia::render('Users/Form', [
            'user' => [...$this->userData($user), 'role' => $user->roles->first()?->name],
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->guardUserManagement($user);
        abort_if($user->is($request->user()), 422, 'Anda tidak dapat mengubah akun sendiri melalui User Management.');
        $data = $request->validated();
        $targetRole = ! empty($data['role']) ? $data['role'] : null;
        $this->guardLastSuperAdmin($user, $targetRole);

        $user->update(['name' => $data['name'], 'email' => $data['email'], 'status' => $data['status']]);
        if ($targetRole) {
            $user->syncRoles([$this->validatedAssignableRole($targetRole)]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->guardUserManagement($user);
        abort_if($user->is(request()->user()), 422, 'Anda tidak dapat menghapus akun sendiri.');
        $this->guardLastSuperAdmin($user, null);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $users = $this->usersQuery($request)->get();

        return Pdf::loadView('exports.users-pdf', ['users' => $users])->download('users.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new UsersExport($this->usersQuery($request)->get()), 'users.xlsx');
    }

    private function usersQuery(Request $request)
    {
        return User::query()->with('roles')->when($request->string('search')->isNotEmpty(), function ($query) use ($request): void {
            $search = $request->string('search')->toString();
            $query->where(fn ($users) => $users->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        })->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))->latest();
    }

    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status->value,
            'roles' => $user->roles->pluck('name')->values(),
            'created_at' => $user->created_at->format('d M Y H:i'),
        ];
    }

    private function guardUserManagement(User $user): void
    {
        abort_if($user->hasRole(RoleEnum::SUPER_ADMIN->value) && ! request()->user()->hasRole(RoleEnum::SUPER_ADMIN->value), 403);
    }

    private function guardLastSuperAdmin(User $user, ?string $replacementRole): void
    {
        if (! $user->hasRole(RoleEnum::SUPER_ADMIN->value) || $replacementRole === RoleEnum::SUPER_ADMIN->value) {
            return;
        }

        $superAdminRole = Role::findByName(RoleEnum::SUPER_ADMIN->value, 'web');
        abort_if($superAdminRole->users()->count() <= 1, 422, 'Super Admin terakhir tidak dapat dihapus atau dipindahkan ke role lain.');
    }

    private function assignableRoles(): array
    {
        $actor = request()->user();

        return Role::query()->with('permissions')->orderBy('name')->get()
            ->filter(function (Role $role) use ($actor): bool {
                if ($role->name === RoleEnum::SUPER_ADMIN->value) {
                    return $actor->hasRole(RoleEnum::SUPER_ADMIN->value);
                }

                return $actor->hasRole(RoleEnum::SUPER_ADMIN->value)
                    || $role->permissions->pluck('name')->diff($actor->getAllPermissions()->pluck('name'))->isEmpty();
            })->map(fn (Role $role): array => ['id' => $role->id, 'name' => $role->name])->values()->all();
    }

    private function validatedAssignableRole(?string $roleName): ?string
    {
        if (empty($roleName)) {
            return null;
        }

        abort_unless(collect($this->assignableRoles())->contains('name', $roleName), 403);

        return $roleName;
    }
}
