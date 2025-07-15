<?php

use App\Models\Stat;
use App\Models\User;
use App\Models\Player;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
    $this->player = Player::firstOrFail();
});

test('List Stats', function () {
    $response = $this->getJson('/api/v1/stats');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'playerId', 'GP', 'TP', 'kick', 'body', 'control',
                    'guard', 'speed', 'stamina', 'guts', 'freedom', 'version'
                ]
            ]
        ]);
});

test('Admin can Create Stat', function () {
    $data = [
        'playerId' => $this->player->id,
        'GP' => 10,
        'TP' => 20,
        'kick' => 15,
        'body' => 14,
        'control' => 16,
        'guard' => 13,
        'speed' => 18,
        'stamina' => 17,
        'guts' => 12,
        'freedom' => 11,
        'version' => 'ie2'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/stats', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('stats', ['player_id' => $this->player->id, 'version' => 'ie2']);
});

test('Normal User can not Create Stat', function () {
    $data = [
        'playerId' => $this->player->id,
        'GP' => 5,
        'TP' => 5,
        'kick' => 5,
        'body' => 5,
        'control' => 5,
        'guard' => 5,
        'speed' => 5,
        'stamina' => 5,
        'guts' => 5,
        'freedom' => 5,
        'version' => 'ie1'
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/stats', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/stats', []);
    $response->assertUnauthorized();
});

test('Admin can Update Stat', function () {
    $stat = Stat::firstOrFail();
    $updateData = ['GP' => 99];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/stats/{$stat->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['GP' => 99]]);
});

test('Admin can Delete Stat', function () {
    $stat = Stat::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/stats/{$stat->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Stat deleted successfully.']);
});
