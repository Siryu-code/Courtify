<nav class="bottom-navbar">

    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>

    <a href="{{ route('home') }}#venue-section" class="nav-item">
        <i class="fa-regular fa-compass"></i>
        <span>Explore</span>
    </a>

    <a href="{{ route('promos') }}" class="nav-item {{ request()->routeIs('promos') ? 'active' : '' }}">
        <i class="fa-solid fa-tag"></i>
        <span>Deals</span>
    </a>

    @auth
        <a href="{{ route('history') }}" class="nav-item {{ request()->routeIs('history*') ? 'active' : '' }}">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>History</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>History</span>
        </a>
    @endauth

    @auth
        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="nav-item">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
        </a>
    @endauth

</nav>