<x-layout title="Tus ideas">
    <header class="mt-10 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Tus ideas</h2>
            <p class="mt-1 text-sm text-muted-foreground">Escribe lo que piensas. Arma un plan.</p>
        </div>

        <a href="/ideas/create" class="btn">Nueva idea</a>
    </header>

    @php($current = request('state'))

    <nav class="mt-8 flex flex-wrap gap-2">
        <a href="/ideas" @class(['btn', 'btn-outlined' => $current])>
            Todas
            <span class="ml-1 opacity-60">{{ $counts['all'] }}</span>
        </a>

        @foreach (\App\IdeaState::cases() as $state)
            <a
                href="/ideas?state={{ $state->value }}"
                @class(['btn', 'btn-outlined' => $current !== $state->value])>
                {{ $state->label() }}
                <span class="ml-1 opacity-60">{{ $counts[$state->value] ?? 0 }}</span>
            </a>
        @endforeach
    </nav>

    @if ($ideas->isNotEmpty())
        <ul class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach ($ideas as $idea)
                <li>
                    <x-idea-card :idea="$idea"/>
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-8 rounded-xl border border-dashed border-border p-10 text-center text-sm text-muted-foreground">
            {{ $current ? 'No tenes ideas en ese estado.' : 'Todavia no creaste ninguna idea.' }}
        </p>
    @endif
</x-layout>
