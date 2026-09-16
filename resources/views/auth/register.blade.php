<x-layout>
    <form action="/register" method="POST">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box mx-auto w-xs border p-4">
            <legend class="fieldset-legend">Register</legend>

            <label class="label" for="username">Name</label>
            <input type="text" id="username" name="username" class="input" placeholder="Your name" value="{{ old('username') }}" required/>

            <label class="email" for="email">Email</label>
            <input type="email" name="email" class="input" placeholder="Your Email" required/>

            <label class="label">Password</label>
            <input type="password" name="password" class="input" placeholder="Password" required/>

            <button class="btn btn-neutral mt-4">Register</button>
        </fieldset>
    </form>
</x-layout>
