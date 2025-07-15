<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="PlayerRequest",
 *     type="object",
 *     required={"name", "full_name", "position", "element", "original_team", "image", "stats", "techniques"},
 *     @OA\Property(property="name", type="string", example="Mark", maxLength=255),
 *     @OA\Property(property="full_name", type="string", example="Mark Evans", maxLength=255),
 *     @OA\Property(
 *         property="position", 
 *         type="string", 
 *         example="Portero",
 *         enum={"Portero", "Defensa", "Centrocampista", "Delantero"}
 *     ),
 *     @OA\Property(
 *         property="element", 
 *         type="string", 
 *         example="Montaña",
 *         enum={"Aire", "Bosque", "Fuego", "Montaña", "Neutro"},
 *         nullable=true
 *     ),
 *     @OA\Property(property="original_team", type="string", example="Raimon", maxLength=255, nullable=true),
 *     @OA\Property(property="image", type="string", example="https://static.wikia.nocookie.net/inazuma/images/2/22/%28R%29_Mark_%28PR%29.png/revision/latest?cb=20230912133740&path-prefix=es", maxLength=255),
 *     @OA\Property(
 *         property="stats",
 *         type="array",
 *         @OA\Items(
 *             required={"version", "GP", "TP", "kick", "body", "control", "guard", "speed", "stamina", "guts", "freedom"},
 *             @OA\Property(property="version", type="string", example="ie1", maxLength=255),
 *             @OA\Property(property="GP", type="integer", example=50, minimum=0),
 *             @OA\Property(property="TP", type="integer", example=100, minimum=0),
 *             @OA\Property(property="kick", type="integer", example=80, minimum=0, maximum=99),
 *             @OA\Property(property="body", type="integer", example=70, minimum=0, maximum=99),
 *             @OA\Property(property="control", type="integer", example=75, minimum=0, maximum=99),
 *             @OA\Property(property="guard", type="integer", example=65, minimum=0, maximum=99),
 *             @OA\Property(property="speed", type="integer", example=90, minimum=0, maximum=99),
 *             @OA\Property(property="stamina", type="integer", example=85, minimum=0, maximum=99),
 *             @OA\Property(property="guts", type="integer", example=95, minimum=0, maximum=99),
 *             @OA\Property(property="freedom", type="integer", example=60, minimum=0, maximum=99)
 *         )
 *     ),
 *     @OA\Property(
 *         property="techniques",
 *         type="array",
 *         @OA\Items(
 *             required={"id"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="source", type="string", example="default", maxLength=255),
 *             @OA\Property(
 *                 property="with",
 *                 type="object",
 *                 nullable=true,
 *                 @OA\Property(property="player_id", type="integer", example=2)
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PlayerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/Player"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Player",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Mark", maxLength=255),
 *     @OA\Property(property="full_name", type="string", example="Mark Evans", maxLength=255),
 *     @OA\Property(
 *         property="position", 
 *         type="string", 
 *         example="Portero",
 *         enum={"Portero", "Defensa", "Centrocampista", "Delantero"}
 *     ),
 *     @OA\Property(
 *         property="element", 
 *         type="string", 
 *         example="Montaña",
 *         enum={"Aire", "Bosque", "Fuego", "Montaña", "Neutro"},
 *         nullable=true
 *     ),
 *     @OA\Property(property="original_team", type="string", example="Raimon", maxLength=255, nullable=true),
 *     @OA\Property(property="image", type="string", example="https://example.com/mark.png", maxLength=255),
 *     @OA\Property(
 *         property="stats",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Stat")
 *     ),
 *     @OA\Property(
 *         property="techniques",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Mano Celestial"),
 *             @OA\Property(property="element", type="string", example="Montaña"),
 *             @OA\Property(property="type", type="string", example="Tiro"),
 *             @OA\Property(
 *                 property="pivot",
 *                 type="object",
 *                 @OA\Property(property="source", type="string", example="default"),
 *                 @OA\Property(
 *                     property="with",
 *                     type="object",
 *                     nullable=true,
 *                     @OA\Property(property="player_id", type="integer", example=2)
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="teams",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Raimon"),
 *             @OA\Property(
 *                 property="pivot",
 *                 type="object",
 *                 @OA\Property(property="position", type="string", example="Portero"),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PlayerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Player")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/players?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/players?page=3"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/players?page=2")
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
class PlayerSchema
{
    // Class exists only for Swagger annotations
}