<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery\MockInterface;

test('google redirect route can be called', function () {
    $response = $this->get(route('auth.google'));

    $response->assertRedirect();
    $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
});

test('new user can authenticate via google callback', function () {
    $abstractUser = Mockery::mock(SocialiteUser::class, function (MockInterface $mock) {
        $mock->shouldReceive('getId')->andReturn('google-id-12345');
        $mock->shouldReceive('getName')->andReturn('Pendaki Merbabu');
        $mock->shouldReceive('getEmail')->andReturn('pendaki@example.com');
        $mock->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');
        $mock->shouldReceive('getNickname')->andReturn(null);
    });

    Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);

    $response = $this->get(route('auth.google.callback'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'pendaki@example.com',
        'google_id' => 'google-id-12345',
        'avatar' => 'https://lh3.googleusercontent.com/avatar.jpg',
    ]);

    $response->assertRedirect(route('home', absolute: false));
});

test('existing user can login and link google_id via google callback', function () {
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'google_id' => null,
    ]);

    $abstractUser = Mockery::mock(SocialiteUser::class, function (MockInterface $mock) {
        $mock->shouldReceive('getId')->andReturn('google-id-99999');
        $mock->shouldReceive('getName')->andReturn('Existing User');
        $mock->shouldReceive('getEmail')->andReturn('existing@example.com');
        $mock->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/new-avatar.jpg');
        $mock->shouldReceive('getNickname')->andReturn(null);
    });

    Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);

    $response = $this->get(route('auth.google.callback'));

    $this->assertAuthenticatedAs($existingUser);
    $this->assertDatabaseHas('users', [
        'id' => $existingUser->id,
        'google_id' => 'google-id-99999',
    ]);

    $response->assertRedirect(route('home', absolute: false));
});

test('existing admin logging in with google is redirected to admin dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'is_admin' => true,
    ]);

    $abstractUser = Mockery::mock(SocialiteUser::class, function (MockInterface $mock) {
        $mock->shouldReceive('getId')->andReturn('google-admin-123');
        $mock->shouldReceive('getName')->andReturn('Admin MiddleTrip');
        $mock->shouldReceive('getEmail')->andReturn('admin@example.com');
        $mock->shouldReceive('getAvatar')->andReturn(null);
        $mock->shouldReceive('getNickname')->andReturn(null);
    });

    Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);

    $response = $this->get(route('auth.google.callback'));

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.dashboard'));
});

test('google authentication error redirects back to login with error', function () {
    Socialite::shouldReceive('driver->stateless->user')->andThrow(new Exception('OAuth access denied'));

    $response = $this->get(route('auth.google.callback'));

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors(['email']);
});
