<?php

use App\Models\Coach;
use App\Models\Emblem;
use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use App\Models\Player;

beforeEach(function () {

    $this->user = User::where('is_admin', false)->firstOrFail();
    $this->otherUser = User::where('is_admin', false)->where('id', '!=', $this->user->id)->firstOrFail();

    $this->formation = Formation::firstOrFail();
    $this->emblem = Emblem::firstOrFail();
    $this->coach = Coach::firstOrFail();
    $this->player = Player::firstOrFail();
});

function createValidTeamForUser($user, $player, $formation, $emblem, $coach)
{
    $team = Team::create([
        'name' => 'Team for ' . $user->id,
        'formation_id' => $formation->id,
        'emblem_id' => $emblem->id,
        'coach_id' => $coach->id,
        'user_id' => $user->id,
    ]);

    $team->players()->attach($player->id, ['position' => 'pos-0']);

    return $team;
}

test('User sees only their own teams in index', function () {

    createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);
    createValidTeamForUser($this->otherUser, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson('/api/v1/teams');

    $response->assertOk();

    $response->assertJsonFragment(['userId' => $this->user->id]);
    $response->assertJsonMissing(['userId' => $this->otherUser->id]);
});

test('Admin sees all teams in index', function () {

    $admin = User::where('is_admin', true)->firstOrFail();
    $team1 = createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);
    $team2 = createValidTeamForUser($this->otherUser, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/teams');

    $response->assertOk();
    $this->assertGreaterThan(54, $response->json('meta.total'));
});

test('User can view own team with show', function () {
    $team = createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson("/api/v1/teams/{$team->id}");

    $response->assertOk()
        ->assertJson(['data' => ['name' => $team->name]]);
});

test('User CANNOT view another user team', function () {
    $team = createValidTeamForUser($this->otherUser, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson("/api/v1/teams/{$team->id}");

    $response->assertForbidden();
});

test('Admin can view any team', function () {
    $admin = User::where('is_admin', true)->firstOrFail();
    $team = createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/teams/{$team->id}");

    $response->assertOk()
        ->assertJson(['data' => ['name' => $team->name]]);
});

test('User can Create Team', function () {
    $data = [
        'name' => 'New Team',
        'formationId' => $this->formation->id,
        'emblemId' => $this->emblem->id,
        'coachId' => $this->coach->id,
        'players' => [
            [
                'player_id' => $this->player->id,
                'position' => 'pos-0'
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/teams', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('teams', ['name' => 'New Team']);
});

test('User can Update own Team', function () {
    $team = createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);

    $update = [
        'name' => 'Updated Team',
        'formationId' => $this->formation->id,
        'emblemId' => $this->emblem->id,
        'coachId' => $this->coach->id,
        'userId' => $this->user->id,
        'players' => [
            ['player_id' => $this->player->id, 'position' => 'pos-1']
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->putJson("/api/v1/teams/{$team->id}", $update);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'Updated Team']]);
});

test('User can Delete own Team', function () {
    $team = createValidTeamForUser($this->user, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->deleteJson("/api/v1/teams/{$team->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Team deleted successfully.']);

    $this->assertDatabaseMissing('teams', ['id' => $team->id]);
});

test('User CANNOT update team of another user', function () {
    $team = createValidTeamForUser($this->otherUser, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->putJson("/api/v1/teams/{$team->id}", [
            'name' => 'Malicious Update',
            'formationId' => $this->formation->id,
            'emblemId' => $this->emblem->id,
            'coachId' => $this->coach->id,
            'userId' => $this->otherUser->id,
            'players' => [
                ['player_id' => $this->player->id, 'position' => 'pos-0']
            ]
        ]);

    $response->assertForbidden();
});

test('User CANNOT delete team of another user', function () {
    $team = createValidTeamForUser($this->otherUser, $this->player, $this->formation, $this->emblem, $this->coach);

    $response = $this->actingAs($this->user, 'sanctum')
        ->deleteJson("/api/v1/teams/{$team->id}");

    $response->assertForbidden();
});
