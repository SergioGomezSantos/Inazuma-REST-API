<?php

use App\Models\Coach;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('List Coaches', function () {
    $response = $this->getJson('/api/v1/coaches');
    
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'image', 'version']
            ]
        ]);
});

test('Admin can Create Coach', function () {
    $data = [
        'name' => 'New Coach',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es',
        'version' => 'ie3'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('coaches', ['name' => 'New Coach']);
});

test('Normal User can not Create Coach', function () {
    $data = [
        'name' => 'nope',
        'image' => 'https://aaa.com/bb.jpg',
        'version' => 'ie3'
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/coaches', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/coaches', []);
    $response->assertUnauthorized();
});

test('Admin can Update Coach', function () {
    $coach = Coach::firstOrFail();
    $updateData = ['name' => 'updated name'];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/coaches/{$coach->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'updated name']]);
});

test('Admin can Delete Coach', function () {
    $coach = Coach::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/coaches/{$coach->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Coach deleted successfully.']);
});