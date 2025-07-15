<?php

namespace App\Http\Controllers;

use App\Filters\StatFilter;
use App\Models\Stat;
use App\Http\Requests\StoreStatRequest;
use App\Http\Requests\UpdateStatRequest;
use App\Http\Resources\StatCollection;
use App\Http\Resources\StatResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Stats",
 *     description="Endpoints for Stats"
 * )
 */
class StatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/stats",
     *     tags={"Stats"},
     *     summary="List all player stats",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="playerId[eq]",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="version[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="ie1")
     *     ),
     *     @OA\Parameter(
     *         name="GP[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="TP[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="kick[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="body[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="control[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="guard[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="speed[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="stamina[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="guts[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Parameter(
     *         name="freedom[gt]",
     *         in="query",
     *         @OA\Schema(type="integer", example=80)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StatCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new StatFilter();
        $queryItems = $filter->transform($request);

        $stats = Stat::where($queryItems);
        return new StatCollection($stats->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/stats",
     *     tags={"Stats"},
     *     summary="Create new player stats",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StatRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stats created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StatResource")
     *     )
     * )
     */
    public function store(StoreStatRequest $request)
    {
        return new StatResource(Stat::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/stats/{id}",
     *     tags={"Stats"},
     *     summary="Get specific player stats",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stats details",
     *         @OA\JsonContent(ref="#/components/schemas/StatResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Stats not found"
     *     )
     * )
     */
    public function show(Stat $stat)
    {
        return new StatResource($stat);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/stats/{id}",
     *     tags={"Stats"},
     *     summary="Update player stats",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StatRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stats updated",
     *         @OA\JsonContent(ref="#/components/schemas/StatResource")
     *     )
     * )
     */
    public function update(UpdateStatRequest $request, Stat $stat)
    {
        $stat->update($request->all());
        return new StatResource($stat);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/stats/{id}",
     *     tags={"Stats"},
     *     summary="Delete player stats",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stats deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Stat deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy(Stat $stat)
    {
        $stat->delete();
        return response()->json([
            'message' => 'Stat deleted successfully.',
        ], 200);
    }
}