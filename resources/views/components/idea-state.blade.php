@props(['state'])

@php
    $classes = match ($state) {
        \App\IdeaState::PENDING => 'bg-amber-500/15 text-amber-400 ring-amber-500/30',
        \App\IdeaState::IN_PROGRESS => 'bg-sky-500/15 text-sky-400 ring-sky-500/30',
        \App\IdeaState::COMPLETED => 'bg-emerald-500/15 text-emerald-400 ring-emerald-500/30',
    };
@endphp

<span {{ $attributes->merge(['class' => "shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset $classes"]) }}>
    {{ $state->label() }}
</span>
