@props([
    'name' => 'required'
])
@error($name)
    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
@enderror
