<?php

namespace App\Models;

use Database\Factories\ApiCredentialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\PersonalAccessToken;

#[Fillable(['name', 'client_id', 'client_secret_hash', 'created_by', 'last_used_at', 'revoked_at'])]
#[Hidden(['client_secret_hash'])]
class ApiCredential extends Model
{
    /** @use HasFactory<ApiCredentialFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['last_used_at' => 'datetime', 'revoked_at' => 'datetime'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function issuedTokens(): HasMany
    {
        return $this->hasMany(PersonalAccessToken::class);
    }

    public function isActive(): bool
    {
        return $this->revoked_at === null;
    }
}
