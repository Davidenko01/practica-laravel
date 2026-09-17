<x-layout title="Login">
    <x-forms
        action="/login"
        heading="Iniciar sesion"
        subheading="Entra para ver y crear tus ideas."
        submit="Entrar"
        test="login-button"
        >

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
            autocomplete="current-password"
            required/>

        <x-slot:footer>
            ¿No tienes cuenta?
            <a href="/register" class="text-foreground underline">Registrate</a>
        </x-slot:footer>
    </x-forms>
</x-layout>
