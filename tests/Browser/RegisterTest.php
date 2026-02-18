<?php

test('register a user', function () {
    visit('/register')
        ->fill('name', 'Miguel Lopez')
        ->fill('email', 'miguel@gmail.com')
        ->fill('password', 'password!1223')
        ->click('Create Account')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'Miguel Lopez',
        'email' => 'miguel@gmail.com',
    ]);
});

test('register a valid email user', function () {
    visit('/register')
        ->fill('name', 'Miguel Lopez')
        ->fill('email', 'miguel')
        ->fill('password', 'password!1223')
        ->click('Create Account')
        ->assertPathIs('/register');

});
