<x-layout>
    <form method="POST" action="/ideas">
        @csrf
        {{-- Browser solo reconoce POST, para PUT o PATCH se pone method --}}
        <fieldset class="mt-6 fieldset bg-base-200 border-base-300 rounded-box mx-auto w-xs border p-4">
            <legend class="fieldset-legend">Create Idea</legend>
            <label class="description" for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="3"
                class="textarea textarea-neutral"></textarea>

            <x-forms.error name="description" />
            <button class="btn btn-neutral mt-4">Create</button>
        </fieldset>
    </form>
</x-layout>
