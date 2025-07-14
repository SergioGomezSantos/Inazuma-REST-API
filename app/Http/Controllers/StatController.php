<?php

namespace App\Http\Controllers;

use App\Filters\StatFilter;
use App\Models\Stat;
use App\Http\Requests\StoreStatRequest;
use App\Http\Requests\UpdateStatRequest;
use App\Http\Resources\StatCollection;
use Illuminate\Http\Request;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new StatFilter();
        $queryItems = $filter->transform($request);

        $stats = Stat::where($queryItems);
        return new StatCollection($stats->paginate()->appends($request->query()));
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
    public function store(StoreStatRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Stat $stats)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stat $stats)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatRequest $request, Stat $stat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stat $stat)
    {
        //
    }
}
