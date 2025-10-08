<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *     title="Client API Documentation",
 *     version="1.0.0",
 *     description="This documentation covers all admin endpoints",
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *  ),
 *
 * @OA\SecurityScheme(
 *      type="http",
 *      description="Login with mobile and password to get the authentication token",
 *      name="Token based Based",
 *      in="header",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="bearer_token",
 *  )
 */
class Client {}
