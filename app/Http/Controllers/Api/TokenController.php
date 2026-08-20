<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IssueApiTokenRequest;
use App\Models\ApiCredential;
use App\Services\ApiTokenService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TokenController extends Controller
{
    public function __construct(private ApiTokenService $apiTokenService) {}

    #[OA\Post(path: '/api/v1/auth/token', tags: ['Authentication'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['client_id', 'client_secret'], properties: [new OA\Property(property: 'client_id', type: 'string'), new OA\Property(property: 'client_secret', type: 'string', format: 'password')])), responses: [new OA\Response(response: 200, description: 'Temporary bearer token issued'), new OA\Response(response: 422, description: 'Invalid credential')])]
    public function store(IssueApiTokenRequest $request): JsonResponse
    {
        $credential = ApiCredential::query()->where('client_id', $request->validated('client_id'))->first();

        if ($credential === null) {
            return response()->json(['message' => 'Invalid API credential.'], 422);
        }

        return response()->json(['success' => true, 'token_type' => 'Bearer', ...$this->apiTokenService->issue($credential, $request->validated('client_secret'))]);
    }
}
