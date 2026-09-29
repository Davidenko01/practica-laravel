@props([
    'name',
    'label' => false,
    'type' => 'text',
    'value' => null,
])

<div>
    @if ($label)
        <label class="label" for="{{ $name }}">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => 'textarea mt-1']) }}>{{ old($name, $value) }}</textarea>
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $type === 'password' ? '' : old($name, $value) }}"
            {{ $attributes->merge(['class' => 'input mt-1']) }}/>
    @endif

        <x-forms.error name="{{ $name }}"/>
</div>
