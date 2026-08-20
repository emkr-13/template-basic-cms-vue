<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\ApiCredential;
use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiCredentialApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_check_does_not_require_authentication(): void
    {
        $this->getJson('/api/v1/public/check')
            ->assertOk()
            ->assertExactJson(['success' => true, 'message' => 'Public API is working']);
    }

    public function test_private_check_requires_a_valid_bearer_token(): void
    {
        $this->getJson('/api/v1/private/check')->assertUnauthorized();
    }

    public function test_credential_can_issue_token_for_private_check_and_revoke_it(): void
    {
        $user = User::factory()->create();
        Role::create(['name' => RoleEnum::SUPER_ADMIN->value, 'guard_name' => 'web']);
        $user->assignRole(RoleEnum::SUPER_ADMIN->value);
        $credential = ApiCredential::factory()->for($user, 'creator')->create([
            'client_secret_hash' => Hash::make('credential-secret'),
        ]);

        $response = $this->postJson('/api/v1/auth/token', [
            'client_id' => $credential->client_id,
            'client_secret' => 'credential-secret',
        ])->assertOk()->assertJsonPath('token_type', 'Bearer');

        $token = $response->json('access_token');
        $this->assertDatabaseHas('personal_access_tokens', ['api_credential_id' => $credential->id]);
        $this->getJson('/api/v1/private/check', ['Authorization' => "Bearer {$token}"])
            ->assertOk()
            ->assertExactJson(['success' => true, 'message' => 'Private API is working']);

        app(ApiTokenService::class)->revoke($credential);
        $this->assertDatabaseMissing('personal_access_tokens', ['api_credential_id' => $credential->id]);
        app('auth')->forgetGuards();

        $this->getJson('/api/v1/private/check', ['Authorization' => "Bearer {$token}"])->assertUnauthorized();
    }

    public function test_only_super_admin_can_manage_credentials(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('api-credentials.index'))
            ->assertForbidden();
    }

    public function test_invalid_credential_does_not_issue_a_token(): void
    {
        $this->postJson('/api/v1/auth/token', ['client_id' => 'unknown', 'client_secret' => 'invalid'])
            ->assertUnprocessable();
    }
}
