<?php

use App\Models\User;
use App\Models\Player;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->player = Player::firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/stats', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'playerId', 'GP', 'TP', 'kick', 'body', 'control',
            'guard', 'speed', 'stamina', 'guts', 'freedom', 'version'
        ]);
});

test('Invalid playerId', function () {
    $invalidData = [
        'playerId' => 999999, // ID doesn't exist
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
        ->postJson('/api/v1/stats', $invalidData);

    $response->assertJsonValidationErrors(['playerId']);
});

test('Negative Integer Fields', function () {
    $invalidData = [
        'playerId' => $this->player->id,
        'GP' => -1,
        'TP' => -1,
        'kick' => -1,
        'body' => -1,
        'control' => -1,
        'guard' => -1,
        'speed' => -1,
        'stamina' => -1,
        'guts' => -1,
        'freedom' => -1,
        'version' => 'ie3'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/stats', $invalidData);

    $response->assertJsonValidationErrors([
        'GP', 'TP', 'kick', 'body', 'control', 'guard',
        'speed', 'stamina', 'guts', 'freedom'
    ]);
});

test('Version Enum', function () {
    $invalidData = [
        'playerId' => $this->player->id,
        'GP' => 10,
        'TP' => 10,
        'kick' => 10,
        'body' => 10,
        'control' => 10,
        'guard' => 10,
        'speed' => 10,
        'stamina' => 10,
        'guts' => 10,
        'freedom' => 10,
        'version' => 'xyz' // Invalid version
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/stats', $invalidData);

    $response->assertJsonValidationErrors(['version']);
});
