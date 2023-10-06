<?php
use App\Models\User;

it('can access protected route with correct permissions', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get('/protected-route');

    $response->assertStatus(200);
});

it('cannot access protected route without correct permissions', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->get('/protected-route');

    $response->assertStatus(403);
});
