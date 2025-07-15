<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="user@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         example="password123"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     @OA\Property(
 *         property="token",
 *         type="string",
 *         example="1|abcdef1234567890"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="user@example.com"),
 *         @OA\Property(property="is_admin", type="boolean", example=false)
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="LogoutResponse",
 *     type="object",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Session Closed"
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthSchemas
{
    // Clase solo para agrupar anotaciones Swagger
}