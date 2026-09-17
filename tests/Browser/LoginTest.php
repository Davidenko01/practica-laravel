<?php

use App\Models\User;

it('it login a user', function () {
    User::factory()->create([
        'email' => 'davito@gmail.com',
        'password' => '1234568910',
    ]);

    visit('/login')
        ->fill('email', 'davito@gmail.com')
        ->fill('password', '1234568910')
        ->click('@login-button')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();
});

it('it logs out a user', function () {
    $user = User::factory()->create([
        'email' => 'davito@gmail.com',
        'password' => '1234568910',
    ]);

    $this->actingAs($user);
    visit('/ideas')
        ->click('@logout-button');

    $this->assertGuest();
});
