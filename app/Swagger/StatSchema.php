<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="StatRequest",
 *     type="object",
 *     required={"playerId", "version", "GP", "TP", "kick", "body", "control", "guard", "speed", "stamina", "guts", "freedom"},
 *     @OA\Property(property="playerId", type="integer", example=1),
 *     @OA\Property(property="version", type="string", example="ie1"),
 *     @OA\Property(property="GP", type="integer", example=50),
 *     @OA\Property(property="TP", type="integer", example=100),
 *     @OA\Property(property="kick", type="integer", example=80),
 *     @OA\Property(property="body", type="integer", example=70),
 *     @OA\Property(property="control", type="integer", example=75),
 *     @OA\Property(property="guard", type="integer", example=65),
 *     @OA\Property(property="speed", type="integer", example=90),
 *     @OA\Property(property="stamina", type="integer", example=85),
 *     @OA\Property(property="guts", type="integer", example=95),
 *     @OA\Property(property="freedom", type="integer", example=60)
 * )
 * 
 * @OA\Schema(
 *     schema="StatResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Stat"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Stat",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="player_id", type="integer", example=1),
 *     @OA\Property(property="version", type="string", example="ie1"),
 *     @OA\Property(property="GP", type="integer", example=50),
 *     @OA\Property(property="TP", type="integer", example=100),
 *     @OA\Property(property="kick", type="integer", example=80),
 *     @OA\Property(property="body", type="integer", example=70),
 *     @OA\Property(property="control", type="integer", example=75),
 *     @OA\Property(property="guard", type="integer", example=65),
 *     @OA\Property(property="speed", type="integer", example=90),
 *     @OA\Property(property="stamina", type="integer", example=85),
 *     @OA\Property(property="guts", type="integer", example=95),
 *     @OA\Property(property="freedom", type="integer", example=60),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="StatCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Stat")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/stats?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/stats?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/stats?page=2")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=3),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=35)
 *     )
 * )
 */
class StatSchema
{
    // Class exists only for Swagger annotations
}