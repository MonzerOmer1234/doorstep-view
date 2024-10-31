<?php

namespace App\OpenApi;

use Dotenv\Store\File\Paths;
use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: "1.0.0",
        title: "API Documentation",
        description: "API Documentation",
        
    ),
    OA\SecurityScheme(
        securityScheme: "bearerAuth",
        type: "http",
        scheme: "bearer",
        bearerFormat: "JWT",
        name: "Authorization",
        in: "header"
    )
]
class OpenApi
{
}
