<?php

namespace App\Http\Controllers;

use App\Filters\EmblemFilter;
use App\Models\Emblem;
use App\Http\Requests\StoreEmblemRequest;
use App\Http\Requests\UpdateEmblemRequest;
use App\Http\Resources\EmblemCollection;
use App\Http\Resources\EmblemResource;
use Illuminate\Http\Request;

class EmblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new EmblemFilter();
        $queryItems = $filter->transform($request);

        $emblems = Emblem::where($queryItems);
        return new EmblemCollection($emblems->paginate()->appends($request->query()));
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
    public function store(StoreEmblemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Emblem $emblem)
    {
        return new EmblemResource($emblem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emblem $emblem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmblemRequest $request, Emblem $emblem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emblem $emblem)
    {
        //
    }
}
