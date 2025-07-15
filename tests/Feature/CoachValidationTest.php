<?php

use App\Models\Coach;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', []);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'image', 'version']);
});

test('Unique Name', function () {
    $existingCoach = Coach::firstOrFail();
    $invalidData = [
        'name' => $existingCoach->name, // Duplicate name
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png',
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', $invalidData);
    
    $response->assertJsonValidationErrors(['name']);
});

test('Image URL', function () {
    $invalidData = [
        'name' => 'Test Coach',
        'image' => 'not-a-url', // Invalid URL format
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', $invalidData);
    
    $response->assertJsonValidationErrors(['image']);
});

test('Image Domain', function () {
    $invalidData = [
        'name' => 'Test Coach',
        'image' => 'https://google.com/image.jpg', // Invalid domain
        'version' => 'ie3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', $invalidData);
    
    $response->assertJsonValidationErrors(['image']);
});

test('Version Enum', function () {
    $invalidData = [
        'name' => 'Test Coach',
        'image' => 'https://static.wikia.nocookie.net/inazuma/images/e/e1/Raimon_FF_Emblema.png',
        'version' => 'invalid' // Not in enum
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/coaches', $invalidData);
    
    $response->assertJsonValidationErrors(['version']);
});