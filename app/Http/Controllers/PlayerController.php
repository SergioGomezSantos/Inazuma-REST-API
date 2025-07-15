<?php

namespace App\Http\Controllers;

use App\Filters\PlayerFilter;
use App\Models\Player;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerCollection;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Players",
 *     description="Endpoints for managing players"
 * )
 */
class PlayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/players",
     *     tags={"Players"},
     *     summary="List all players",
     *     description="Returns paginated list of players with optional filtering and relationship inclusion",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         description="Filter by exact player name",
     *         @OA\Schema(type="string", example="Mark")
     *     ),
     *     @OA\Parameter(
     *         name="fullName[eq]",
     *         in="query",
     *         description="Filter by exact full name",
     *         @OA\Schema(type="string", example="Mark Evans")
     *     ),
     *     @OA\Parameter(
     *         name="position[eq]",
     *         in="query",
     *         description="Filter by exact position",
     *         @OA\Schema(
     *             type="string",
     *             enum={"Portero", "Defensa", "Centrocampista", "Delantero"},
     *             example="Portero"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="element[eq]",
     *         in="query",
     *         description="Filter by exact element",
     *         @OA\Schema(
     *             type="string",
     *             enum={"Aire", "Bosque", "Fuego", "Montaña", "Neutro"},
     *             example="Montaña"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="originalTeam[eq]",
     *         in="query",
     *         description="Filter by exact originalTeam name",
     *         @OA\Schema(type="string", example="Raimon")
     *     ),
     *     @OA\Parameter(
     *         name="kick[gt]",
     *         in="query",
     *         description="Filter by exact kick value",
     *         @OA\Schema(type="string", example="80")
     *     ),
     *     @OA\Parameter(
     *         name="control[lt]",
     *         in="query",
     *         description="Filter by exact control value",
     *         @OA\Schema(type="string", example="60")
     *     ),
     *     @OA\Parameter(
     *         name="technique[eq]",
     *         in="query",
     *         description="Filter by exact technique name",
     *         @OA\Schema(type="string", example="Mano Celestial")
     *     ),
     *     @OA\Parameter(
     *         name="includeStats",
     *         in="query",
     *         description="Include player stats in response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTechniques",
     *         in="query",
     *         description="Include player techniques in response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTeams",
     *         in="query",
     *         description="Include teams the player belongs to",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new PlayerFilter();
        $filterResult = $filter->transform($request);
        $queryItems = $filterResult['where'];
        $relationFilters = $filterResult['with'];

        $includeStats = $request->has('includeStats');
        $includeTechniques = $request->has('includeTechniques');
        $includeTeams = $request->has('includeTeams');

        $players = Player::where($queryItems);

        foreach ($relationFilters as $relation => $filters) {
            $players->whereHas($relation, function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            });
        }

        $withRelations = [];
        if ($includeStats) $withRelations[] = 'stats';
        if ($includeTechniques) $withRelations[] = 'techniques';
        if ($includeTeams) $withRelations[] = 'teams';

        if (!empty($withRelations)) {
            $players = $players->with($withRelations);
        }

        return new PlayerCollection($players->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/players",
     *     tags={"Players"},
     *     summary="Create new player",
     *     description="Create a new player with stats and techniques",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlayerRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Player created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StorePlayerRequest $request)
    {
        $player = Player::create($request->only([
            'name',
            'full_name',
            'position',
            'element',
            'original_team',
            'image'
        ]));

        foreach ($request->input('stats') as $statData) {
            $player->stats()->create($statData);
        }

        foreach ($request->input('techniques') as $techniqueData) {
            $player->techniques()->attach($techniqueData['id'], [
                'source' => $techniqueData['source'],
                'with' => $techniqueData['with'] ? json_encode($techniqueData['with']) : null
            ]);
        }

        $player->load('stats', 'techniques');
        return new PlayerResource($player);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/players/{id}",
     *     tags={"Players"},
     *     summary="Get player details",
     *     description="Returns detailed information about a specific player",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="includeStats",
     *         in="query",
     *         description="Include player stats in response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTechniques",
     *         in="query",
     *         description="Include player techniques in response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTeams",
     *         in="query",
     *         description="Include teams the player belongs to",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player details",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found"
     *     )
     * )
     */
    public function show(Player $player)
    {
        $includeStats = request()->has('includeStats');
        $includeTechniques = request()->has('includeTechniques');
        $includeTeams = request()->has('includeTeams');

        $relations = [];
        if ($includeStats) $relations[] = 'stats';
        if ($includeTechniques) $relations[] = 'techniques';
        if ($includeTeams) $relations[] = 'teams';

        if (!empty($relations)) {
            return new PlayerResource($player->loadMissing($relations));
        }

        return new PlayerResource($player);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/players/{id}",
     *     tags={"Players"},
     *     summary="Update player",
     *     description="Update player information including stats and techniques",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlayerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found"
     *     )
     * )
     */
    public function update(UpdatePlayerRequest $request, Player $player)
    {
        $player->update($request->only([
            'name',
            'full_name',
            'position',
            'element',
            'original_team',
            'image'
        ]));

        if ($request->has('stats')) {
            foreach ($request->input('stats') as $statData) {
                if (isset($statData['id'])) {
                    $player->stats()->where('id', $statData['id'])->update($statData);
                } else {
                    $player->stats()->create($statData);
                }
            }
        }

        if ($request->has('techniques')) {
            $techniquesData = [];
            foreach ($request->input('techniques') as $techniqueData) {
                $techniquesData[$techniqueData['id']] = [
                    'source' => $techniqueData['source'] ?? null,
                    'with' => isset($techniqueData['with']) ? json_encode($techniqueData['with']) : null
                ];
            }
            $player->techniques()->sync($techniquesData);
        }

        $player->load('stats', 'techniques');
        return new PlayerResource($player);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/players/{id}",
     *     tags={"Players"},
     *     summary="Delete player",
     *     description="Permanently delete a player",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found"
     *     )
     * )
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return response()->json([
            'message' => 'Player deleted successfully.',
        ], 200);
    }
}