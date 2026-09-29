@props([
    'action',
    'heading',
    'subheading' => null,
    'submit' => 'Enviar',
    'test' => 'submit',
    'method' => null
])

<div class="mx-auto mt-12 w-full max-w-sm">
    <div class="rounded-xl border border-border bg-card p-6">
        <h1 class="text-xl font-semibold">{{ $heading }}</h1>

        @if ($subheading)
            <p class="mt-1 text-sm text-muted-foreground">{{ $subheading }}</p>
        @endif

        <form action="{{ $action }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @if($method)
                @method($method)
            @endif
            {{ $slot }}

            <button type="submit" data-test="{{ $test }}" class="btn mt-2 w-full text-center">{{ $submit }}</button>
        </form>
    </div>

    @isset($footer)
        <p class="mt-6 text-center text-sm text-muted-foreground">{{ $footer }}</p>
    @endisset
</div>
