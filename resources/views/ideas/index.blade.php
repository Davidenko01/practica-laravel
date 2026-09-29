<x-layout title="Tus ideas">
    <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold">Tus ideas</h1>
        <p class="mt-1 text-sm text-muted-foreground">Escribe lo que piensas. Arma un plan.</p>

        <x-card
            x-data
            @click="$dispatch('open-modal', 'create-idea')"
            is="button"
            type="button"
            class="mt-10 cursor-pointer h-32 w-full text-left">
            <p>¿Qué idea tienes?</p>
        </x-card>
    </header>

    @php($current = request('state'))

    <nav class="mt-8 flex flex-wrap gap-2">
        <a href="{{ route('ideas.index') }}" @class(['btn', 'btn-outlined' => $current])>
            Todas
            <span class="ml-1 opacity-60">{{ $counts['all'] }}</span>
        </a>

        @foreach (\App\IdeaState::cases() as $state)
            <a
                href="{{ route('ideas.index', ['state' => $state->value]) }}"
                @class(['btn', 'btn-outlined' => $current !== $state->value])>
                {{ $state->label() }}
                <span class="ml-1 opacity-60">{{ $counts[$state->value] ?? 0 }}</span>
            </a>
        @endforeach
    </nav>

    @if ($ideas->isNotEmpty())
        <ul class="mt-8 grid gap-4 lg:grid-cols-3 sm:grid-cols-2">
            @foreach ($ideas as $idea)
                <li>
                    <x-idea-card class="max-w-lg" :idea="$idea"/>
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-8 rounded-xl border border-dashed border-border p-10 text-center text-sm text-muted-foreground">
            {{ $current ? 'No tenes ideas en ese estado.' : 'Todavia no creaste ninguna idea.' }}
        </p>
    @endif

    <x-modal name="create-idea" title="Nueva Idea" :open="$errors->any()">
        <x-idea-form/>
    </x-modal>
</x-layout>
