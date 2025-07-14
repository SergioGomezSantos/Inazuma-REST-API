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
        $player = Player::create($request->only([
            'name', 'fullName', 'position', 'element', 'originalTeam', 'image'
        ]));

        foreach ($request->input('stats') as $statData) {
            $player->stats()->create($statData);
        }

        foreach ($request->input('techniques') as $techniqueData) {
            $player->techniques()->attach($techniqueData['id'], [
                'source' => $techniqueData['source'],
                'with' => $techniqueData['with'] ? json_encode($techniqueData['with']) : null
            ]);
        }

        $player->load('stats', 'techniques');

        return new PlayerResource($player);
    }


    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        $includeStats = request()->has('includeStats');
        $includeTechniques = request()->has('includeTechniques');

        if ($includeStats && $includeTechniques) {
            return new PlayerResource($player->loadMissing(['stats', 'techniques']));
        }

        elseif ($includeStats) {
            return new PlayerResource($player->loadMissing('stats'));
        }

        elseif ($includeTechniques) {
            return new PlayerResource($player->loadMissing('techniques'));

        }
        
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

        $player->update($request->only([
            'name', 'full_name', 'position', 'element', 'original_team', 'image'
        ]));


        if ($request->has('stats')) {
            foreach ($request->input('stats') as $statData) {
                $stat = $player->stats()->find($statData['id']);
                if ($stat) {
                    $stat->update($statData);
                } else {
                    $player->stats()->create($statData);
                }
            }
        }

        if ($request->has('techniques')) {
            foreach ($request->input('techniques') as $techniqueData) {
                $player->techniques()->syncWithoutDetaching([
                    $techniqueData['id'] => [
                        'source' => $techniqueData['source'],
                        'with' => $techniqueData['with'] ? json_encode($techniqueData['with']) : null
                    ]
                ]);
            }
        }

        $player->load('stats', 'techniques');
        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return response()->json([
            'message' => 'Player deleted successfully.',
        ], 200);
    }
}
