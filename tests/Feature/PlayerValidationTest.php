<?php

use App\Models\User;
use App\Models\Technique;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->technique = Technique::firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name', 'fullName', 'position', 'element', 'originalTeam', 'image',
            'stats', 'techniques'
        ]);
});

test('Invalid Position or Element', function () {
    $data = [
        'name' => 'Test',
        'fullName' => 'Test Player',
        'position' => 'Centro',
        'element' => 'Agua',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/2/22/%28R%29_Mark_%28PR%29.png/revision/latest?cb=20230912133740&path-prefix=es',
        'stats' => [[
            'GP' => 1, 'TP' => 1, 'kick' => 1, 'body' => 1, 'control' => 1,
            'guard' => 1, 'speed' => 1, 'stamina' => 1, 'guts' => 1, 'freedom' => 1, 'version' => 'ie1'
        ]],
        'techniques' => [['id' => $this->technique->id, 'source' => 'ie1']]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertJsonValidationErrors(['position', 'element']);
});

test('Image URL', function () {
    $data = [
        'name' => 'Test',
        'fullName' => 'Test Player',
        'position' => 'Portero',
        'element' => 'Montaña',
        'originalTeam' => 'Raimon',
        'image' => 'https://invalid.com/image.jpg',
        'stats' => [[
            'GP' => 1, 'TP' => 1, 'kick' => 1, 'body' => 1, 'control' => 1,
            'guard' => 1, 'speed' => 1, 'stamina' => 1, 'guts' => 1, 'freedom' => 1, 'version' => 'ie1'
        ]],
        'techniques' => [['id' => $this->technique->id, 'source' => 'ie1']]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertJsonValidationErrors(['image']);
});

test('Invalid Stats or Version', function () {
    $data = [
        'name' => 'Test',
        'fullName' => 'Test Player',
        'position' => 'Portero',
        'element' => 'Montaña',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/2/22/%28R%29_Mark_%28PR%29.png/revision/latest?cb=20230912133740&path-prefix=es',
        'stats' => [[
            'GP' => -5, // invalid
            'TP' => 10,
            'kick' => 10,
            'body' => 10,
            'control' => 10,
            'guard' => 10,
            'speed' => 10,
            'stamina' => 10,
            'guts' => 10,
            'freedom' => 10,
            'version' => 'invalid'
        ]],
        'techniques' => [['id' => $this->technique->id, 'source' => 'ie1']]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertJsonValidationErrors(['stats.0.GP', 'stats.0.version']);
});

test('Invalid Technique Format', function () {
    $data = [
        'name' => 'Test',
        'fullName' => 'Test Player',
        'position' => 'Portero',
        'element' => 'Montaña',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Test.png',
        'stats' => [[
            'GP' => 10, 'TP' => 10, 'kick' => 10, 'body' => 10,
            'control' => 10, 'guard' => 10, 'speed' => 10,
            'stamina' => 10, 'guts' => 10, 'freedom' => 10, 'version' => 'ie1'
        ]],
        'techniques' => [
            ['id' => 999999, 'source' => 'wrong'], // id does not exist
        ]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertJsonValidationErrors(['techniques.0.id', 'techniques.0.source']);
});

test('Missing Stats', function () {
    $data = [
        'name' => 'No Stats',
        'fullName' => 'Missing Stats',
        'position' => 'Delantero',
        'element' => 'Aire',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/2/22/%28R%29_Mark_%28PR%29.png/revision/latest?cb=20230912133740&path-prefix=es',
        'techniques' => [['id' => $this->technique->id, 'source' => 'ie1']]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['stats']);
});

test('Missing Techniques', function () {
    $data = [
        'name' => 'No Techs',
        'fullName' => 'Missing Techniques',
        'position' => 'Centrocampista',
        'element' => 'Bosque',
        'originalTeam' => 'Raimon',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/2/22/%28R%29_Mark_%28PR%29.png/revision/latest?cb=20230912133740&path-prefix=es',
        'stats' => [[
            'GP' => 10, 'TP' => 10, 'kick' => 10, 'body' => 10,
            'control' => 10, 'guard' => 10, 'speed' => 10,
            'stamina' => 10, 'guts' => 10, 'freedom' => 10, 'version' => 'ie1'
        ]]
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/players', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['techniques']);
});
