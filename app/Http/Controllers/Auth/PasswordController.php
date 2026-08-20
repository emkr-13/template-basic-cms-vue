<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    public function request(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function email(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', ['email' => $request->string('email')->toString(), 'token' => $request->route('token')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'must_change_password' => false,
                'status' => UserStatusEnum::ACTIVE,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function editChangePassword(): Response
    {
        return Inertia::render('Auth/ChangePassword');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()]]);
        $request->user()->forceFill(['password' => Hash::make($request->string('password')->toString()), 'must_change_password' => false])->save();

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui.');
    }
}
