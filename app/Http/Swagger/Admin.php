<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *     title="Admin API Documentation",
 *     version="1.0.0",
 *     description="This documentation covers all admin endpoints",
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="sanctum",
 *     securityScheme="sanctum",
 *     name="Sanctum Token",
 *     in="header",
 *     description="Enter Sanctum token in format: Bearer {token}"
 * )
 */
class Admin {}
