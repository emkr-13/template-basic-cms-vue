<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_by_email_and_ip_address(): void
    {
        $payload = ['email' => 'user@example.com', 'password' => 'incorrect-password'];

        foreach (range(1, 5) as $attempt) {
            $this->post(route('login.store'), $payload)->assertRedirect();
        }

        $this->post(route('login.store'), $payload)->assertTooManyRequests();
    }

    public function test_password_reset_is_rate_limited_by_email_and_ip_address(): void
    {
        $payload = ['email' => 'user@example.com'];

        foreach (range(1, 3) as $attempt) {
            $this->post(route('password.email'), $payload)->assertRedirect();
        }

        $this->post(route('password.email'), $payload)->assertTooManyRequests();
    }
}
