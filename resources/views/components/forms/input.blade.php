@props([
    'name',
    'label',
    'type' => 'text',
])

<div>
    <label class="label" for="{{ $name }}">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $type === 'password' ? '' : old($name) }}"
        {{ $attributes->merge(['class' => 'input mt-1']) }}/>
    @error($name)
        <p class="error">{{$message}}</p>
    @enderror
</div>
