<?php

namespace App\Http\Controllers;

use App\Filters\TeamFilter;
use App\Models\Team;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TeamController extends Controller
{
    use AuthorizesRequests;

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

        $relations = [];

        if ($includePlayers) {
            if ($includeStats && $includeTechniques) {
                $relations = ['players.stats', 'players.techniques'];
            } elseif ($includeStats) {
                $relations = ['players.stats'];
            } elseif ($includeTechniques) {
                $relations = ['players.techniques'];
            } else {
                $relations = ['players'];
            }
        }

        $user = $request->user();

        // Admin
        if ($user?->is_admin) {
            $teams = Team::query();

            if (!empty($queryItems)) {
                $teams->where($queryItems);
            }

            if (!empty($relations)) {
                $teams->with($relations);
            }

            return new TeamCollection($teams->paginate()->appends($request->query()));
        }

        // Normal User
        if ($user) {
            $globalQuery = Team::query()->when(!empty($queryItems), fn($q) => $q->where($queryItems))->limit(54);

            if (!empty($relations)) {
                $globalQuery->with($relations);
            }

            $globalTeams = $globalQuery->get();

            $ownQuery = Team::query()
                ->where('user_id', $user->id)
                ->when(!empty($queryItems), fn($q) => $q->where($queryItems));

            if (!empty($relations)) {
                $ownQuery->with($relations);
            }

            $ownTeams = $ownQuery->get();
            $combined = $ownTeams->concat($globalTeams)->unique('id')->values();


            // Manual Paginate to avoid problems on collect
            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 15;
            $results = new Collection($combined);
            $paginated = new LengthAwarePaginator(
                $results->forPage($page, $perPage),
                $results->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return new TeamCollection($paginated);
        }

        // Unathenticated
        $query = Team::query()->when(!empty($queryItems), fn($q) => $q->where($queryItems))->limit(54);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return new TeamCollection($query->paginate()->appends($request->query()));
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
        $team = Team::create([
            'name' => $request->name,
            'formation_id' => $request->formation_id,
            'emblem_id' => $request->emblem_id,
            'coach_id' => $request->coach_id,
            'user_id' => $request->user()->id,
        ]);

        foreach ($request->players as $playerData) {
            $team->players()->attach($playerData['player_id'], [
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
        $this->authorize('view', $team);

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
        $this->authorize('update', $team);

        $team->update($request->only([
            'name',
            'formation_id',
            'emblem_id',
            'coach_id',
            'full_name',
            'position',
            'element',
            'original_team',
            'image',
        ]));

        if ($request->has('players')) {
            $playerSyncData = [];

            foreach ($request->players as $playerData) {
                $playerSyncData[$playerData['player_id']] = [
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
        $this->authorize('update', $team);

        $team->delete();
        return response()->json([
            'message' => 'Team deleted successfully.',
        ], 200);
    }
}
