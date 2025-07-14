<?php

namespace App\Http\Controllers;

use App\Filters\CoachFilter;
use App\Models\Coach;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Http\Resources\CoachCollection;
use App\Http\Resources\CoachResource;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CoachFilter();
        $queryItems = $filter->transform($request);

        $coaches = Coach::where($queryItems);
        return new CoachCollection($coaches->paginate()->appends($request->query()));
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
    public function store(StoreCoachRequest $request)
    {
        return new CoachResource(Coach::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        return new CoachResource($coach);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coach $coach)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoachRequest $request, Coach $coach)
    {
        $coach->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coach $coach)
    {
        $coach->delete();
        return response()->json([
            'message' => 'Coach deleted successfully.',
        ], 200);
    }
}
