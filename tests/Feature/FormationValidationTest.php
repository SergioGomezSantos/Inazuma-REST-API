<?php

use App\Models\Formation;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::where('is_admin', true)->firstOrFail();
    $this->user = User::where('is_admin', false)->firstOrFail();
});

test('Required Fields', function () {
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/formations', []);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'layout']);
});

test('Unique Name', function () {
    $existingFormation = Formation::firstOrFail();
    $invalidData = [
        'name' => $existingFormation->name, // Duplicate name
        'layout' => '4-3-3'
    ];
    
    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/formations', $invalidData);
    
    $response->assertJsonValidationErrors(['name']);
});

test('Layout Regex', function () {
    $invalidData = [
        'name' => "New Formation",
        'layout' => '4-4',        // Regex Error
    ];

    $response = $this->actingAs($this->admin, 'sanctum')
        ->postJson('/api/v1/formations', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['layout']);
});
