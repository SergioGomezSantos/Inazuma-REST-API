<?php

namespace App\Http\Controllers;

use App\Filters\FormationFilter;
use App\Models\Formation;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Http\Resources\FormationCollection;
use App\Http\Resources\FormationResource;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new FormationFilter();
        $queryItems = $filter->transform($request);

        $formations = Formation::where($queryItems);
        return new FormationCollection($formations->paginate()->appends($request->query()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormationRequest $request)
    {
        return new FormationResource(Formation::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        return new FormationResource($formation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormationRequest $request, Formation $formation)
    {
        $formation->update($request->all());
        return new FormationResource($formation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        $formation->delete();
        return response()->json([
            'message' => 'Formation deleted successfully.',
        ], 200);
    }
}
