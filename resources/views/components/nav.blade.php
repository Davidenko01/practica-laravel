<div class="navbar bg-base-100 shadow-sm">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg aria-label="Menu" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                </svg>
            </div>
            <ul
                tabindex="-1"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a href="/ideas">Home</a></li>
                <li><a href="/ideas/crete">New Idea</a></li>
                @can('view-admin-panel')
                    <li><a href="/admin">Admin Panel</a></li>
                @endcan
            </ul>
        </div>
        <a class="btn btn-ghost text-xl">Idea</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/ideas">Home</a></li>
            <li><a href="/ideas/create">New Idea</a></li>
            @can('view-admin-panel')
                <li><a href="/admin">Admin Panel</a></li>
            @endcan
        </ul>
    </div>
    <div class="navbar-end space-x-2">
        @guest
            <a class="btn btn-primary" href="/register">Register</a>
            <a class="btn btn-secondary" href="/login">Login</a>
        @endguest
        @auth
            <form action="/logout" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost">Log Out</button>
            </form>
        @endauth
    </div>
</div>
