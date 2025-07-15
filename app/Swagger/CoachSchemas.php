<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="CoachRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name", 
 *         type="string",
 *         example="New Coach",
 *         description="Coach's name"
 *     ),
 *     @OA\Property(
 *         property="image", 
 *         type="string",
 *         example="https://static.wikia.nocookie.net/inazuma/images/b/b8/Hillman_%28Sprite%29.png/revision/latest?cb=20201025114237&path-prefix=es",
 *         description="URL to coach's image"
 *     ),
 *     @OA\Property(
 *         property="version", 
 *         type="string",
 *         example="ie1",
 *         description="Game version"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="CoachResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Coach"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Coach",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Seymour Hillman"),
 *     @OA\Property(property="image", type="string", example="https://static.wikia.nocookie.net/inazuma/images/b/b8/Hillman_%28Sprite%29.png/revision/latest?cb=20201025114237&path-prefix=es"),
 *     @OA\Property(property="version", type="string", example="ie1")
 * )
 * 
 * @OA\Schema(
 *     schema="CoachCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Coach")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/coaches?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/coaches?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/coaches?page=2")
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
class CoachSchemas
{
    // Class exists only for Swagger annotations
}