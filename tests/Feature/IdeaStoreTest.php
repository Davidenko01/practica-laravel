<?php

declare(strict_types=1);

use App\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores an idea', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'Mi idea',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
        ])
        ->assertRedirect(route('ideas.index'));

    $this->assertDatabaseHas('ideas', [
        'user_id' => $user->id,
        'title' => 'Mi idea',
        'description' => 'Una descripcion valida',
        'state' => IdeaState::PENDING->value,
    ]);
});

it('requires title, description and state', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [])
        ->assertSessionHasErrors(['title', 'description', 'state']);

    $this->assertDatabaseCount('ideas', 0);
});

it('stores an idea with links', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'Con links',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'links' => ['https://laravel.com', 'https://alpinejs.dev'],
        ])
        ->assertRedirect(route('ideas.index'));

    expect(Idea::sole()->links->toArray())
        ->toBe(['https://laravel.com', 'https://alpinejs.dev']);
});

it('stores an idea without links', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Sin links',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
        ])
        ->assertRedirect(route('ideas.index'));

    expect(Idea::sole()->links->toArray())->toBe([]);
});

it('rejects invalid and duplicated links', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con links',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'links' => ['no-es-una-url', 'https://laravel.com', 'https://laravel.com'],
        ])
        ->assertSessionHasErrors(['links.0', 'links.1', 'links.2']);

    $this->assertDatabaseCount('ideas', 0);
});

it('stores an idea with steps', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con pasos',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'steps' => [['description' => 'Investigar'], ['description' => 'Prototipar']],
        ])
        ->assertRedirect(route('ideas.index'));

    $idea = Idea::sole();

    expect($idea->steps->pluck('description')->all())->toBe(['Investigar', 'Prototipar'])
        ->and($idea->steps->pluck('completed')->all())->toBe([false, false]);
});

it('stores an idea without steps', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Sin pasos',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
        ])
        ->assertRedirect(route('ideas.index'));

    expect(Idea::sole()->steps)->toBeEmpty();
});

it('rejects empty and duplicated steps', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con pasos',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'steps' => [['description' => ''], ['description' => 'Investigar'], ['description' => 'Investigar']],
        ])
        ->assertSessionHasErrors(['steps.0.description', 'steps.1.description', 'steps.2.description']);

    $this->assertDatabaseCount('ideas', 0);
    $this->assertDatabaseCount('steps', 0);
});

it('deletes the steps when the idea is deleted', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'Con pasos',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'steps' => [['description' => 'Investigar']],
        ]);

    $this->delete(route('idea.destroy', Idea::sole()))->assertRedirect(route('ideas.index'));

    $this->assertDatabaseCount('steps', 0);
});

it('stores an idea with an image', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con imagen',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'image' => UploadedFile::fake()->image('foto.jpg'),
        ])
        ->assertRedirect(route('ideas.index'));

    $idea = Idea::sole();

    Storage::disk('public')->assertExists($idea->image_path);

    expect($idea->image_path)->toStartWith('ideas/')
        ->and($idea->image_url)->toBe(asset('storage/'.$idea->image_path));
});

it('stores an idea without an image', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Sin imagen',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
        ])
        ->assertRedirect(route('ideas.index'));

    expect(Idea::sole()->image_path)->toBeNull()
        ->and(Idea::sole()->image_url)->toBeNull();
});

it('rejects a file that is not an image', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con archivo',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'image' => UploadedFile::fake()->create('notas.pdf', 100, 'application/pdf'),
        ])
        ->assertSessionHasErrors('image');

    $this->assertDatabaseCount('ideas', 0);
});

it('rejects an image bigger than 5mb', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Con imagen pesada',
            'description' => 'Una descripcion valida',
            'state' => IdeaState::PENDING->value,
            'image' => UploadedFile::fake()->image('enorme.jpg')->size(5121),
        ])
        ->assertSessionHasErrors('image');

    $this->assertDatabaseCount('ideas', 0);
});

it('rejects an invalid state', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'Mi idea',
            'description' => 'Una descripcion valida',
            'state' => 'no-existe',
        ])
        ->assertSessionHasErrors('state');
});
