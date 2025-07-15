<?php

namespace App\Http\Controllers;

use App\Filters\FormationFilter;
use App\Models\Formation;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Http\Resources\FormationCollection;
use App\Http\Resources\FormationResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Formations",
 *     description="Endpoints for Formations"
 * )
 */
class FormationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/formations",
     *     tags={"Formations"},
     *     summary="List all formations",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="Diamante")
     *     ),
     *     @OA\Parameter(
     *         name="layout[eq]",
     *         in="query",
     *         @OA\Schema(type="string", example="4-3-3")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/FormationCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new FormationFilter();
        $queryItems = $filter->transform($request);

        $formations = Formation::where($queryItems);
        return new FormationCollection($formations->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/formations",
     *     tags={"Formations"},
     *     summary="Create new formation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FormationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Formation created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/FormationResource")
     *     )
     * )
     */
    public function store(StoreFormationRequest $request)
    {
        return new FormationResource(Formation::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/formations/{id}",
     *     tags={"Formations"},
     *     summary="Get specific formation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation details",
     *         @OA\JsonContent(ref="#/components/schemas/FormationResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Formation not found"
     *     )
     * )
     */
    public function show(Formation $formation)
    {
        return new FormationResource($formation);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/formations/{id}",
     *     tags={"Formations"},
     *     summary="Update a formation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FormationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation updated",
     *         @OA\JsonContent(ref="#/components/schemas/FormationResource")
     *     )
     * )
     */
    public function update(UpdateFormationRequest $request, Formation $formation)
    {
        $formation->update($request->all());
        return new FormationResource($formation);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/formations/{id}",
     *     tags={"Formations"},
     *     summary="Delete formation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Formation deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy(Formation $formation)
    {
        $formation->delete();
        return response()->json([
            'message' => 'Formation deleted successfully.',
        ], 200);
    }
}