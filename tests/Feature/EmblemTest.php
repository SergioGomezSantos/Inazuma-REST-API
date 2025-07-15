<?php

use App\Models\Emblem;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('List Emblems', function () {
    $response = $this->getJson('/api/v1/emblems');
    
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'image', 'version']
            ]
        ]);
});

test('Admin can Create Emblem', function () {
    $data = [
        'name' => 'New Emblem',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es',
        'version' => 'ie3'
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', $data);

    $response->assertCreated();
    $this->assertDatabaseHas('emblems', ['name' => 'New Emblem']);
});

test('Normal User can not Create Emblem', function () {
    $data = [
        'name' => 'nope',
        'image' => 'https://aaa.com/bb.jpg',
        'version' => 'ie3'
    ];

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/emblems', $data);

    $response->assertForbidden();
});

test('Unauthorized', function () {
    $response = $this->postJson('/api/v1/emblems', []);
    $response->assertUnauthorized();
});

test('Admin can Update Emblem', function () {
    $emblem = Emblem::firstOrFail();
    $updateData = ['name' => 'updated name'];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->putJson("/api/v1/emblems/{$emblem->id}", $updateData);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'updated name']]);
});

test('Admin can Delete Emblem', function () {
    $emblem = Emblem::firstOrFail();

    $response = $this->actingAs($this->admin, 'sanctum')
        ->deleteJson("/api/v1/emblems/{$emblem->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Emblem deleted successfully.']);
});