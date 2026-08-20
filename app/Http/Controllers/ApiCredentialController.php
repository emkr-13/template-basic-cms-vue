<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApiCredentialRequest;
use App\Models\ApiCredential;
use App\Services\ActivityLogService;
use App\Services\ApiTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ApiCredentialController extends Controller
{
    public function __construct(private ApiTokenService $apiTokenService) {}

    public function index(): Response
    {
        return Inertia::render('ApiCredentials/Index', ['credentials' => ApiCredential::query()->latest()->get()->map(fn (ApiCredential $credential): array => [
            'id' => $credential->id,
            'name' => $credential->name,
            'client_id' => $credential->client_id,
            'last_used_at' => $credential->last_used_at?->format('d M Y H:i'),
            'revoked_at' => $credential->revoked_at?->format('d M Y H:i'),
            'created_at' => $credential->created_at->format('d M Y H:i'),
            'status' => $credential->isActive() ? 'active' : 'revoked',
        ])]);
    }

    public function store(StoreApiCredentialRequest $request): RedirectResponse
    {
        $secret = Str::random(48);
        $credential = ApiCredential::query()->create([
            'name' => $request->validated('name'),
            'client_id' => 'api_'.Str::lower(Str::random(20)),
            'client_secret_hash' => Hash::make($secret),
            'created_by' => $request->user()->id,
        ]);

        ActivityLogService::log(
            'api_credential.created',
            "Pengguna {$request->user()->name} membuat API credential {$credential->name}.",
            $credential,
            ['client_id' => $credential->client_id]
        );

        return redirect()->route('api-credentials.index')->with('apiCredential', ['name' => $credential->name, 'client_id' => $credential->client_id, 'client_secret' => $secret]);
    }

    public function destroy(ApiCredential $apiCredential): RedirectResponse
    {
        $this->apiTokenService->revoke($apiCredential);

        ActivityLogService::log(
            'api_credential.revoked',
            'Pengguna '.request()->user()->name." merevoke API credential {$apiCredential->name}.",
            $apiCredential,
            ['client_id' => $apiCredential->client_id]
        );

        return redirect()->route('api-credentials.index')->with('success', 'API credential berhasil direvoke.');
    }
}
