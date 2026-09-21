<?php

it('register a user', function () {
    visit('/register')
        ->fill('username', 'Davor Kissner')
        ->fill('email', 'davito@gmail.com')
        ->fill('password', '1234568910')
        ->click('@submit')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'username' => 'Davor Kissner',
        'email' => 'davito@gmail.com',
    ]);
});
