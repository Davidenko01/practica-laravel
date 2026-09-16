<x-layout>
    <form method="POST" action="/ideas/{{ $idea->id }}">
        @csrf
        {{-- Browser solo reconoce POST, para PUT o PATCH se pone method --}}
        @method('PATCH')
        <div class="col-span-full">
            <label for="description" class="block text-sm/6 font-medium text-primary">Editar Idea</label>
            <div class="mt-2">
                <textarea id="description" name="description" rows="3" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-primary outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">{{ $idea->description }}</textarea>
            </div>
            <x-forms.error name="description" />
            <div class="mt-6 flex items-center gap-x-4">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                <button type="submit" form="delete-idea-form" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-800 cursor-pointer">Delete</button>
            </div>
        </div>
    </form>
    <form method="POST" id="delete-idea-form" action="/ideas/{{ $idea->id }}">
        @csrf
        @method('DELETE')
    </form>
</x-layout>

