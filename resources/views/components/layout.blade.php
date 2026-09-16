@props([
    'title' => 'MuniNQN',

])
    <!doctype html>
<html lang="en" data-theme="lofi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<x-nav/>
<main class="max-w-3xl mx-auto">
    {{ $slot }}
</main>
</body>
</html>
