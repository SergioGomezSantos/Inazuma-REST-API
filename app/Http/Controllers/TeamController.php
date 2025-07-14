<?php

namespace App\Http\Controllers;

use App\Filters\TeamFilter;
use App\Models\Team;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new TeamFilter();
        $queryItems = $filter->transform($request);
        $includePlayers = $request->has('includePlayers');
        $includeStats = $request->has('includeStats');
        $includeTechniques = $request->has('includeTechniques');

        $teams = Team::where($queryItems);

        if ($includePlayers) {

            if ($includeStats && $includeTechniques) {
                $teams = $teams->with(['players.stats', 'players.techniques']);
            } elseif ($includeStats) {
                $teams = $teams->with(['players.stats']);
            } elseif ($includeTechniques) {
                $teams = $teams->with(['players.techniques']);
            } else {
                $teams = $teams->with('players');
            }
        }

        return new TeamCollection($teams->paginate()->appends($request->query()));
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
    public function store(StoreTeamRequest $request)
    {
        $team = Team::create($request->only([
            'name',
            'formation_id',
            'emblem_id',
            'coach_id',
            'user_id'
        ]));

        foreach ($request->players as $playerData) {
            $team->players()->attach($playerData['id'], [
                'position' => $playerData['position'],
            ]);
        }

        $team->load('players');
        return new TeamResource($team);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $includePlayers = request()->has('includePlayers');
        $includeStats = request()->has('includeStats');
        $includeTechniques = request()->has('includeTechniques');

        if ($includePlayers) {

            if ($includeStats && $includeTechniques) {
                return new TeamResource($team->loadMissing(['players.stats', 'players.techniques']));
            } elseif ($includeStats) {
                return new TeamResource($team->loadMissing('players.stats'));
            } elseif ($includeTechniques) {
                return new TeamResource($team->loadMissing('players.techniques'));
            } else {
                return new TeamResource($team->loadMissing('players'));
            }
        }

        return new TeamResource($team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team->update($request->only([
            'name',
            'full_name',
            'position',
            'element',
            'original_team',
            'image'
        ]));


        if ($request->has('players')) {
            $playerSyncData = [];

            foreach ($request->players as $playerData) {
                $playerSyncData[$playerData['id']] = [
                    'position' => $playerData['position']
                ];
            }

            $team->players()->sync($playerSyncData);
        }

        $team->load('players');
        return new TeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json([
            'message' => 'Team deleted successfully.',
        ], 200);
    }
}
