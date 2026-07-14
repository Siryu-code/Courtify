{{-- ======================== OFFCANVAS MENU ======================== --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    {{-- Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold fs-5" id="offcanvasMenuLabel">Menu</h5>

        <button type="button" class="btn p-0 border-0 shadow-none" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark fs-4"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="offcanvas-body d-flex flex-column">

        {{-- Mini Profile --}}
        @auth
            @php($user = Auth::user())

            <a href="{{ route('profile') }}" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 mb-4">

                    @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}"
                             alt="{{ $user->name }}"
                             class="rounded-circle"
                             width="52"
                             height="52"
                             style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:52px;height:52px;font-size:20px;">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                        <div class="text-muted small">{{ $user->email }}</div>
                    </div>
                </div>
            </a>
        @else
            <a href="{{ route('login') }}" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 mb-4">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;">
                        <i class="fa-solid fa-user text-secondary fs-5"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">Silakan Masuk</div>
                        <div class="text-muted small">Selamat datang di Courtify Arena</div>
                    </div>
                </div>
            </a>
        @endauth

        {{-- Navigasi --}}
        <h6 class="fw-bold mb-3 mt-2">Navigasi</h6>

        <div class="d-flex flex-column gap-2">

            {{-- Home --}}
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 {{ request()->routeIs('home') ? 'bg-light' : '' }}">
                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;">
                        <i class="fa-solid fa-house text-dark"></i>
                    </div>
                    <span class="text-dark">Home</span>
                </div>
            </a>

            {{-- Lapangan --}}
            <a href="{{ route('home') }}#venue-section" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 {{ request()->routeIs('home') ? 'bg-light' : '' }}">
                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;">
                        <i class="fa-solid fa-futbol text-dark"></i>
                    </div>
                    <span class="text-dark">Lapangan</span>
                </div>
            </a>

            {{-- Promo --}}
            <a href="{{ route('promos') }}" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 {{ request()->routeIs('promos') ? 'bg-light' : '' }}">
                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;">
                        <i class="fa-solid fa-tag text-dark"></i>
                    </div>
                    <span class="text-dark">Event Promo</span>
                </div>
            </a>

            {{-- History --}}
            @auth
                <a href="{{ route('history') }}" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-3 border rounded-3 p-3 {{ request()->routeIs('history*') ? 'bg-light' : '' }}">
                        <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;">
                            <i class="fa-solid fa-clock-rotate-left text-dark"></i>
                        </div>
                        <span class="text-dark">Riwayat Booking</span>
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-3 border rounded-3 p-3">
                        <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;">
                            <i class="fa-solid fa-clock-rotate-left text-dark"></i>
                        </div>
                        <span class="text-dark">Riwayat Booking</span>
                    </div>
                </a>
            @endauth

        </div>

        {{-- Login / Logout --}}
        <div class="mt-auto pt-3">

            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="btn w-100 rounded-3 py-2 fw-bold text-white"
                   style="background-color:#0d5ba8;">
                    Login
                </a>
            @endauth

        </div>
    </div>
</div>