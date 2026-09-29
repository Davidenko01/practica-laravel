<x-layout title="Edit Your Account">
    <x-forms
        action="{{ route('profile.update') }}"
        heading="Editar Perfil"
        subheading="Editar Perfil de Usuario."
        submit="Editar cuenta"
        method="PATCH"
    >

        <x-forms.input
            name="username"
            label="Nombre"
            placeholder="Tu nombre"
            autocomplete="name"
            :value="$user->username"
        />

        <x-forms.input
            name="email"
            label="Email"
            type="email"
            placeholder="tu@email.com"
            autocomplete="email"
            :value="$user->email"
        />

        <x-forms.input
            name="password"
            label="Nueva Contraseña"
            type="password"
            placeholder="Tu nueva contraseña"
            autocomplete="new-password"
        />
    </x-forms>
</x-layout>
