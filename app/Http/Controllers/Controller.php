<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;


#[OA\Info(
    title: 'medical API',
    description: 'API for medical advices and finding doctors.',
    version: '1.0.0',
    )]
    #[OA\SecurityScheme(
        securityScheme: 'sanctum',
        type: 'http',
        scheme: 'bearer',
        bearerFormat: 'JWT',
    )]
    #[OA\Server(
        url: '/api',
        description: 'API Server',
    )]

abstract class Controller
{
}
