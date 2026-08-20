<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(static fn ($user): ?bool => $user->hasRole(RoleEnum::SUPER_ADMIN->value) ? true : null);

        RateLimiter::for('login', static fn (Request $request): Limit => Limit::perMinute(5)
            ->by(str($request->input('email'))->lower()->append('|'.$request->ip())->toString()));

        RateLimiter::for('password-reset', static fn (Request $request): Limit => Limit::perMinute(3)
            ->by(str($request->input('email'))->lower()->append('|'.$request->ip())->toString()));
    }
}
