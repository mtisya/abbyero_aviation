<body>
    @php
        $cartCount = collect(session('cart', []))->sum('quantity');
    @endphp

    <nav class="navbar navbar-expand-lg sticky-top navbar-solid">
        <div class="container-fluid px-3">

            {{-- =====================================================
            LOGO
            ====================================================== --}}
            <a class="navbar-brand d-flex align-items-center me-3" href="{{ url('/') }}"
                aria-label="Abbyero Aviation Home">

                <img src="{{ asset('assets/images/logos/abbyerologo.png') }}" alt="Abbyero Aviation Logo"
                    id="menu-logo">
            </a>

            {{-- =====================================================
            TOP ACTIONS
            Cart + Notifications + Authentication
            Remain horizontally aligned
            ====================================================== --}}
            <div class="navbar-actions order-lg-3">

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" aria-label="Shopping cart"
                    title="Shopping Cart">

                    <i class="bi bi-cart-fill fs-5"></i>

                    @if($cartCount > 0)
                        <span class="nav-badge">
                            {{ $cartCount }}
                        </span>
                    @endif

                </a>


                {{-- Notifications --}}
                @auth
                    <div class="dropdown notification-wrapper">

                        <button class="nav-icon-btn notification-btn" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Notifications" title="Notifications">

                            <i class="bi bi-bell-fill fs-5"></i>

                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="nav-badge">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif

                        </button>


                        {{-- Notification Dropdown --}}
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">

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


                            <div class="notification-list">

                                @forelse(auth()->user()->unreadNotifications as $notification)

                                    <div class="notification-item">

                                        <div class="notification-icon">
                                            <i class="bi bi-bell-fill"></i>
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
                                        <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                        No new notifications
                                    </div>

                                @endforelse

                            </div>


                            {{-- Notification Footer --}}
                            @if(auth()->user()->unreadNotifications->count() > 0)

                                <div class="notification-footer">

                                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="markAsRead()">

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

                    <form action="{{ route('logout') }}" method="POST" class="m-0">

                        @csrf

                        <button type="submit" class="btn btn-danger nav-auth-btn" id="nav-btn-logout">

                            <i class="bi bi-box-arrow-right me-1"></i>
                            <span class="auth-label">Logout</span>

                        </button>

                    </form>

                @else

                    <a href="{{ route('register') }}" class="btn btn-primary nav-auth-btn" id="nav-btn-register">

                        <i class="bi bi-person-plus me-1"></i>
                        <span class="auth-label">Start your Ride</span>

                    </a>

                    <a href="{{ route('login') }}" class="btn btn-secondary nav-auth-btn" id="nav-btn-login">

                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        <span class="auth-label">Sign In</span>

                    </a>

                @endauth

            </div>

            {{-- =====================================================
            MOBILE TOGGLE
            ====================================================== --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>
            </button>


            {{-- =====================================================
            NAVIGATION MENU
            ====================================================== --}}
            <div class="collapse navbar-collapse order-lg-2" id="navbarScroll">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    @auth
                        <li class="nav-item">

                            @php
                                $role = Auth::user()->role;

                                $dashboardRoute = match ($role) {
                                    'admin' => '/admin/dashboard',
                                    'instructor' => '/instructor/dashboard',
                                    'student' => '/student/dashboard',
                                    'user' => '/dashboard',
                                    default => '#',
                                };
                            @endphp

                            <a class="nav-link" href="{{ $dashboardRoute }}">

                                <i class="bi bi-person-circle me-1"></i>
                                My Account

                            </a>

                        </li>
                    @endauth


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


    <main>
        <div class='container-fluid' id='billboard-container'>
            <div class='row'>

                {{-- Desktop Version --}}
                <div class='col-sm-12 col-lg-11 d-none d-md-block' id='billboard-text'>
                    <h1 class='banner-text display-2'>
                        Start your <span class='billboard-text-underline'>RIDE</span><br>
                        test fly today!
                    </h1>
                    <div class='row justify-content-end' id='billboard-cta-button'>
                        <div class='col-lg-4 col-md-6'>
                            <a href='/register'>
                                <button class='btn btn-primary btn-cta btn-cta-generic text-nowrap' type='button'>
                                    Get Started Today
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Mobile Version --}}
                <div class='col-12 d-block d-md-none text-center p-4' id='billboard-text-mobile'>
                    <h2 class='banner-text'>
                        Start your <span class='billboard-text-underline'>RIDE</span><br>
                        test fly today!
                    </h2>
                    <a href='/register'>
                        <button class='btn btn-primary btn-cta w-10 mt-3' type='button'>
                            Get Started
                        </button>
                    </a>
                </div>

            </div>

            <div class='row'>
                <div class='col-12' id='billboard-bottom-line'></div>
            </div>
        </div>

        <div class='container-fluid' id='skyscraper-container'>
            <div class='row justify-content-center gap-5'>
                <div class='col-lg-2 bg-gradient card col-10 col-sm-4 skyscraper private-skyscraper'
                    style="background-color: #cae7f5;">
                    <div class='part-141-tab d-none d-md-block'> <img alt='Abbyero Aviation is Part 141-Approved'
                            src='assets/images/logos/logo1.png' class="rounded-logo">
                    </div>
                    <div class='row justify-content-center mt-2 mb-2'>
                        <div class='col-12' id='course-1-image'> <img
                                alt='Cessna 172 representing the Abbyero Aviation Private Pilot Aviation School.'
                                src='assets/images/index/IMG-20250710-WA0152.jpg' height='360' width='640'
                                onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0162.jpg"'
                                class='course-image transparent-image'> </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12' id='course-1-name'>
                            <h2>PA28R-200 piper arrow<br>200hp (N2204T)</h2>
                        </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12 course-feature-text' id='course-1-features'>
                            The PA28R-200 is a Piper Arrow, a popular single-engine, retractable-gear aircraft known for
                            its reliability and performance.
                            It's a four-seat aircraft with a 200 horsepower Lycoming engine. </div>
                    </div>
                    <div class='text-center approved'>
                        <span>The "N2204T" refers to its FAA Registration Number.</span>
                    </div>
                    <div class='row justify-content-center cta-row'>
                        <div class='col-4 course-price-shape' id='private_pilot_price'></div>
                        <div class='col-10'> <a href='/flights'> <button class='btn btn-primary course-cta-shape' type='button'
                                    id='private_pilot_cta'> Explore </button> </a> </div>
                    </div>
                </div>
                <div class='col-lg-2 bg-gradient card col-10 col-sm-4 skyscraper instrument-skyscraper'
                    style="background-color: #cae7f5;">
                    <div class='part-141-tab d-none d-md-block'> <img alt='Abbyero Aviation is Part 141-Approved'
                            src='assets/images/logos/logo1.png' class="rounded-logo">
                    </div>
                    <div class='row justify-content-center mt-2 mb-2'>
                        <div class='col-12' id='course-2-image'> <img
                                alt='Cirrus SR22 representing the Abbyero Aviation Instrument Pilot Aviation School.'
                                src='assets/images/index/N6326W_images (5).jpeg' height='360' width='640'
                                onerror='this.onerror=null,this.src="assets/images/index/N6326W_images (5).jpeg"'
                                class='course-image transparent-image'> </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12' id='course-2-name'>
                            <h2>⁠PA28-140 piper Cherokee<br>160 hp (N547FL)</h2>
                        </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12 course-feature-text' id='course-2-features'>
                            The Piper PA-28-140 Cherokee is a popular light aircraft known for its reliability, ease of
                            handling, and spacious cabin.
                            This makes it a versatile choice for both recreational pilots and flight training schools.
                        </div>
                    </div>
                    <div class='text-center approved'> <span>The "N547FL" refers to its FAA Registration Number.</span>
                    </div>
                    <div class='row justify-content-center cta-row'>
                        <div class='col-4 course-price-shape' id='instrument_pilot_price'></div>
                        <div class='col-10'> <a href='/flights'> <button class='btn btn-primary course-cta-shape' type='button'
                                    id='instrument_pilot_cta'>
                                    Explore </button> </a> </div>
                    </div>
                </div>
                <style class='hover'></style>
                <div class='col-lg-2 bg-gradient card col-10 col-sm-4 skyscraper commercial-skyscraper relative'
                    style="background-color: #cae7f5;">
                    <div class='part-141-tab d-none d-md-block'> <img alt='Abbyero Aviation is Part 141-Approved'
                            src='assets/images/logos/logo1.png' class="rounded-logo">
                    </div>
                    <div class='sparkles'></div>
                    <div class='row justify-content-center mt-2 mb-2'>
                        <div class='col-12' id='course-3-image'> <img
                                alt='Pilatus representing the Abbyero Aviation Commercial Pilot Aviation School.'
                                src='assets/images/index/IMG-20250710-WA0226.jpg' height='360' width='640'
                                class='course-image transparent-image'> </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12' id='course-2-name'>
                            <h2>Aircraft Maintenance<br>and Inspection</h2>
                        </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12 course-feature-text' id='course-3-features'>
                            We offer professional aircraft maintenance and inspection services, ensuring safety,
                            compliance, and peak performance. Our certified technicians handle everything from routine
                            checks to complex repairs, adhering to FAA and international aviation standards.
                        </div>
                    </div>
                    <div class='text-center approved'>
                        <span>Trusted by operators for timely service.</span>
                    </div>

                    <div class='row justify-content-center cta-row'>
                        <div class='col-4 course-price-shape' id='commercial_pilot_price'></div>
                        <div class='col-10'> <a href='/maintenances'> <button class='btn btn-primary course-cta-shape' type='button'
                                    id='commercial_pilot_cta'>
                                    Explore </button> </a> </div>
                    </div>
                </div>
                <div class='col-lg-2 bg-gradient card col-10 col-sm-4 skyscraper remote-skyscraper'
                    style="background-color: #cae7f5;">
                    <div class='part-141-tab d-none d-md-block'> <img alt='Abbyero Aviation is Part 141-Approved'
                            src='assets/images/logos/logo1.png' class="rounded-logo"> </div>
                    <div class='row justify-content-center mt-2 mb-2'>
                        <div class='col-12' id='course-4-image'> <img
                                alt='DJI Inspire Drone representing the Abbyero Aviation Remote Pilot Aviation School.'
                                src='assets/images/index/IMG-20250710-WA00644.jpg' height='360' width='640'
                                onerror='this.onerror=null,this.src="assets/images/index/skyscraper-remote-aircraft.png"'
                                class='course-image transparent-image'> </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12' id='course-4-name'>
                            <h2>Aircraft Parts<br>Supply & Support</h2>
                        </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-12 course-feature-text' id='course-4-features'>
                            We provide genuine aircraft spare parts for a wide range of models, including OEM and
                            FAA-approved components.
                            From routine replacements to critical spares, our inventory ensures fast delivery and
                            reliable quality to keep your aircraft flying safely.
                        </div>
                    </div>
                    <div class='text-center approved'>
                        <span>Approved for FAA WINGS Credit</span>
                    </div>

                    <div class='row justify-content-center cta-row'>
                        <div class='col-4 course-price-shape' id='remote_pilot_price'></div>
                        <div class='col-10'> <a href='/aircraftparts'> <button class='btn btn-primary course-cta-shape' type='button'
                                    id='remote_pilot_cta'> Explore </button> </a> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="instructor_banner_container">
            <div class="row justify-content-center text-center">
                <div class="col-12 col-lg-9 bg-gradient card" id="instructor-banner">
                    <h2 style="padding-bottom: 10px;">Professional Aviation Services</h2>
                    <p class="h5">
                        We provide reliable aviation services to flight operators, instructors, skydiving teams, and
                        glider enthusiasts — including aircraft rentals, maintenance, aerobatics, parts supply, and
                        more.
                    </p>
                    <a href="/flights">
                        <button class="btn btn-primary btn-lg mt-3" type="button">Explore Our Services</button>
                    </a>
                </div>
            </div>
        </div>


        <div class="container-fluid" id="differences-container">
            <div class="row flex-wrap align-items-center">
                <!-- Left Column: Features -->
                <div class="col-12 col-lg-6 mb-4" id="differences-text-column">
                    <div class="row" id="differences-row">
                        <div class="col-12 mx-auto" id="differences-column">
                            <div class="row">
                                <div class="col-12 px-3" id="differences-headline">
                                    <h2 class="display-6 text-center text-lg-start">What makes Abbyero Aviation
                                        different?</h2>
                                </div>
                            </div>

                            <!-- Feature List -->
                            <div class="row difference-row align-items-center my-3">
                                <div class="col-auto text-center differences-feature-icon">
                                    <i class="fas fa-plane-departure fa-4x text-primary"></i>
                                </div>
                                <div class="col difference-text text-md-start text-sm-center">
                                    <p><strong>Fast, Reliable Aircraft Rentals</strong><br>Book and fly with minimal
                                        turnaround time and maximum availability.</p>
                                </div>
                            </div>

                            <div class="row difference-row align-items-center my-3">
                                <div class="col-auto text-center differences-feature-icon">
                                    <i class="fas fa-wrench fa-4x text-primary"></i>
                                </div>
                                <div class="col difference-text text-md-start text-sm-center">
                                    <p><strong>Expert Aircraft Maintenance</strong><br>Handled by certified technicians
                                        with strict adherence to aviation standards.</p>
                                </div>
                            </div>

                            <div class="row difference-row align-items-center my-3">
                                <div class="col-auto text-center differences-feature-icon">
                                    <i class="fas fa-cogs fa-4x text-secondary"></i>
                                </div>
                                <div class="col difference-text text-md-start text-sm-center">
                                    <p><strong>Genuine Aircraft Parts Supply</strong><br>We stock and source OEM and
                                        FAA-approved components with rapid delivery.</p>
                                </div>
                            </div>

                            <div class="row difference-row align-items-center my-3">
                                <div class="col-auto text-center differences-feature-icon">
                                    <i class="fas fa-parachute-box fa-4x text-danger"></i>
                                </div>
                                <div class="col difference-text text-md-start text-sm-center">
                                    <p><strong>Skydiving & Glider Support</strong><br>Facilities and logistics tailored
                                        to freefall teams and glider pilots.</p>
                                </div>
                            </div>

                            <div class="row difference-row align-items-center my-3">
                                <div class="col-auto text-center differences-feature-icon">
                                    <i class="fas fa-headset fa-4x text-info"></i>
                                </div>
                                <div class="col difference-text text-md-start text-sm-center">
                                    <p><strong>Dedicated Aviation Support</strong><br>Our team ensures personalized
                                        assistance for every client, every mission.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Videos - Stacked Vertically -->
                <div class="col-12 col-lg-6 mb-4" id="differences-video-column">
                    <div class="row justify-content-center g-4">
                        <!-- Video 1 -->
                        <div class="col-12">
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/pE03a0xWerQ"
                                    title="90% of My Aviation Training Lessons in 5 Minutes"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                                </iframe>
                            </div>
                        </div>

                        <!-- Video 2 -->
                        <div class="col-12">
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/SHV2eCpg1U0"
                                    title="Second Abbyero Aviation Video"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="row justify-content-center my-4 text-center">
                <div class="col-12">
                    <span class="h4 text-white">Ready to Become A Pilot?
                        <a href="learntofly">
                            <button class="btn btn-lg btn-primary learn-button mt-2 mt-md-0">Find out the 6 Easy
                                Steps</button>
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <div class='container-fluid' id='screenshots-container'>
            <!-- Aviation Services Overview Section -->
            <div class="container-fluid my-5" id="aviation-services-section">

                <!-- Section Header -->
                <div class="row my-2" id="aviation-services-header-row">
                    <div class="col-sm-12 mx-auto text-center" id="aviation-services-headline">
                        <h2 class="display-6">Comprehensive Aviation Support Tailored for Your Journey</h2>
                    </div>
                </div>

                <!-- Two-column Text Description -->
                <div class="row gap-4 my-4" id="aviation-services-text-row">
                    <div class="col"></div>

                    <!-- Left Column -->
                    <div class="col-sm-12 col-md-4">
                        <p><strong>From aircraft rentals to glider support</strong>, Abbyero Aviation provides
                            dependable, professional services that cater to pilots, instructors, skydiving teams, and
                            aviation clubs. Whether you're planning your next solo flight or organizing an aerobatic
                            practice, we've got you covered with certified aircraft and expert coordination.</p>
                    </div>

                    <!-- Right Column -->
                    <div class="col-sm-12 col-md-4">
                        <p>Our services include <strong>flight rentals</strong>, <strong>aircraft maintenance</strong>,
                            <strong>OEM parts supply</strong>, <strong>aerobatic support</strong>, <strong>flight
                                instruction programs</strong>, <strong>skydiving logistics</strong>, and <strong>glider
                                towing</strong>. Everything is designed to help you fly further, safer, and smarter.
                        </p>
                    </div>

                    <div class="col"></div>
                </div>
            </div>

            <div class='row justify-content-center gap-4 my-2' id='screenshots-image-row'>
                <div class='col-sm-12 col-lg-3 col-md-5'> <img
                        alt='Screenshot of San Fransisco airspace in Abbyero Aviation&#39;s innovative 3D animation.'
                        src='assets/images/index/IMG-20250710-WA0159.jpg' height='360' width='640'
                        onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0203.jpg"'
                        class='course-screenshot' data-title='Immersive 3D Lessons'> </div>
                <div class='col-sm-12 col-lg-3 col-md-5'> <img
                        alt='Russ Still, Abbyero Aviation Chief Flight Instructor'
                        src='assets/images/index/IMG-20250710-WA0136.jpg' height='360' width='640'
                        onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0178.jpg"'
                        class='course-screenshot' data-title='Real World Examples'> </div>
                <div class='col-sm-12 col-lg-3 col-md-5'> <img alt='Abbyero Aviation Flight Instructor'
                        src='assets/images/index/IMG-20250710-WA0201.jpg' height='360' width='640'
                        onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0202.jpg"'
                        class='course-screenshot' data-title='Interactive Lessons'> </div>
            </div>
        </div>
        <div class='container-fluid' id='faa-wings-container'>
            <div class='row justify-content-center'>
                <div class='col-sm-12 col-md-4 text-center' id='wings-icon'> <img alt='FAA Safety Team Logo'
                        src='assets\images\index\FAAST_Lg_Logo.webp' height='250' width='250'
                        onerror='this.onerror=null,this.src="assets/images/index/FAAST_Lg_Logo.png"'> </div>
                <div class='col-sm-12 align-self-center col-md-6' id='wings-text'>
                    <h2 class='display-6'> Earn FAA <span class='px-2 wings-bold'>WINGS</span> Credit! </h2>
                    <p class='content-text'> Earn free <span class='px-1 wings-bold'>WINGS</span> credit on approved
                        courses when you print the course Certificate of Completion. This valuable credit provides many
                        benefits including Flight Review credit, possible reduction in insurance rates, and the award of
                        Basic, Advanced, and Master Wings from the FAA. </p>
                    <p class='content-text'> Use the same email address for your Abbyero Aviation account that's on <a
                            href='https://www.faasafety.gov/' target='_blank' rel='noopener noreferrer'><span
                                class='px-1 wings-safety'>your FAASafety.gov account.</span></a> </p>
                    <p class='content-text wings-button'> <a href='https://www.faasafety.gov/WINGS/pub/learn_more.aspx'
                            target='_blank' rel='noopener noreferrer'><button class='btn btn-lg btn-primary'> Learn more
                                about WINGS </button></a> </p>
                </div>
            </div>
        </div>
        <div class='container-fluid' id='gold-standard-container'>
            <div class='row my-2 text-center' id='gold-standard-headline-row'>
                <div class='col-12 text-center' id='gold-standard-headline-column'>
                    <h2 class='display-6'>Abbyero Aviation is the Gold Standard!</h2>
                </div>
            </div>
            <div class='row justify-content-center py-4'>
                <div class='col-12 card col-md-5 g-2 p-4 gap-2'>
                    <div class='row'>
                        <div class='text-center col-4 col-sm-4'> <img alt='Ethan Bennett Photo'
                                src='assets/images/index/IMG-20250710-WA0173.jpg' height='460' width='460'
                                onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0175.jpg"'
                                class='img-fluid testimonial-image'> </div>
                        <div class='col' style='padding-right:50px'>
                            <p class='py-2'>
                                "When I needed a reliable aircraft for cross-country hours, Abbyero Aviation made the
                                entire rental process smooth and stress-free. The aircraft were clean, well-maintained,
                                and ready on time. Their team was professional, flexible with scheduling, and always
                                prioritized safety. I wouldn’t rent anywhere else."
                            </p>
                            <span>- Ethan Bennett, Lakeland, Florida</span>
                        </div>

                    </div>
                </div>
                <div class='col-2 col-sm-1'></div>
                <div class='col-12 card col-md-5 g-2 p-4'>
                    <div class='row'>
                        <div class='text-center col-4 col-sm-4'> <img alt='Josh Larson Photo'
                                src='assets/images/index/IMG-20250710-WA0179.jpg' height='460' width='460'
                                onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0179.jpg"'
                                class='img-fluid testimonial-image'> </div>
                        <div class='col' style='padding-right:50px'>
                            <p class='py-2'>
                                "In aviation, having access to a dependable rental service is key — and Abbyero Aviation
                                delivers. Their fleet is top-notch and the booking process is simple and efficient. From
                                preflight to return, everything is seamless. Whether you're building hours or flying for
                                fun, Abbyero makes it easy and stress-free. I simply cannot recommend them enough."
                            </p>
                            <span>- Josh Larson, Santa Clarita, California</span>
                        </div>

                    </div>
                </div>
            </div>
            <div id='gold-standard-button-row'>
                <div class='justify-content-center d-grid d-sm-flex gap-2'> <a href='/signup'> <button
                            class='btn btn-secondary btn-lg btn-cta btn-cta-generic' type='button'> Start Your Ride
                        </button> </a> </div>
            </div>
        </div>
        <div class='container-fluid' id='guarantee-container'>
            <div class='row'>
                <div class='col-sm-12 col-md-4 text-center' id='guarantee-badge'> <img
                        alt='Abbyero Aviation Guarantee Badge' src='assets/images/index/badge.png' height='416'
                        width='423'
                        onerror='this.onerror=null,this.src="assets/images/index/guarantee-container-badge.png"'> </div>
                <div class='col-sm-12 align-self-center col-md-6' id='guarantee-text'>
                    <h2 class='display-6'>Your Flight Experience Is Guaranteed!</h2>
                    <p class='content-text'>
                        We stand behind every service we provide — from aircraft rentals and maintenance to aerobatics
                        and glider support. If you're not fully satisfied after your flight or scheduled service, we’ll
                        work to make it right or credit you toward your next booking.
                    </p>
                    <p class='content-text'>
                        Whether it’s your first rental or a recurring maintenance visit, Abbyero Aviation guarantees
                        professionalism, safety, and customer satisfaction — it’s our commitment to excellence in the
                        skies.
                    </p>

                    <div class='mt-3'>
                        <div class='trustpilot-widget' style='pointer-events:none'
                            data-businessunit-id='608aed0e19a0b0000196110a' data-locale='en-US'
                            data-style-height='100px' data-style-width='200px'
                            data-template-id='53aa8807dec7e10d38f59f32'></div>
                    </div>
                </div>
            </div>
        </div>
        <div class='container-fluid' id='organizations'>
            <div class='row'>
                <div class='col-sm-12 col-md-7' id='organizations-text-column'>
                    <div class='row' id='organizations-features-row'>
                        <div class='col-sm-12 mx-auto' id='organizations-features-column'>
                            <div class='row'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col' id='organizations-headline'>
                                    <h2 class='display-6'> Essential Features for Pilots, Operators & Aviation
                                        Enthusiasts </h2>
                                </div>
                            </div>

                            <!-- Feature 1: Flight Rentals -->
                            <div class='row organization-row' id='feature-rentals'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col-sm-12 col-md-2 col-lg-1 organization-feature-icon text-center'>
                                    <i class="fas fa-plane-departure fa-3x text-primary"></i>
                                </div>
                                <div class='col text-md-start text-sm-center align-self-center organization-text py-3'>
                                    Fast and reliable flight rentals with real-time availability and confirmation.
                                </div>
                            </div>

                            <!-- Feature 2: Maintenance -->
                            <div class='row organization-row' id='feature-maintenance'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col-sm-12 col-md-2 col-lg-1 organization-feature-icon text-center'>
                                    <i class="fas fa-tools fa-3x text-primary"></i>
                                </div>
                                <div class='col text-md-start text-sm-center align-self-center organization-text py-3'>
                                    Aircraft maintenance done by certified professionals, meeting top aviation
                                    standards.
                                </div>
                            </div>

                            <!-- Feature 3: Parts Supply -->
                            <div class='row organization-row' id='feature-parts'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col-sm-12 col-md-2 col-lg-1 organization-feature-icon text-center'>
                                    <i class="fas fa-cogs fa-3x text-primary"></i>
                                </div>
                                <div class='col text-md-start text-sm-center align-self-center organization-text py-3'>
                                    Access FAA-approved, genuine aircraft parts with rapid sourcing and delivery.
                                </div>
                            </div>

                            <!-- Feature 4: Skydiving & Gliders -->
                            <div class='row organization-row' id='feature-skydiving'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col-sm-12 col-md-2 col-lg-1 organization-feature-icon text-center'>
                                    <i class="fas fa-parachute-box fa-3x text-primary"></i>
                                </div>
                                <div class='col text-md-start text-sm-center align-self-center organization-text py-3'>
                                    Tailored support for skydiving teams and glider clubs with reliable logistics and
                                    coordination.
                                </div>
                            </div>

                            <!-- Feature 5: Support -->
                            <div class='row organization-row' id='feature-support'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col-sm-12 col-md-2 col-lg-1 organization-feature-icon text-center'>
                                    <i class="fas fa-headset fa-3x text-primary"></i>
                                </div>
                                <div class='col text-md-start text-sm-center align-self-center organization-text py-3'>
                                    Our dedicated aviation support team ensures personalized service for every flight.
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <div class='row organization-row' id='organization-cta-row'>
                                <div class='col-lg-2 col-md-1'></div>
                                <div class='col'>
                                    <a href='/contact'>
                                        <button class='btn btn-lg btn-primary'> Request Aviation Services </button>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <footer class='footer'>
        <div class='container'>
            <div class='footer-info'> <span id=''>© 2025 Abbyero Aviation | 11357 Lindenwood Ave El Paso Texas.</span>
                <span id='footer_links'> <a href='/privacy'>Privacy
                        Policy</a> • <a href='/terms'>Terms & Conditions</a> • <a href='/login'
                        class='footer-login'>Login</a> </span>
            </div>
            <div style='clear:both'></div>
            <div id='footer_contact'> <span><i class='fa fa-phone-alt'></i> <a href='tel:+1 (915) 9995352'
                        class='px-2'>+1 (915) 9995352</a> 9am-6pm EST Mon - Fri</span> </div>
            <div class="mt-3" id='footer_social'> <a href='https://www.facebook.com/profile.php?id=61593295282943' target='_blank'
                    rel='noopener noreferrer'> <img alt='Facebook' src='assets/images/index/fb-icon.webp' height='45'
                        width='45' onerror='this.onerror=null,this.src="assets/images/index/fb-icon.png"'> </a> <a
                    href='https://www.instagram.com/abbyeroaviation' target='_blank' rel='noopener noreferrer'>
                    <img alt='Instagram' src='assets/images/index/ig-icon.webp' height='45' width='45'
                        onerror='this.onerror=null,this.src="assets/images/index/ig-icon.png"'> </a> <a
                    href='https://www.youtube.com/@AbbyeroAviationLLC' target='_blank' rel='noopener noreferrer'> <img
                        alt='YouTube' src='assets/images/index/yt-icon.webp' height='45' width='45'
                        onerror='this.onerror=null,this.src="assets/images/index/yt-icon.png"'> </a> </div>
        </div>
    </footer>
    <div class='fade modal' id='enlargeImage' aria-hidden='true' aria-labelledby='enlargeImageLabel' tabindex='-1'>
        <div class='modal-dialog modal-dialog-centered modal-xl'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'></h5> <button class='btn-close' type='button' aria-label='Close'
                        data-bs-dismiss='modal'></button>
                </div>
                <div class='modal-body'> <img alt='enlarged image' class='enlarged-image'> </div>
            </div>
        </div>
    </div>
    <!-- Tawk.to Live Chat Script -->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var e = document.createElement("script"),
                t = document.getElementsByTagName("script")[0];
            e.async = true;
            e.src = "https://embed.tawk.to/65788ecd70c9f2407f7f3188/1hhferkee";
            e.charset = "UTF-8";
            e.setAttribute("crossorigin", "*");
            t.parentNode.insertBefore(e, t);
        })();
    </script>

    <!-- External Libraries -->
    <script src="../../common/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../../common/video-js/8.0.4/video.min.js" defer></script>
    <!-- <script src="../../common/bootstrap/5.3/js/bootstrap.bundle.min.js" defer></script> -->
    <script src="../../common/js-cookie/3.0.1/js.cookie.min.js" defer></script>
    <script src="../../common/scripts/traverse.min.js" defer></script>
    <script src="../../common/amplify-js/cognito/6.1.2/amazon-cognito-identity.min.js" defer></script>
    <script src="../../common/sweetalert2/11.4.8/sweetalert2.all.min.js" defer></script>
    <script src="../../common/modernizr/3.6.0/modernizr.webp.min.js" defer></script>
    <script src="../../common/scripts/notifications.min.js" defer></script>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ND5XSQH79P"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Project Scripts -->
    <script src="assets/js/navbar-solid.v2.min.js" defer></script>
    <script src="assets/js/chat.min.js" defer></script>
    <script src="assets/js/frontFunctions.v2.min.js" defer></script>
    <script src="assets/js/index.min.js" defer></script>
    <script src="assets/js/main.js" defer></script>
</body>