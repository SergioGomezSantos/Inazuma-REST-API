<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="TeamPosition",
 *     type="string",
 *     enum={
 *         "pos-0", "pos-1", "pos-2", "pos-3", "pos-4", 
 *         "pos-5", "pos-6", "pos-7", "pos-8", "pos-9", "pos-10",
 *         "bench-0", "bench-1", "bench-2", "bench-3", "bench-4"
 *     },
 *     example="pos-1"
 * )
 * 
 * @OA\Schema(
 *     schema="TeamPlayerPivot",
 *     type="object",
 *     @OA\Property(
 *         property="position",
 *         ref="#/components/schemas/TeamPosition"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TeamRequest",
 *     type="object",
 *     required={"name", "formation_id", "emblem_id", "coach_id", "players"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Raimon",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="formation_id",
 *         type="integer",
 *         example=1,
 *         description="ID of the formation"
 *     ),
 *     @OA\Property(
 *         property="emblem_id",
 *         type="integer",
 *         example=1,
 *         description="ID of the emblem"
 *     ),
 *     @OA\Property(
 *         property="coach_id",
 *         type="integer",
 *         example=1,
 *         description="ID of the coach"
 *     ),
 *     @OA\Property(
 *         property="players",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"player_id", "position"},
 *             @OA\Property(
 *                 property="player_id",
 *                 type="integer",
 *                 example=1
 *             ),
 *             @OA\Property(
 *                 property="position",
 *                 ref="#/components/schemas/TeamPosition"
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TeamResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Team"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Team",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Raimon",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="formation_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="formation",
 *         ref="#/components/schemas/Formation"
 *     ),
 *     @OA\Property(
 *         property="emblem_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="emblem",
 *         ref="#/components/schemas/Emblem"
 *     ),
 *     @OA\Property(
 *         property="coach_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="coach",
 *         ref="#/components/schemas/Coach"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="players",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Mark"),
 *             @OA\Property(property="full_name", type="string", example="Mark Evans"),
 *             @OA\Property(property="position", type="string", example="Portero"),
 *             @OA\Property(
 *                 property="pivot",
 *                 ref="#/components/schemas/TeamPlayerPivot"
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TeamCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Team")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/teams?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/teams?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/teams?page=2")
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
 * 
 */
class TeamSchema
{
    // Class exists only for Swagger annotations
}