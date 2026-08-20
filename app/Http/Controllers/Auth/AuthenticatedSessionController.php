<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak valid.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->user()->status === UserStatusEnum::DISABLED) {
            Auth::logout();

            return back()->withErrors(['email' => 'Akun ini tidak aktif.']);
        }

        ActivityLogService::log(
            'auth.login',
            "Pengguna {$request->user()->name} berhasil login ke dalam sistem.",
            $request->user()
        );

        return redirect()->intended($request->user()->must_change_password
            ? route('password.change.edit')
            : route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            ActivityLogService::log(
                'auth.logout',
                "Pengguna {$user->name} logout dari sistem.",
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
