@php
$cartCount = collect(session('cart', []))->sum('quantity');

$dashboardRoute = '#';

if (auth()->check()) {
    $dashboardRoute = match (auth()->user()->role) {
        'admin'       => '/admin/dashboard',
        'instructor'  => '/instructor/dashboard',
        'student'     => '/student/dashboard',
        'user'        => '/dashboard',
        default       => '#',
    };
}

@endphp

<nav class="navbar navbar-expand-lg sticky-top navbar-solid">

<div class="container-fluid px-3">

    {{-- =====================================================
         LOGO
    ====================================================== --}}
    <a class="navbar-brand d-flex align-items-center me-2"
       href="{{ url('/') }}"
       aria-label="Abbyero Aviation Home">

        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
             alt="Abbyero Aviation Logo"
             id="menu-logo">

    </a>


    


    {{-- =====================================================
         TOP ACTIONS
         Cart + Notifications + Authentication

         These remain visible on mobile
    ====================================================== --}}
    <div class="navbar-actions order-lg-3">


        {{-- =================================================
             CART
        ================================================== --}}
        <a href="{{ route('cart.index') }}"
           class="nav-icon-btn"
           aria-label="Shopping cart"
           title="Shopping Cart">

            <i class="bi bi-cart-fill fs-5"></i>

            @if($cartCount > 0)

                <span class="nav-badge">
                    {{ $cartCount }}
                </span>

            @endif

        </a>


        {{-- =================================================
             NOTIFICATIONS
        ================================================== --}}
        @auth

            <div class="dropdown notification-wrapper">

                <button class="nav-icon-btn notification-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Notifications"
                        title="Notifications">

                    <i class="bi bi-bell-fill fs-5"></i>

                    @if(auth()->user()->unreadNotifications->count() > 0)

                        <span class="nav-badge">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>

                    @endif

                </button>


                {{-- Notification Dropdown --}}
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">

                    {{-- Header --}}
                    <div class="notification-header">

                        <strong>
                            <i class="bi bi-bell me-1"></i>
                            Notifications
                        </strong>

                        @if(auth()->user()->unreadNotifications->count() > 0)

                            <span class="notification-count">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>

                        @endif

                    </div>


                    {{-- Notification List --}}
                    <div class="notification-list">

                        @forelse(auth()->user()->unreadNotifications as $notification)

                            <div class="notification-item">

                                <div class="notification-icon">
                                    <i class="bi bi-bell-fill"></i>
                                </div>

                                <div class="notification-content">

                                    <div class="notification-message">
                                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                    </div>

                                    @if($notification->created_at)

                                        <small class="notification-time">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="notification-empty">

                                <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>

                                No new notifications

                            </div>

                        @endforelse

                    </div>


                    {{-- Footer --}}
                    @if(auth()->user()->unreadNotifications->count() > 0)

                        <div class="notification-footer">

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary w-100"
                                    onclick="markAsRead()">

                                <i class="bi bi-check2-all me-1"></i>
                                Mark all as read

                            </button>

                        </div>

                    @endif

                </div>

            </div>

        @endauth


        {{-- =================================================
             AUTHENTICATION
        ================================================== --}}
        @auth

            <form action="{{ route('logout') }}"
                  method="POST"
                  class="m-0">

                @csrf

                <button type="submit"
                        class="btn btn-danger nav-auth-btn"
                        title="Logout">

                    <i class="bi bi-box-arrow-right"></i>

                    <span class="auth-label">
                        Logout
                    </span>

                </button>

            </form>

        @else

            <a href="{{ route('register') }}"
               class="btn btn-primary nav-auth-btn">

                <i class="bi bi-person-plus me-1"></i>

                <span class="auth-label">
                    Start for Free
                </span>

            </a>

            <a href="{{ route('login') }}"
               class="btn btn-secondary nav-auth-btn">

                <i class="bi bi-box-arrow-in-right me-1"></i>

                <span class="auth-label">
                    Sign In
                </span>

            </a>

        @endauth

    </div>

    {{-- =====================================================
         MOBILE MENU TOGGLE
    ====================================================== --}}
    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#roleBasedNavbar"
            aria-controls="roleBasedNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

    </button>


    {{-- =====================================================
         MAIN NAVIGATION
    ====================================================== --}}
    <div class="collapse navbar-collapse order-lg-2"
         id="roleBasedNavbar">

        <ul class="navbar-nav me-auto mb-2 mb-lg-0">


            {{-- =================================================
                 MY ACCOUNT
            ================================================== --}}
            @auth

                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ $dashboardRoute }}">

                        <i class="bi bi-person-circle me-1"></i>

                        My Account

                    </a>

                </li>

            @endauth


            {{-- =================================================
                 AIRCRAFT
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/flightrental">

                    <i class="bi bi-airplane me-1"></i>

                    Aircraft

                </a>

            </li>


            {{-- =================================================
                 MAINTENANCE
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/maintenance">

                    <i class="bi bi-tools me-1"></i>

                    Aircraft Maintenance

                </a>

            </li>


            {{-- =================================================
                 FLIGHT SCHOOL
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/flight-school">

                    <i class="bi bi-mortarboard me-1"></i>

                    Flight School

                </a>

            </li>


            {{-- =================================================
                 AIRCRAFT PARTS
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/aircraftparts">

                    <i class="bi bi-gear me-1"></i>

                    Aircraft Parts

                </a>

            </li>


            {{-- =================================================
                 FLIGHT INSTRUCTORS
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/instructors">

                    <i class="bi bi-person-workspace me-1"></i>

                    Flight Instructors

                </a>

            </li>


            {{-- =================================================
                 SKY DIVING
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/skydiving">

                    <i class="bi bi-person-fill-down me-1"></i>

                    Sky Diving

                </a>

            </li>


            {{-- =================================================
                 GLIDERS
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/gliders">

                    <i class="bi bi-wind me-1"></i>

                    Gliders

                </a>

            </li>


            {{-- =================================================
                 CONTACT
            ================================================== --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="/contact">

                    <i class="bi bi-envelope me-1"></i>

                    Contact Us

                </a>

            </li>

        </ul>

    </div>

</div>

</nav>
