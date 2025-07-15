<?php

use App\Models\Coach;
use App\Models\Emblem;
use App\Models\Formation;
use App\Models\User;
use App\Models\Player;

beforeEach(function () {
    $this->user = User::where('is_admin', true)->firstOrFail();
    $this->formation = Formation::firstOrFail();
    $this->emblem = Emblem::firstOrFail();
    $this->coach = Coach::firstOrFail();
    $this->player = Player::firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/teams', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name', 'formationId', 'emblemId', 'coachId', 'players'
        ]);
});

test('Position Format', function () {
    $data = [
        'name' => 'Bad Position',
        'formationId' => $this->formation->id,
        'emblemId' => $this->emblem->id,
        'coachId' => $this->coach->id,
        'userId' => $this->user->id,
        'players' => [
            [
                'player_id' => $this->player->id,
                'position' => 'invalid-pos' // wrong format
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/teams', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['players.0.position']);
});

test('Missing Players Array', function () {
    $data = [
        'name' => 'No Players',
        'formationId' => $this->formation->id,
        'emblemId' => $this->emblem->id,
        'coachId' => $this->coach->id,
        'userId' => $this->user->id,
        // players omitted
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/teams', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['players']);
});

test('Invalid player_id', function () {
    $data = [
        'name' => 'Invalid Player ID',
        'formationId' => $this->formation->id,
        'emblemId' => $this->emblem->id,
        'coachId' => $this->coach->id,
        'userId' => $this->user->id,
        'players' => [
            [
                'player_id' => 999999, // ID does not exist
                'position' => 'pos-1'
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/teams', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['players.0.player_id']);
});

test('Invalid position format', function () {
    $invalidPositions = ['position-1', 'pos-11', 'bench-5', 'bench-', 'pos-a'];

    foreach ($invalidPositions as $position) {
        $data = [
            'name' => 'Invalid Position Format',
            'formationId' => $this->formation->id,
            'emblemId' => $this->emblem->id,
            'coachId' => $this->coach->id,
            'userId' => $this->user->id,
            'players' => [
                [
                    'player_id' => $this->player->id,
                    'position' => $position
                ]
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/teams', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['players.0.position']);
    }
});
