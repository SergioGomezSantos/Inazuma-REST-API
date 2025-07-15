<?php

use App\Models\Player;
use App\Models\User;
use App\Models\Technique;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('List Players', function () {
    $response = $this->getJson('/api/v1/players');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'fullName', 'position', 'element', 'originalTeam', 'image']
            ]
        ]);
});

test('Admin can Create Player', function () {
    $technique = Technique::firstOrFail();

    $data = [
        'name' => 'Mark',
        'fullName' => 'Mark Evans',
        'position' => 'Portero',
        'element' => 'Fuego',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Mark.png',

        'stats' => [
            [
                'GP' => 120,
                'TP' => 90,
                'kick' => 50,
                'body' => 60,
                'control' => 70,
                'guard' => 100,
                'speed' => 40,
                'stamina' => 80,
                'guts' => 75,
                'freedom' => 65,
                'version' => 'ie1'
            ]
        ],

        'techniques' => [
            [
                'id' => $technique->id,
                'source' => 'ie1',
                'with' => json_encode(['some' => 'value'])
            ]
        ]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('players', ['name' => 'Mark']);
});

test('Normal User can not Create Player', function () {
    $technique = Technique::firstOrFail();

    $data = [
        'name' => 'Banned Player',
        'fullName' => 'Should Fail',
        'position' => 'Delantero',
        'element' => 'Bosque',
        'originalTeam' => 'Alius',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Invalid.png',

        'stats' => [
            [
                'GP' => 100,
                'TP' => 100,
                'kick' => 80,
                'body' => 70,
                'control' => 60,
                'guard' => 90,
                'speed' => 40,
                'stamina' => 100,
                'guts' => 85,
                'freedom' => 50,
                'version' => 'ie2'
            ]
        ],

        'techniques' => [
            [
                'id' => $technique->id,
                'source' => 'ie2'
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/players', []);
    $response->assertUnauthorized();
});

test('Admin can Update Player', function () {
    $player = Player::firstOrFail();

    $updateData = [
        'name' => 'Updated Name',
        'fullName' => $player->fullName,
        'position' => $player->position,
        'element' => $player->element,
        'originalTeam' => $player->originalTeam,
        'image' => $player->image,
        'stats' => $player->stats->map(function ($stat) {
            return [
                'GP' => $stat->GP,
                'TP' => $stat->TP,
                'kick' => $stat->kick,
                'body' => $stat->body,
                'control' => $stat->control,
                'guard' => $stat->guard,
                'speed' => $stat->speed,
                'stamina' => $stat->stamina,
                'guts' => $stat->guts,
                'freedom' => $stat->freedom,
                'version' => $stat->version,
            ];
        })->toArray(),
        'techniques' => $player->techniques->map(function ($tech) {
            return [
                'id' => $tech->id,
                'source' => 'ie1',
            ];
        })->toArray()
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/players/{$player->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'Updated Name']]);
});

test('Admin can Delete Player', function () {
    $player = Player::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/players/{$player->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Player deleted successfully.']);
});
