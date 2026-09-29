<?php

declare(strict_types=1);

use App\IdeaState;
use App\Models\Idea;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('updates the title, description and state', function () {
    $idea = Idea::factory()->create(['state' => IdeaState::PENDING]);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => 'Titulo nuevo',
            'description' => 'Descripcion nueva',
            'state' => IdeaState::COMPLETED->value,
        ])
        ->assertRedirect(route('idea.show', $idea));

    $this->assertDatabaseHas('ideas', [
        'id' => $idea->id,
        'title' => 'Titulo nuevo',
        'description' => 'Descripcion nueva',
        'state' => IdeaState::COMPLETED->value,
    ]);
});

it('replaces the links', function () {
    $idea = Idea::factory()->create(['links' => ['https://laravel.com']]);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
            'links' => ['https://alpinejs.dev'],
        ])
        ->assertRedirect(route('idea.show', $idea));

    expect($idea->fresh()->links->toArray())->toBe(['https://alpinejs.dev']);
});

it('clears the links when none are sent', function () {
    $idea = Idea::factory()->create(['links' => ['https://laravel.com']]);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
        ])
        ->assertRedirect(route('idea.show', $idea));

    expect($idea->fresh()->links->toArray())->toBe([]);
});

it('keeps the steps still present, drops the removed ones and creates the new ones', function () {
    $idea = Idea::factory()->create();

    $kept = Step::factory()->for($idea)->create(['description' => 'Investigar', 'completed' => true]);
    $removed = Step::factory()->for($idea)->create(['description' => 'Descartar']);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
            'steps' => [
                ['id' => $kept->id, 'description' => 'Investigar'],
                ['description' => 'Prototipar'],
            ],
        ])
        ->assertRedirect(route('idea.show', $idea));

    $steps = $idea->fresh()->steps;

    expect($steps->pluck('description')->all())->toBe(['Investigar', 'Prototipar'])
        ->and($kept->fresh()->completed)->toBeTrue();

    $this->assertModelMissing($removed);
});

it('keeps the completion of a step when its description changes', function () {
    $idea = Idea::factory()->create();

    $step = Step::factory()->for($idea)->create(['description' => 'Investigar', 'completed' => true]);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
            'steps' => [['id' => $step->id, 'description' => 'Investigar a fondo']],
        ])
        ->assertRedirect(route('idea.show', $idea));

    expect($step->fresh())
        ->description->toBe('Investigar a fondo')
        ->completed->toBeTrue();
});

it('rejects steps that belong to another idea', function () {
    $idea = Idea::factory()->create();
    $foreignStep = Step::factory()->create(['description' => 'Ajeno']);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
            'steps' => [['id' => $foreignStep->id, 'description' => 'Robado']],
        ])
        ->assertSessionHasErrors(['steps.0.id']);

    expect($foreignStep->fresh()->description)->toBe('Ajeno');
});

it('deletes every step when none are sent', function () {
    $idea = Idea::factory()->has(Step::factory()->count(2))->create();

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
        ])
        ->assertRedirect(route('idea.show', $idea));

    $this->assertDatabaseCount('steps', 0);
});

it('replaces the image and deletes the previous file', function () {
    Storage::fake('public');

    $idea = Idea::factory()->create([
        'image_path' => UploadedFile::fake()->image('vieja.jpg')->store('ideas', 'public'),
    ]);

    $previousPath = $idea->image_path;

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
            'image' => UploadedFile::fake()->image('nueva.jpg'),
        ])
        ->assertRedirect(route('idea.show', $idea));

    $idea->refresh();

    expect($idea->image_path)->not->toBe($previousPath);

    Storage::disk('public')->assertExists($idea->image_path);
    Storage::disk('public')->assertMissing($previousPath);
});

it('keeps the image when no file is sent', function () {
    Storage::fake('public');

    $idea = Idea::factory()->create([
        'image_path' => UploadedFile::fake()->image('foto.jpg')->store('ideas', 'public'),
    ]);

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'description' => $idea->description,
            'state' => $idea->state->value,
        ])
        ->assertRedirect(route('idea.show', $idea));

    expect($idea->fresh()->image_path)->toBe($idea->image_path);

    Storage::disk('public')->assertExists($idea->image_path);
});

it('validates the payload', function () {
    $idea = Idea::factory()->create();

    $this->actingAs($idea->user)
        ->patch(route('idea.update', $idea), [
            'title' => 'ab',
            'description' => '',
            'state' => 'no-existe',
            'links' => ['no-es-una-url'],
            'steps' => [['description' => 'Investigar'], ['description' => 'Investigar']],
        ])
        ->assertSessionHasErrors(['title', 'description', 'state', 'links.0', 'steps.0.description', 'steps.1.description']);

    expect($idea->fresh()->title)->toBe($idea->title);
});

it('does not let a user update someone elses idea', function () {
    $idea = Idea::factory()->create();

    $this->actingAs(User::factory()->create())
        ->patch(route('idea.update', $idea), [
            'title' => 'Robado',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
        ])
        ->assertNotFound();

    expect($idea->fresh()->title)->toBe($idea->title);
});

it('redirects guests to the login page', function () {
    $idea = Idea::factory()->create();

    $this->patch(route('idea.update', $idea), [
        'title' => 'Anonimo',
        'description' => 'Una descripcion valida',
        'state' => IdeaState::PENDING->value,
    ])->assertRedirect(route('login'));
});
