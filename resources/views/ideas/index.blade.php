<x-layout>
    @if($ideas->count())
    <div class="mt-6 text-white mx-auto">
        <h2 class="font-bold">Tus ideas:</h2>
        <ul class="mt-6 grid grid-cols-2 gap-x-6 gap-y-4">
            @foreach($ideas as $idea)
                <x-idea-card href="/ideas/{{ $idea -> id }}">
                    {{ $idea -> description }}
                </x-idea-card>
            @endforeach
        </ul>
    </div>
    @else
        <p class="mt-6">Todavía no has creado ninguna idea. Puedes crear una nueva haciendo click en:</p>
    @endif
        <p class="mt-6"><a href="/ideas/create" class="text-primary underline">CREAR NUEVA IDEA</a></p>
</x-layout>

