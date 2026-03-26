<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class SwaggerController extends Controller
{
    #[OA\Get(
        path: '/test',
        summary: 'Test endpoint',
        tags: ['Test'],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function test()
    {
        return response()->json(["message" => "Swagger works"]);
    }
}