<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="EmblemRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Raimon",
 *         description="Emblem name"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         example="https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es",
 *         description="URL to emblem image"
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
 *     schema="EmblemResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Emblem"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Emblem",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Raimon"),
 *     @OA\Property(property="image", type="string", example="https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es"),
 *     @OA\Property(property="version", type="string", example="ie1"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="EmblemCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Emblem")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/emblems?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/emblems?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/emblems?page=2")
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
class EmblemSchema
{
    // Class exists only for Swagger annotations
}