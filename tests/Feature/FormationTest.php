<?php

use App\Models\Formation;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('List Formations', function () {
    $response = $this->getJson('/api/v1/formations');
    
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'layout']
            ]
        ]);
});

test('Admin can Create Formation', function () {
    $data = [
        'name' => 'New Formation',
        'layout' => '4-3-3'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/formations', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('formations', ['name' => 'New Formation']);
});

test('Normal User can not Create Formation', function () {
    $data = [
        'name' => 'nope',
        'layout' => '1-2-3'
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/formations', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/formations', []);
    $response->assertUnauthorized();
});

test('Admin can Update Formation', function () {
    $formation = Formation::firstOrFail();
    $updateData = ['name' => 'updated name'];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/formations/{$formation->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'updated name']]);
});

test('Admin can Delete Formation', function () {
    $formation = Formation::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/formations/{$formation->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Formation deleted successfully.']);
});