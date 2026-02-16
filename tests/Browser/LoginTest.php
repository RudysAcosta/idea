<?php

use App\Models\User;

test('login a user', function () {
    $user = User::factory()->create([
        'password' => 'password!1223'
    ]);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password!1223')
        ->click('@login-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();
});


test('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')->click('@log-out-button');

    $this->assertGuest();
});
