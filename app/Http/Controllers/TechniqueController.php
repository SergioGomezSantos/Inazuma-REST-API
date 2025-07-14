<?php

namespace App\Http\Controllers;

use App\Filters\TechniqueFilter;
use App\Models\Technique;
use App\Http\Requests\StoreTechniqueRequest;
use App\Http\Requests\UpdateTechniqueRequest;
use App\Http\Resources\TechniqueCollection;
use App\Http\Resources\TechniqueResource;
use Illuminate\Http\Request;

class TechniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new TechniqueFilter();
        $queryItems = $filter->transform($request);

        $teams = Technique::where($queryItems);
        return new TechniqueCollection($teams->paginate()->appends($request->query()));
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
    public function store(StoreTechniqueRequest $request)
    {
        return new TechniqueResource(Technique::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Technique $technique)
    {
        return new TechniqueResource($technique);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technique $technique)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechniqueRequest $request, Technique $technique)
    {
        $technique->update($request->all());
        return new TechniqueResource($technique);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technique $technique)
    {
        $technique->delete();
        return response()->json([
            'message' => 'Technique deleted successfully.',
        ], 200);
    }
}
