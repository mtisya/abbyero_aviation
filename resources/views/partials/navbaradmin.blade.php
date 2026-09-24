@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<nav class="navbar navbar-expand-lg sticky-top navbar-solid">
    <div class="container-fluid px-3">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center me-3"
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

            {{-- Cart --}}
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


{{-- =====================================================
     NOTIFICATIONS
===================================================== --}}
@auth

    <div class="dropdown notification-wrapper">

        {{-- Notification Button --}}
        <button class="nav-icon-btn notification-btn"
                type="button"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
                aria-label="Notifications"
                title="Notifications">

            <i class="bi bi-bell-fill fs-5"></i>

            @php
                $unreadCount = auth()->user()->unreadNotifications->count();
            @endphp

            @if($unreadCount > 0)

                <span class="nav-badge">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>

            @endif

        </button>


        {{-- =================================================
             NOTIFICATION DROPDOWN
        ================================================== --}}
        <div class="dropdown-menu dropdown-menu-end notification-dropdown">

            {{-- Header --}}
            <div class="notification-header">

                <div>
                    <i class="bi bi-bell-fill me-1"></i>
                    <strong>Notifications</strong>
                </div>

                @if($unreadCount > 0)
                    <span class="notification-count">
                        {{ $unreadCount }}
                    </span>
                @endif

            </div>


            {{-- Notifications --}}
            <div class="notification-list">

                @forelse(auth()->user()->unreadNotifications as $notification)

                    <div class="notification-item">

                        <div class="notification-icon">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>

                        <div class="notification-content">

                            <div class="notification-message">
                                {{ $notification->data['message'] ?? 'New notification' }}
                            </div>

                            @if(isset($notification->created_at))
                                <small class="notification-time">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="notification-empty">

                        <i class="bi bi-bell-slash fs-3"></i>

                        <p class="mb-0 mt-2">
                            No new notifications
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Footer --}}
            @if($unreadCount > 0)

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



            {{-- Authentication --}}
            @auth

                <form action="{{ route('logout') }}"
                      method="POST"
                      class="m-0">

                    @csrf

                    <button type="submit"
                            class="btn btn-danger nav-auth-btn">

                        <i class="bi bi-box-arrow-right me-1"></i>
                        <span class="auth-label">Logout</span>

                    </button>

                </form>

            @else

                <a href="{{ route('register') }}"
                   class="btn btn-primary nav-auth-btn">

                    <i class="bi bi-person-plus me-1"></i>
                    <span class="auth-label">Start for Free</span>

                </a>

                <a href="{{ route('login') }}"
                   class="btn btn-secondary nav-auth-btn">

                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    <span class="auth-label">Sign In</span>

                </a>

            @endauth

        </div>


        {{-- =====================================================
             MOBILE MENU TOGGLE
        ====================================================== --}}

        <button class="navbar-toggler order-lg-2"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarScroll"
                aria-controls="navbarScroll"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>


        {{-- =====================================================
             COLLAPSIBLE NAVIGATION
             Only this section collapses on mobile
        ====================================================== --}}

        <div class="collapse navbar-collapse order-lg-1"
             id="navbarScroll">

            <ul class="navbar-nav me-auto mb-3 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/admin/dashboard">
                        <i class="bi bi-person-circle me-1"></i>
                        My Account
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/flightrental">
                        <i class="bi bi-airplane me-1"></i>
                        Aircraft
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/maintenance">
                        <i class="bi bi-tools me-1"></i>
                        Aircraft Maintenance
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/flight-school">
                        <i class="bi bi-mortarboard me-1"></i>
                        Flight School
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/aircraftparts">
                        <i class="bi bi-gear me-1"></i>
                        Aircraft Parts
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/instructors">
                        <i class="bi bi-person-workspace me-1"></i>
                        Flight Instructors
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/skydiving">
                        <i class="bi bi-person-fill-down me-1"></i>
                        Sky Diving
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/gliders">
                        <i class="bi bi-wind me-1"></i>
                        Gliders
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/contact">
                        <i class="bi bi-envelope me-1"></i>
                        Contact Us
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>


<script>
    const notifications = @json(auth()->user()->unreadNotifications ?? []);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const container = document.getElementById('toast-container');

        notifications.forEach((notification, index) => {

            const toast = document.createElement('div');
            toast.className = "toast align-items-center text-white bg-dark border-0 show mb-2";
            toast.style.minWidth = "220px";
            toast.style.borderRadius = "12px";
            toast.style.boxShadow = "0 4px 12px rgba(56, 102, 121, 0.4)";

            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    🔔 ${notification.data.message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
            </div>
        `;

            container.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.style.opacity = "0";
                setTimeout(() => toast.remove(), 500);
            }, 5000 + (index * 500)); // stagger effect

            // Manual close
            toast.querySelector('.btn-close').onclick = () => {
                toast.remove();
            };
        });

    });

    function markAsRead() {
        fetch('/notifications/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            location.reload();
        });
    }
    function markOneAsRead(id) {
        fetch('/notifications/read-one/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            location.reload(); // refresh badge + dropdown
        });
    }
</script>