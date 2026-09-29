<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="{{ route('home') }}">
                <img src="/images/logo_muni.svg" alt="Muni Logo" width="100">
            </a>
        </div>
        <div class="flex gap-5 items-center">
            @guest
                <a href="{{ route('login') }}">Sign In</a>
                <a href="{{ route('register') }}" class="btn">Register</a>
            @endguest

            @auth
                <a href="{{ route('profile.edit') }}">Edit Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn" data-test="logout-button">Log Out</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
