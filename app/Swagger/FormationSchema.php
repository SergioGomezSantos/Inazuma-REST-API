<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="FormationRequest",
 *     type="object",
 *     required={"name", "layout"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Diamante",
 *         description="Formation name"
 *     ),
 *     @OA\Property(
 *         property="layout",
 *         type="string",
 *         example="4-3-3",
 *         description="Formation layout pattern"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="FormationResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Formation"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Formation",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Diamante"),
 *     @OA\Property(property="layout", type="string", example="4-3-3"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="FormationCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Formation")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/formations?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/formations?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/formations?page=2")
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
class FormationSchema
{
    // Class exists only for Swagger annotations
}