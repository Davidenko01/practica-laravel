@props(['idea'])

@php
    $stateClasses = match ($idea->state) {
        \App\IdeaState::PENDING => 'bg-input text-muted-foreground',
        \App\IdeaState::IN_PROGRESS => 'bg-primary/20 text-primary',
        \App\IdeaState::COMPLETED => 'bg-primary text-primary-foreground',
    };
@endphp

<a
    href="/ideas/{{ $idea->id }}"
    {{ $attributes->merge(['class' => 'flex h-full flex-col gap-3 rounded-xl border border-border bg-card p-5 transition hover:border-primary']) }}>
    <div class="flex items-start justify-between gap-3">
        <h2 class="font-medium">{{ $idea->title }}</h2>

        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $stateClasses }}">
            {{ $idea->state->label() }}
        </span>
    </div>

    @if ($idea->description)
        <p class="line-clamp-3 text-sm text-muted-foreground">{{ $idea->description }}</p>
    @endif

    @if ($idea->created_at)
        <time datetime="{{ $idea->created_at->toIso8601String() }}" class="mt-auto text-xs text-muted-foreground">
            {{ $idea->created_at->diffForHumans() }}
        </time>
    @endif
</a>
