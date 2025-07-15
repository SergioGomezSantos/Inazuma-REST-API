<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="TechniqueRequest",
 *     type="object",
 *     required={"name", "element", "type"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Mano Celestial",
 *         description="Technique name"
 *     ),
 *     @OA\Property(
 *         property="element",
 *         type="string",
 *         example="Fuego",
 *         description="Technique element"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="Tiro",
 *         description="Technique type"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TechniqueResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Technique"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Technique",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Fire Tornado"),
 *     @OA\Property(property="element", type="string", example="fire"),
 *     @OA\Property(property="type", type="string", example="shoot"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="TechniqueCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Technique")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/techniques?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/techniques?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/techniques?page=2")
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
class TechniqueSchema
{
    // Class exists only for Swagger annotations
}