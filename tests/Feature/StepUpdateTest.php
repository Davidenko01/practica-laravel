<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\Step;
use App\Models\User;

it('marks a step as completed', function () {
    $user = User::factory()->create();
    $step = Step::factory()->for(Idea::factory()->for($user))->create(['completed' => false]);

    $this->actingAs($user)
        ->patch(route('step.update', $step), ['completed' => '1'])
        ->assertRedirect();

    expect($step->refresh()->completed)->toBeTrue();
});

it('marks a step as not completed', function () {
    $user = User::factory()->create();
    $step = Step::factory()->for(Idea::factory()->for($user))->create(['completed' => true]);

    $this->actingAs($user)
        ->patch(route('step.update', $step), ['completed' => '0'])
        ->assertRedirect();

    expect($step->refresh()->completed)->toBeFalse();
});

it('requires a valid completed value', function () {
    $user = User::factory()->create();
    $step = Step::factory()->for(Idea::factory()->for($user))->create(['completed' => false]);

    $this->actingAs($user)
        ->patch(route('step.update', $step), ['completed' => 'quiza'])
        ->assertSessionHasErrors('completed');

    expect($step->refresh()->completed)->toBeFalse();
});

it('does not let a user update a step of someone else idea', function () {
    $step = Step::factory()->for(Idea::factory())->create(['completed' => false]);

    $this->actingAs(User::factory()->create())
        ->patch(route('step.update', $step), ['completed' => '1'])
        ->assertNotFound();

    expect($step->refresh()->completed)->toBeFalse();
});

it('does not let a guest update a step', function () {
    $step = Step::factory()->create(['completed' => false]);

    $this->patch(route('step.update', $step), ['completed' => '1'])
        ->assertRedirect(route('login'));

    expect($step->refresh()->completed)->toBeFalse();
});
