<?php

use App\Models\Emblem;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', []);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'image', 'version']);
});

test('Unique Name', function () {
    $existingEmblem = Emblem::firstOrFail();
    $invalidData = [
        'name' => $existingEmblem->name, // Duplicate name
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es',
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', $invalidData);
    
    $response->assertJsonValidationErrors(['name']);
});

test('Image URL', function () {
    $invalidData = [
        'name' => 'Test Emblem',
        'image' => 'not-a-url', // Invalid URL format
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', $invalidData);
    
    $response->assertJsonValidationErrors(['image']);
});

test('Image Domain', function () {
    $invalidData = [
        'name' => 'Test Emblem',
        'image' => 'https://google.com/image.jpg', // Invalid domain
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', $invalidData);
    
    $response->assertJsonValidationErrors(['image']);
});

test('Version Enum', function () {
    $invalidData = [
        'name' => 'Test Emblem',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png/revision/latest?cb=20210620190405&path-prefix=es',
        'version' => 'invalid' // Not in enum
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/emblems', $invalidData);
    
    $response->assertJsonValidationErrors(['version']);
});