<?php

use App\Models\Technique;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('List Techniques', function () {
    $response = $this->getJson('/api/v1/techniques');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'element', 'type']
            ]
        ]);
});

test('Admin can Create Technique', function () {
    $data = [
        'name' => 'New Tiro',
        'element' => 'Fuego',
        'type' => 'Tiro'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('techniques', ['name' => 'New Tiro']);
});

test('Admin can Create Talento without Element', function () {
    $data = [
        'name' => 'New Talento',
        'element' => null,
        'type' => 'Talento'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('techniques', ['name' => 'New Talento']);
});

test('Normal User can not Create Technique', function () {
    $data = [
        'name' => 'bbbb',
        'element' => 'Fuego',
        'type' => 'Tiro'
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/techniques', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/techniques', []);
    $response->assertUnauthorized();
});

test('Admin can Update Technique', function () {
    $technique = Technique::firstOrFail();
    $updateData = ['name' => 'Updated Technique'];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/techniques/{$technique->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'Updated Technique']]);
});

test('Admin can Delete Technique', function () {
    $technique = Technique::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/techniques/{$technique->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Technique deleted successfully.']);
});
