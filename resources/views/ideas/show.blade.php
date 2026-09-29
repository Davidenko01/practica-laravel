<x-layout :title="$idea->title">
    <article class="mt-10 mx-auto max-w-5xl">
        <header class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-3xl font-semibold">{{ $idea->title }}</h2>

                @if ($idea->created_at)
                    <time
                        datetime="{{ $idea->created_at->toIso8601String() }}"
                        class="mt-1 block text-xs text-muted-foreground">
                        Created {{ $idea->created_at->diffForHumans() }}
                    </time>
                @endif
            </div>

            <x-idea-state :state="$idea->state"/>
        </header>

        @if ($idea->image_url)
            <img
                src="{{ $idea->image_url }}"
                alt="Imagen de {{ $idea->title }}"
                class="mt-6 h-auto max-h-80 w-auto max-w-full rounded-xl border border-border object-contain"/>
        @endif

        @if ($idea->description)
            <div class="mt-6 rounded-xl border border-border bg-card p-6">
                <p class="text-sm leading-relaxed whitespace-pre-line">{{ $idea->description }}</p>
            </div>
        @endif

        @if (count($idea->links ?? []))
            <h3 class="font-bold text-xl mt-6">Enlaces</h3>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach ($idea->links as $link)
                    <li>
                        <a
                            href="{{ $link }}"
                            target="_blank"
                            rel="noopener"
                            class="block truncate rounded-xl border border-border bg-card p-4 text-sm text-primary transition hover:border-primary">
                            {{ $link }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($idea->steps->isNotEmpty())
            <h3 class="font-bold text-xl mt-6">Pasos</h3>
            <ol class="mt-4 space-y-3">
                @foreach ($idea->steps as $step)
                    <li class="flex items-center gap-3 rounded-xl border border-border bg-card p-4">
                        <span class="text-xs text-muted-foreground">{{ $loop->iteration }}</span>

                        <form x-data method="POST" action="{{ route('step.update', $step) }}" class="flex items-center">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="completed" value="0">

                            <input
                                type="checkbox"
                                id="step-{{ $step->id }}"
                                name="completed"
                                value="1"
                                @checked($step->completed)
                                @change="$el.form.submit()"
                                class="size-4 cursor-pointer accent-primary"
                            />
                        </form>

                        <label
                            for="step-{{ $step->id }}"
                            @class(['flex-1 cursor-pointer text-sm', 'line-through text-muted-foreground' => $step->completed])>
                            {{ $step->description }}
                        </label>
                    </li>
                @endforeach
            </ol>
        @endif

        <div class="mt-8 flex items-center gap-3">
            <button x-data type="button" @click="$dispatch('open-modal', 'edit-idea')" class="btn">Editar</button>

            <form method="POST" action="{{ route('idea.destroy', $idea) }}">
                @csrf
                @method('DELETE')

                <button class="btn btn-outlined text-red-500!">Eliminar</button>
            </form>

            <a href="{{ route('ideas.index') }}" class="btn btn-outlined ml-auto">Volver</a>
        </div>

        <x-modal name="edit-idea" title="Editar Idea" :open="$errors->any()">
            <x-idea-form :idea="$idea"/>
        </x-modal>
    </article>
</x-layout>
