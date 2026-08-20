<?php

namespace App\Services;

use App\Models\ApiCredential;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenService
{
    /** @return array{access_token: string, expires_at: string} */
    public function issue(ApiCredential $credential, string $clientSecret): array
    {
        if (! $credential->isActive() || ! Hash::check($clientSecret, $credential->client_secret_hash)) {
            throw ValidationException::withMessages(['client_id' => 'Invalid API credential.']);
        }

        $expiresAt = now()->addHour();
        $token = $credential->creator->createToken("api-credential:{$credential->id}", ['private:check'], $expiresAt);
        $token->accessToken->forceFill(['api_credential_id' => $credential->id])->save();
        $credential->forceFill(['last_used_at' => now()])->save();

        return ['access_token' => $token->plainTextToken, 'expires_at' => $expiresAt->toIso8601String()];
    }

    public function revoke(ApiCredential $credential): void
    {
        PersonalAccessToken::query()->where('api_credential_id', $credential->id)->delete();
        $credential->forceFill(['revoked_at' => now()])->save();
    }
}
