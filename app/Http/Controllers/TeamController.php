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

/**
 * @OA\Tag(
 *     name="Teams",
 *     description="Endpoints for managing football teams"
 * )
 */
class TeamController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *     path="/api/v1/teams",
     *     tags={"Teams"},
     *     summary="List all teams with optional filters",
     *     description="Returns paginated list of teams. Different results are returned based on user role (admin, authenticated user, or guest).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name[eq]",
     *         in="query",
     *         description="Filter by exact team name",
     *         @OA\Schema(type="string", example="Raimon")
     *     ),
     *     @OA\Parameter(
     *         name="userId[eq]",
     *         in="query",
     *         description="Filter by exact user ID who owns the team",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="playerName[eq]",
     *         in="query",
     *         description="Filter teams containing players with exact name",
     *         @OA\Schema(type="string", example="Mark")
     *     ),
     *     @OA\Parameter(
     *         name="playerFullName[eq]",
     *         in="query",
     *         description="Filter teams containing players with exact full name",
     *         @OA\Schema(type="string", example="Mark Evans")
     *     ),
     *     @OA\Parameter(
     *         name="includePlayers",
     *         in="query",
     *         description="Include players in the response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeStats",
     *         in="query",
     *         description="Include player stats (requires includePlayers)",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTechniques",
     *         in="query",
     *         description="Include player techniques (requires includePlayers)",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TeamCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new TeamFilter();
        $filterResult = $filter->transform($request);
        $queryItems = $filterResult['where'];
        $relationFilters = $filterResult['with'];

        $includePlayers = $request->has('includePlayers');
        $includeStats = $request->has('includeStats');
        $includeTechniques = $request->has('includeTechniques');

        $relations = [];
        if ($includePlayers) {
            $relations = ['players'];
            if ($includeStats) $relations[] = 'players.stats';
            if ($includeTechniques) $relations[] = 'players.techniques';
        }

        $user = $request->user();

        // Admin
        if ($user?->is_admin) {
            $teams = Team::query();

            if (!empty($queryItems)) {
                $teams->where($queryItems);
            }

            foreach ($relationFilters as $relationPath => $filters) {
                $teams->whereHas($relationPath, function ($query) use ($filters) {
                    foreach ($filters as $filter) {
                        $query->where($filter['column'], $filter['operator'], $filter['value']);
                    }
                });
            }

            if (!empty($relations)) {
                $teams->with($relations);
            }

            return new TeamCollection($teams->paginate()->appends($request->query()));
        }

        // Normal User
        if ($user) {
            $globalQuery = Team::query()
                ->when(!empty($queryItems), fn($q) => $q->where($queryItems))
                ->limit(54);

            foreach ($relationFilters as $relationPath => $filters) {
                $globalQuery->whereHas($relationPath, function ($query) use ($filters) {
                    foreach ($filters as $filter) {
                        $query->where($filter['column'], $filter['operator'], $filter['value']);
                    }
                });
            }

            if (!empty($relations)) {
                $globalQuery->with($relations);
            }

            $globalTeams = $globalQuery->get();

            $ownQuery = Team::query()
                ->where('user_id', $user->id)
                ->when(!empty($queryItems), fn($q) => $q->where($queryItems));

            foreach ($relationFilters as $relationPath => $filters) {
                $ownQuery->whereHas($relationPath, function ($query) use ($filters) {
                    foreach ($filters as $filter) {
                        $query->where($filter['column'], $filter['operator'], $filter['value']);
                    }
                });
            }

            if (!empty($relations)) {
                $ownQuery->with($relations);
            }

            $ownTeams = $ownQuery->get();
            $combined = $ownTeams->concat($globalTeams)->unique('id')->values();

            // Manual Paginate
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

        // Unauthenticated
        $query = Team::query()
            ->when(!empty($queryItems), fn($q) => $q->where($queryItems))
            ->limit(54);

        foreach ($relationFilters as $relationPath => $filters) {
            $query->whereHas($relationPath, function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            });
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return new TeamCollection($query->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/teams",
     *     tags={"Teams"},
     *     summary="Create a new team",
     *     description="Creates a new team with the specified players and their positions",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeamRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Team created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TeamResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/v1/teams/{id}",
     *     tags={"Teams"},
     *     summary="Get team details",
     *     description="Returns detailed information about a specific team",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="includePlayers",
     *         in="query",
     *         description="Include players in the response",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeStats",
     *         in="query",
     *         description="Include player stats (requires includePlayers)",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="includeTechniques",
     *         in="query",
     *         description="Include player techniques (requires includePlayers)",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team details",
     *         @OA\JsonContent(ref="#/components/schemas/TeamResource")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - not authorized to view this team"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Team not found"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/v1/teams/{id}",
     *     tags={"Teams"},
     *     summary="Update team information",
     *     description="Updates team details and player positions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeamRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TeamResource")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - not authorized to update this team"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/v1/teams/{id}",
     *     tags={"Teams"},
     *     summary="Delete a team",
     *     description="Permanently deletes a team",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Team deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - not authorized to delete this team"
     *     )
     * )
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
