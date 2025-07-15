<?php

use App\Models\User;
use App\Models\Technique;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'type']);
});

test('Unique Name', function () {
    $existing = Technique::firstOrFail();

    $data = [
        'name' => $existing->name,
        'element' => 'Montaña',
        'type' => 'Bloqueo'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertJsonValidationErrors(['name']);
});

test('Invalid Element Enum', function () {
    $data = [
        'name' => 'New Technique',
        'element' => 'Agua', // Not in enum
        'type' => 'Tiro'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertJsonValidationErrors(['element']);
});

test('Invalid Type Enum', function () {
    $data = [
        'name' => 'Error Technique',
        'element' => 'Fuego',
        'type' => 'AAA' // Not in Enum
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertJsonValidationErrors(['type']);
});

test('Element must be null for Talento', function () {
    $data = [
        'name' => 'Talento with Element',
        'element' => 'Fuego',
        'type' => 'Talento'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertJsonValidationErrors(['element']);
});

test('Element is required if not Talento', function () {
    $data = [
        'name' => 'Technique without Element',
        'element' => null,
        'type' => 'Tiro'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertJsonValidationErrors(['element']);
});
