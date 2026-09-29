<x-layout title="Register">
    <x-forms
        action="{{ route('register.store') }}"
        heading="Crear cuenta"
        subheading="Registrate para empezar a guardar tus ideas."
        submit="Crear cuenta">

        <x-forms.input
            name="username"
            label="Nombre"
            placeholder="Tu nombre"
            autocomplete="name"
            required/>

        <x-forms.input
            name="email"
            label="Email"
            type="email"
            placeholder="tu@email.com"
            autocomplete="email"
            required/>

        <x-forms.input
            name="password"
            label="Contraseña"
            type="password"
            placeholder="Tu contraseña"
            autocomplete="new-password"
            required/>

        <x-slot:footer>
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-foreground underline">Inicia sesion</a>
        </x-slot:footer>
    </x-forms>
</x-layout>
