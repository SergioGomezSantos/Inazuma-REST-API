<?php

namespace App\Http\Controllers;

use App\Filters\EmblemFilter;
use App\Models\Emblem;
use App\Http\Requests\StoreEmblemRequest;
use App\Http\Requests\UpdateEmblemRequest;
use App\Http\Resources\EmblemCollection;
use App\Http\Resources\EmblemResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Emblems",
 *     description="Endpoints for Emblems"
 * )
 */
class EmblemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/emblems",
     *     tags={"Emblems"},
     *     summary="List all emblems",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Raimon")
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
     *         @OA\JsonContent(ref="#/components/schemas/EmblemCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new EmblemFilter();
        $queryItems = $filter->transform($request);

        $emblems = Emblem::where($queryItems);
        return new EmblemCollection($emblems->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/emblems",
     *     tags={"Emblems"},
     *     summary="Create new emblem",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmblemRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Emblem created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmblemResource")
     *     )
     * )
     */
    public function store(StoreEmblemRequest $request)
    {
        return new EmblemResource(Emblem::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/emblems/{id}",
     *     tags={"Emblems"},
     *     summary="Get specific emblem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Emblem details",
     *         @OA\JsonContent(ref="#/components/schemas/EmblemResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Emblem not found"
     *     )
     * )
     */
    public function show(Emblem $emblem)
    {
        return new EmblemResource($emblem);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/emblems/{id}",
     *     tags={"Emblems"},
     *     summary="Update an emblem",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmblemRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Emblem updated",
     *         @OA\JsonContent(ref="#/components/schemas/EmblemResource")
     *     )
     * )
     */
    public function update(UpdateEmblemRequest $request, Emblem $emblem)
    {
        $emblem->update($request->all());
        return new EmblemResource($emblem);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/emblems/{id}",
     *     tags={"Emblems"},
     *     summary="Delete emblem",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Emblem deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Emblem deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy(Emblem $emblem)
    {
        $emblem->delete();
        return response()->json([
            'message' => 'Emblem deleted successfully.',
        ], 200);
    }
}