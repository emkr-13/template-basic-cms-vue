<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Public', description: 'Endpoint tanpa autentikasi.')]
#[OA\Tag(name: 'Private', description: 'Endpoint dengan Sanctum Bearer Token.')]
class ApiCheckController extends Controller
{
    #[OA\Get(path: '/api/v1/public/check', tags: ['Public'], responses: [new OA\Response(response: 200, description: 'Public API is working')])]
    public function public(): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Public API is working']);
    }

    #[OA\Get(path: '/api/v1/private/check', tags: ['Private'], security: [['bearerAuth' => []]], responses: [new OA\Response(response: 200, description: 'Private API is working'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function private(): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Private API is working']);
    }
}
