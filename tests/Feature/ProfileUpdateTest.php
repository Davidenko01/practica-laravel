<?php

declare(strict_types=1);

use App\Models\User;
use App\Notifications\ProfileUpdated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

it('notifies the user when the profile changes', function () {
    Notification::fake();

    $user = User::factory()->create(['username' => 'Viejo']);

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => 'Nuevo',
            'email' => $user->email,
        ])
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh()->username)->toBe('Nuevo');

    Notification::assertSentTo($user, ProfileUpdated::class);
});

it('does not notify when nothing changed', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => $user->username,
            'email' => $user->email,
        ])
        ->assertRedirect(route('profile.edit'));

    Notification::assertNothingSent();
});

it('keeps the password when the field is left empty', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => 'Nuevo',
            'email' => $user->email,
            'password' => '',
        ]);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

it('hashes the new password and reports it as a change', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'contrasena-nueva',
        ]);

    expect(Hash::check('contrasena-nueva', $user->fresh()->password))->toBeTrue();

    Notification::assertSentTo(
        $user,
        fn (ProfileUpdated $notification) => $notification->toArray($user)['changes'] === ['password'],
    );
});

it('validates the payload', function () {
    Notification::fake();

    $other = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => '',
            'email' => $other->email,
            'password' => '123',
        ])
        ->assertSessionHasErrors(['username', 'email', 'password']);

    Notification::assertNothingSent();
});

it('lets the user keep their own email', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'username' => 'Nuevo',
            'email' => $user->email,
        ])
        ->assertSessionHasNoErrors();
});

it('redirects guests to the login page', function () {
    $this->patch(route('profile.update'), [
        'username' => 'Anonimo',
        'email' => 'anonimo@example.com',
    ])->assertRedirect(route('login'));
});
