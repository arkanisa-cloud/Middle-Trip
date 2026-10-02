<?php

use App\Models\User;

test('guest cannot access admin routes and is redirected to login', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/login');
});

test('non-admin user cannot access admin routes and receives 403', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/admin');
    $response->assertForbidden();
});

test('admin user can access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/admin');
    $response->assertOk();
});
