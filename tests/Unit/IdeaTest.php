<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('pertenece a un usuario', function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('puede tener pasos', function () {
    $idea = Idea::factory()->create();

    expect($idea->steps)->toBeInstanceOf(Collection::class);

    $idea->steps()->create([
        'description' => 'Yoy get the idea nice',
    ]);

    expect($idea->fresh()->steps)->toHaveCount(1);
});
