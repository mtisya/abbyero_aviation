<nav class="navbar navbar-expand-xxl sticky-top navbar-solid">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logos/abbyerologo.png') }}" 
                 alt="Abbyero Aviation Logo" height="55" width="60" id="menu-logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/dashboard">My Account</a></li>
                <li class="nav-item"><a class="nav-link" href="/flightrental">Flight Rental</a></li>
                <li class="nav-item"><a class="nav-link" href="/maintenance">Aircraft Maintenance</a></li>
                <li class="nav-item"><a class="nav-link" href="/aerobics">Aerobics</a></li>
                <li class="nav-item"><a class="nav-link" href="/aircraftparts">Aircraft Parts</a></li>
                <li class="nav-item"><a class="nav-link" href="/instructors">Flight Instructors</a></li>
                <li class="nav-item"><a class="nav-link" href="/skydiving">Sky Diving</a></li>
                <li class="nav-item"><a class="nav-link" href="/gliders">Gliders</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact Us</a></li>
            </ul>

            <div class="d-flex flex-column align-items-center">
                {{-- 🛒 Cart Icon with Badge --}}
                <a href="{{ route('cart.index') }}" class="btn position-relative mb-1">
                    <i class="bi bi-cart-fill fs-4"></i> {{-- Bootstrap icon --}}
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="w-10 text-center">
                        @csrf
                        <button type="submit" class="btn btn-danger w-10 mb-5" id="nav-btn-logout">Logout</button>
                    </form>
                @else
                    <button class="btn btn-primary mb-2 w-100" id="nav-btn-register"
                        onclick="window.location.href='{{ route('register') }}'">Start for Free</button>
                    <button class="btn btn-secondary w-10" id="nav-btn-login"
                        onclick="window.location.href='{{ route('login') }}'">Sign In</button>
                @endauth
            </div>

        </div>
    </div>
</nav>
