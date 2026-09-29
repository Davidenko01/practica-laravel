@props(['idea' => null])

@php
    $editing = (bool) $idea;
    $defaultLinks = $idea ? array_values((array) $idea->links) : [];
    $defaultSteps = $idea
        ? $idea->steps->map(fn ($step) => ['id' => $step->id, 'description' => $step->description])->all()
        : [];
@endphp

<form
    x-data="{
        state: @js(old('state', $idea?->state?->value ?? \App\IdeaState::PENDING->value)),
        newLink: '',
        links: @js(array_values((array) old('links', $defaultLinks))),
        addLink() {
            const link = this.newLink.trim();

            if (link === '' || this.links.includes(link)) {
                return;
            }

            this.links.push(link);
            this.newLink = '';
        },
        newStep: '',
        steps: @js(array_values((array) old('steps', $defaultSteps))),
        addStep() {
            const step = this.newStep.trim();

            if (step === '' || this.steps.some((existing) => existing.description === step)) {
                return;
            }

            this.steps.push({ id: null, description: step });
            this.newStep = '';
        }
    }"
    method="POST"
    action="{{ $editing ? route('idea.update', $idea) : route('idea.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PATCH')
    @endif

    <div class="space-y-6">
        <x-forms.input
            label="Titulo"
            name="title"
            :value="$idea?->title"
            placeholder="Ingresa un titulo para tu idea"
            autofocus
            required
            minlength="3"
        />

        <div class="space-y-2">
            <span class="label">Estado</span>
            <div class="flex gap-x-3">
                @foreach (\App\IdeaState::cases() as $case)
                    <button
                        type="button"
                        @click="state = @js($case->value)"
                        class="btn flex-1 h-10"
                        :class="state === @js($case->value) ? '' : 'btn-outlined'"
                    >
                        {{ $case->label() }}
                    </button>
                @endforeach

                <input type="hidden" name="state" :value="state">
            </div>

            <x-forms.error name="state"/>
        </div>

        <x-forms.input
            label="Descripcion"
            name="description"
            type="textarea"
            :value="$idea?->description"
            placeholder="Ingresa una descripcion para tu idea"
            minlength="3"
            required
        />

        <div class="space-y-2">
            <label for="image" class="label">Imagen</label>

            @if ($idea?->image_url)
                <div class="flex items-center gap-x-3">
                    <img src="{{ $idea->image_url }}" alt="" class="h-16 w-16 rounded-lg object-cover"/>
                    <span class="text-sm text-muted-foreground">Subi otra para reemplazarla.</span>
                </div>
            @endif

            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
                class="mt-1 cursor-pointer"/>

            <x-forms.error name="image"/>
        </div>

        <div>
            <fieldset class="space-y-3">
                <legend class="label">Links</legend>
                <div class="flex gap-x-2 items-center">
                    <input
                        x-model="newLink"
                        @keydown.enter.prevent="addLink()"
                        type="url"
                        placeholder="http://example.com"
                        autocomplete="url"
                        class="input flex-1"
                        spellcheck="false"
                    >
                    <button
                        type="button"
                        @click="addLink()"
                        :disabled="newLink.trim().length === 0"
                        class="text-4xl leading-none disabled:opacity-40"
                        aria-label="Agregar link"
                    >
                        +
                    </button>
                </div>

                <ul x-show="links.length" class="space-y-2">
                    <template x-for="(link, index) in links" :key="index">
                        <li class="flex items-center gap-x-2 rounded-lg border border-border bg-card px-3 py-2">
                            <span class="flex-1 truncate text-sm" x-text="link"></span>

                            <input type="hidden" name="links[]" :value="link">

                            <button
                                type="button"
                                @click="links.splice(index, 1)"
                                class="text-red-500! text-sm"
                                :aria-label="`Eliminar ${link}`"
                            >
                                X
                            </button>
                        </li>
                    </template>
                </ul>

                <x-forms.error name="links"/>

                @foreach ($errors->get('links.*') as $messages)
                    <p class="text-red-500 mt-1 text-sm">{{ $messages[0] }}</p>
                @endforeach
            </fieldset>
        </div>

        <div>
            <fieldset class="space-y-3">
                <legend class="label">Pasos</legend>
                <div class="flex gap-x-2 items-center">
                    <input
                        x-model="newStep"
                        @keydown.enter.prevent="addStep()"
                        type="text"
                        placeholder="Ej: investigar alternativas"
                        maxlength="255"
                        class="input flex-1"
                    >
                    <button
                        type="button"
                        @click="addStep()"
                        :disabled="newStep.trim().length === 0"
                        class="text-4xl leading-none disabled:opacity-40"
                        aria-label="Agregar paso"
                    >
                        +
                    </button>
                </div>

                <ol x-show="steps.length" class="space-y-2">
                    <template x-for="(step, index) in steps" :key="index">
                        <li class="flex items-center gap-x-2 rounded-lg border border-border bg-card px-3 py-2">
                            <span class="text-xs text-muted-foreground" x-text="index + 1"></span>
                            <input
                                type="text"
                                x-model="step.description"
                                :name="`steps[${index}][description]`"
                                maxlength="255"
                                required
                                class="flex-1 bg-transparent text-sm outline-none"
                                aria-label="Descripcion del paso"
                            >

                            <template x-if="step.id">
                                <input type="hidden" :name="`steps[${index}][id]`" :value="step.id">
                            </template>

                            <button
                                type="button"
                                @click="steps.splice(index, 1)"
                                class="text-red-500! text-sm"
                                :aria-label="`Eliminar ${step.description}`"
                            >
                                X
                            </button>
                        </li>
                    </template>
                </ol>

                <x-forms.error name="steps"/>

                @foreach ($errors->get('steps.*') as $messages)
                    <p class="text-red-500 mt-1 text-sm">{{ $messages[0] }}</p>
                @endforeach
            </fieldset>
        </div>

        <div class="flex justify-end gap-x-3">
            <button type="button" @click="$dispatch('close-modal')" class="btn btn-outlined">Cancelar</button>
            <button type="submit" class="btn">{{ $editing ? 'Guardar' : 'Crear' }}</button>
        </div>
    </div>
</form>
