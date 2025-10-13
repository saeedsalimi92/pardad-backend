<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *     title="Client API Documentation",
 *     version="1.0.0",
 *     description="This documentation covers all client endpoints",
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *  ),
 *
 * @OA\Schema(
 *   schema="PaginationResource",
 *   type="object",
 *   @OA\Property(property="current_page", type="integer", example=1),
 *   @OA\Property(property="per_page", type="integer", example=10),
 *   @OA\Property(property="total", type="integer", example=1000),
 *   @OA\Property(property="last_page", type="integer", example=100)
 * )
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
