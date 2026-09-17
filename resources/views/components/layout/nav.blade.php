<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="/">
                <img src="/images/logo_muni.svg" alt="Muni Logo" width="100">
            </a>
        </div>
        <div class="flex gap-5 items-center">
            @guest
                <a href="/login">Sign In</a>
                <a href="/register" class="btn">Register</a>
            @endguest

            @auth
                <form method="POST" action="/logout">
                    @csrf
                    @method('DELETE')
                    <button class="btn" data-test="logout-button">Log Out</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
