<?php

namespace App\Http\Controllers;

use App\Filters\CoachFilter;
use App\Models\Coach;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Http\Resources\CoachCollection;
use App\Http\Resources\CoachResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Coaches",
 *     description="Endpoints for Coaches"
 * )
 */
class CoachController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/coaches",
     *     tags={"Coaches"},
     *     summary="List all coaches",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Seymour Hillman")
     *     ),
     *     @OA\Parameter(
     *         name="version[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="ie1")
     *     ),
     *     @OA\Parameter(
     *         name="version[ne]",
     *         in="query",
     *         @OA\Schema(type="string", example="ie1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CoachCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new CoachFilter();
        $queryItems = $filter->transform($request);

        $coaches = Coach::where($queryItems);
        return new CoachCollection($coaches->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/coaches",
     *     tags={"Coaches"},
     *     summary="Create new coach",
     *     description="Requires admin privileges",
     *     operationId="createCoach",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CoachRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Coach created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CoachResource")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(StoreCoachRequest $request)
    {
        return new CoachResource(Coach::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/coaches/{id}",
     *     tags={"Coaches"},
     *     summary="Get specific coach",
     *     description="Public endpoint to get coach details",
     *     operationId="getCoach",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Coach ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coach details",
     *         @OA\JsonContent(ref="#/components/schemas/CoachResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coach not found"
     *     ),
     *     security={}
     * )
     */
    public function show(Coach $coach)
    {
        return new CoachResource($coach);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/coaches/{id}",
     *     tags={"Coaches"},
     *     summary="Update a coach",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Coach")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coach updated",
     *         @OA\JsonContent(ref="#/components/schemas/Coach")
     *     )
     * )
     */
    public function update(UpdateCoachRequest $request, Coach $coach)
    {
        $coach->update($request->all());
        return new CoachResource($coach);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/coaches/{id}",
     *     tags={"Coaches"},
     *     summary="Delete coach",
     *     description="Requires admin privileges",
     *     operationId="deleteCoach",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Coach ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coach deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Coach deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coach not found"
     *     )
     * )
     */
    public function destroy(Coach $coach)
    {
        $coach->delete();
        return response()->json([
            'message' => 'Coach deleted successfully.',
        ], 200);
    }
}
