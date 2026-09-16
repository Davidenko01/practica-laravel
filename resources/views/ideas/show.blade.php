<x-layout>
    <form method="POST" action="/ideas/{{ $idea->id }}">
        @csrf
        {{-- Browser solo reconoce POST, para PUT o PATCH se pone method --}}
        @method('DELETE')
        <div class="mt-6 text-primary">
            <h2 class="font-bold">Tu idea:</h2>
            <p class="mt-6"> {{ $idea->description }}
        </div>
        <div class="mt-6 flex">
            <a href="/ideas/{{ $idea->id }}/edit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</a>
        </div>
    </form>
</x-layout>
