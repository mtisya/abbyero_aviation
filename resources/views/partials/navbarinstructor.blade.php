 <nav class="navbar navbar-expand-xxl sticky-top navbar-solid">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logos/abbyerologo.png') }}" alt="Abbyero Aviation Logo" height="55" width="60"
                    id="menu-logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/instructor/dashboard">My Account</a></li>
                    <li class="nav-item"><a class="nav-link" href="/flightrental">Flight Rental</a></li>
                    <li class="nav-item"><a class="nav-link" href="/maintenance">Aircraft Maintenance</a></li>
                    <li class="nav-item"><a class="nav-link" href="/aerobics">Aerobics</a></li>
                    <li class="nav-item"><a class="nav-link" href="/aircraftparts">Aircraft Parts</a></li>
                    <li class="nav-item"><a class="nav-link" href="/instructors">Flight Instructors</a></li>
                    <li class="nav-item"><a class="nav-link" href="/skydiving">Sky Diving</a></li>
                    <li class="nav-item"><a class="nav-link" href="/gliders">Gliders</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact Us</a></li>
                </ul>

                <div class="d-flex">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-danger" id="nav-btn-logout">Logout</button>
                        </form>
                    @else
                        <button class="btn btn-primary me-2" id="nav-btn-register"
                            onclick="window.location.href='{{ route('register') }}'">Start for Free</button>
                        <button class="btn btn-secondary" id="nav-btn-login"
                            onclick="window.location.href='{{ route('login') }}'">Sign In</button>
                    @endauth
                </div>

            </div>
        </div>
    </nav>