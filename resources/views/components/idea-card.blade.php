@props(['idea'])

<a
    href="{{ route('idea.show', $idea) }}"
    {{ $attributes->merge(['class' => 'flex h-full flex-col gap-3 rounded-xl border border-border bg-card p-5 transition hover:border-primary']) }}>
    @if ($idea->image_url)
        <img
            src="{{ $idea->image_url }}"
            alt=""
            loading="lazy"
            class="-mx-5 -mt-5 h-40 w-[calc(100%_+_2.5rem)] max-w-none rounded-t-xl object-cover"/>
    @endif

    <div class="flex items-start justify-between gap-3">
        <h2 class="font-medium">{{ $idea->title }}</h2>

        <x-idea-state :state="$idea->state"/>
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
