<?php

namespace App\Http\Controllers;

use App\Filters\TechniqueFilter;
use App\Models\Technique;
use App\Http\Requests\StoreTechniqueRequest;
use App\Http\Requests\UpdateTechniqueRequest;
use App\Http\Resources\TechniqueCollection;
use App\Http\Resources\TechniqueResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Techniques",
 *     description="Endpoints for Techniques"
 * )
 */
class TechniqueController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/techniques",
     *     tags={"Techniques"},
     *     summary="List all techniques",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Mano Celestial")
     *     ),
     *     @OA\Parameter(
     *         name="element[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Fuego")
     *     ),
     *     @OA\Parameter(
     *         name="element[ne]",
     *         in="query",
     *         @OA\Schema(type="string", example="Aire")
     *     ),
     *     @OA\Parameter(
     *         name="type[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Tiro")
     *     ),
     *     @OA\Parameter(
     *         name="type[ne]",
     *         in="query",
     *         @OA\Schema(type="string", example="Atajo")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new TechniqueFilter();
        $queryItems = $filter->transform($request);

        $techniques = Technique::where($queryItems);
        return new TechniqueCollection($techniques->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/techniques",
     *     tags={"Techniques"},
     *     summary="Create new technique",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Technique created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueResource")
     *     )
     * )
     */
    public function store(StoreTechniqueRequest $request)
    {
        return new TechniqueResource(Technique::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/techniques/{id}",
     *     tags={"Techniques"},
     *     summary="Get specific technique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Technique details",
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Technique not found"
     *     )
     * )
     */
    public function show(Technique $technique)
    {
        return new TechniqueResource($technique);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/techniques/{id}",
     *     tags={"Techniques"},
     *     summary="Update a technique",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Technique updated",
     *         @OA\JsonContent(ref="#/components/schemas/TechniqueResource")
     *     )
     * )
     */
    public function update(UpdateTechniqueRequest $request, Technique $technique)
    {
        $technique->update($request->all());
        return new TechniqueResource($technique);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/techniques/{id}",
     *     tags={"Techniques"},
     *     summary="Delete technique",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Technique deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Technique deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy(Technique $technique)
    {
        $technique->delete();
        return response()->json([
            'message' => 'Technique deleted successfully.',
        ], 200);
    }
}