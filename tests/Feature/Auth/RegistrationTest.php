<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register with valid email', function () {
    $response = $this->post('/register', [
        'name' => 'Pendaki Hebat',
        'email' => 'pendaki.hebat@gmail.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

test('registration rejects dummy emails like coba@mail.com', function () {
    $response = $this->post('/register', [
        'name' => 'Dummy User',
        'email' => 'coba@mail.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('registration rejects coba@coba.com', function () {
    $response = $this->post('/register', [
        'name' => 'Dummy User',
        'email' => 'coba@coba.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});
