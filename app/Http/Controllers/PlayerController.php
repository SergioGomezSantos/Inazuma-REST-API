<?php

namespace App\Http\Controllers;

use App\Filters\PlayerFilter;
use App\Models\Player;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerCollection;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new PlayerFilter();
        $queryItems = $filter->transform($request);
        $includeStats = $request->has('includeStats');
        $includeTechniques = $request->has('includeTechniques');

        $players = Player::where($queryItems);

        if ($includeStats) {
            $players = $players->with('stats');
        }

        if ($includeTechniques) {
            $players = $players->with('techniques');
        }

        if ($includeStats && $includeTechniques) {
        $players = $players->with(['stats', 'techniques']);
        }

        return new PlayerCollection($players->paginate()->appends($request->query()));
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
    public function store(StorePlayerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return new PlayerResource($player);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlayerRequest $request, Player $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //
    }
}
