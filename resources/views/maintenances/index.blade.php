@extends('layout')

@section('content')

    <body>

        <main>
            <!-- Desktop Billboard -->
            <div class='container-fluid d-none d-lg-block' id='billboard-container_maintenace'>
                <div class='row'>
                    <div class='col-sm-12 col-lg-11' id='billboard-text'>
                        <h1 class='banner-text display-2'>
                            Keep Your <span class='billboard-text-underline'>Aircraft Airworthy</span><br>
                            with Expert Maintenance!
                        </h1>
                        <div class='row justify-content-end' id='billboard-cta-button'>
                            <div class='col-sm-12 col-12 col-lg-4 col-md-12'>
                                @if(auth()->check())
                                    <a href="{{ route('maintenances.create') }}">
                                        <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                            Book a Service Now
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}">
                                        <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                            Book a Service Now
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Billboard -->
            <div class='container-fluid d-block d-lg-none' id='billboard-container_maintenace'>
                <div class='row'>
                    <div class='col-12 text-center px-3 py-4' id='billboard-text'>
                        <h2 class='banner-text h2'>
                            Keep Your <span class='billboard-text-underline'>Aircraft Airworthy</span><br>
                            with Expert Maintenance!
                        </h2>
                        <div class='row justify-content-center mt-3' id='billboard-cta-button'>
                            <div class='col-12 col-md-8'>
                                @if(auth()->check())
                                    <a href="{{ route('maintenances.create') }}">
                                        <button class="btn btn-primary btn-cta btn-cta-generic w-100" type="button">
                                            Book a Service Now
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}">
                                        <button class="btn btn-primary btn-cta btn-cta-generic w-100" type="button">
                                            Book a Service Now
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="container mt-5 mb-5" id="instructor_banner_container">
                <div class="row justify-content-center text-center">
                    <div class="col-12 col-lg-9 bg-gradient card" id="instructor-banner">
                        <h2 style="padding-bottom: 10px;">Trusted Aircraft Maintenance Solutions</h2>
                        <p class="h5">
                            Abbyero Aviation delivers professional aircraft maintenance services for private owners,
                            flight schools, charter operators, and skydiving fleets — including scheduled inspections,
                            diagnostics, parts sourcing, and emergency repairs.
                        </p>
                        @php
                            if (auth()->check()) {
                                $url = auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard');
                            } else {
                                $url = route('login');
                            }
                        @endphp

                        <a href="{{ $url }}">
                            <button class="btn btn-primary btn-lg mt-3" type="button">
                                View Maintenance Services
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <!-- Our Solutions Section -->
            <div class="row mt-5 mb-5 our-solutions">
                <div class="col-12">
                    <h2 class="text-center mb-4">Our Solutions</h2>
                </div>

                <!-- CAMO -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="solution-card">
                        <h3>CAMO</h3>
                        <ul>
                            <li>Tech Records</li>
                            <li>AD/SB</li>
                            <li>Planning</li>
                        </ul>
                        <p>Streamline compliance and airworthiness with automated workflows.</p>
                        <a href="#" class="btn btn-sm btn-primary">Learn More</a>
                    </div>
                </div>

                <!-- MRO Management -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="solution-card">
                        <h3>MRO Management</h3>
                        <ul>
                            <li>Orders</li>
                            <li>Defects</li>
                            <li>Hangar</li>
                        </ul>
                        <p>Manage all maintenance events, checks, and tracking in one place.</p>
                        <a href="#" class="btn btn-sm btn-primary">Learn More</a>
                    </div>
                </div>

                <!-- Inventory & Materials -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="solution-card">
                        <h3>Inventory & Materials</h3>
                        <ul>
                            <li>Stock</li>
                            <li>Tools</li>
                            <li>Parts</li>
                        </ul>
                        <p>Monitor inventory, issue parts, control stock levels, and reduce shortages with live data.</p>
                        <a href="#" class="btn btn-sm btn-primary">Learn
                            More</a>
                    </div>
                </div>

                <!-- Safety & QMS -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="solution-card">
                        <h3>Safety & QMS</h3>
                        <ul>
                            <li>Reports</li>
                            <li>Risk</li>
                            <li>Audits</li>
                        </ul>
                        <p>Built-in safety, risk, and quality management aligned with global aviation standards.</p>
                        <a href="#" class="btn btn-sm btn-primary">Learn More</a>
                    </div>
                </div>
            </div>


            <div class="container-fluid" id="differences-container">
                <div class="row flex-wrap align-items-center">

                    <!-- Left Column: Maintenance Descriptions with Thumbnails -->
                    <div class="col-12 col-lg-6 mb-4" id="differences-text-column">
                        <div class="row" id="differences-row">
                            <div class="col-12 mx-auto" id="differences-column">
                                <div class="col-12 px-4" id="differences-headline">
                                    <h2 class="display-7 text-center text-lg-start">Why Choose Abbyero for Maintenance?</h2>
                                </div>

                                <!-- Feature 1 -->
                                <div class="row difference-row align-items-center my-4">
                                    <div class="col-auto p-0">
                                        <img src="assets/images/index/IMG-20250710-WA0260.jpg"
                                            data-target="assets/images/index/IMG-20250710-WA0260.jpg"
                                            class="img-fluid rounded feature-thumb" style="width: 80px; cursor: pointer;"
                                            alt="Hangar">
                                    </div>
                                    <div class="col-auto text-center">
                                        <i class="fas fa-tools fa-2x text-primary"></i>
                                    </div>
                                    <div class="col">
                                        <p><strong>Full-Service Maintenance Hangar</strong><br>
                                            Routine inspections to overhauls — piston & turbine aircraft.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 2 -->
                                <div class="row difference-row align-items-center my-4">
                                    <div class="col-auto p-0">
                                        <img src="assets/images/index/IMG-20250710-WA0033.jpg"
                                            data-target="assets/images/index/IMG-20250710-WA0033.jpg"
                                            class="img-fluid rounded feature-thumb" style="width: 80px; cursor: pointer;"
                                            alt="Compliance">
                                    </div>
                                    <div class="col-auto text-center">
                                        <i class="fas fa-clipboard-check fa-2x text-success"></i>
                                    </div>
                                    <div class="col">
                                        <p><strong>Regulatory Compliance & Logbooks</strong><br>
                                            KCAA & FAA-standard documentation included with every job.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 3 -->
                                <div class="row difference-row align-items-center my-4">
                                    <div class="col-auto p-0">
                                        <img src="assets/images/index/IMG-20250710-WA0228.jpg"
                                            data-target="assets/images/index/IMG-20250710-WA0228.jpg"
                                            class="img-fluid rounded feature-thumb" style="width: 80px; cursor: pointer;"
                                            alt="Parts">
                                    </div>
                                    <div class="col-auto text-center">
                                        <i class="fas fa-box-open fa-2x text-secondary"></i>
                                    </div>
                                    <div class="col">
                                        <p><strong>OEM & Certified Parts</strong><br>
                                            Fast delivery and traceability of genuine aircraft components.
                                        </p>
                                    </div>
                                </div>

                                <!-- Add more rows with same image if needed -->

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Large Image Preview -->
                    <div class="col-12 col-lg-6 mb-4" id="differences-image-column">
                        <div class="row justify-content-center g-4">
                            <div class="col-12">
                                <img id="main-maintenance-image" src="assets/images/index/IMG-20250710-WA0260.jpg"
                                    class="img-fluid rounded shadow" alt="Maintenance Image">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="row justify-content-center my-4 text-center">
                    <div class="col-12">
                        <span class="h4 text-white">Need a Service or Emergency Repair?
                            @php
                                if (auth()->check()) {
                                    $url = auth()->user()->role === 'admin'
                                        ? url('/admin/dashboard')
                                        : url('/dashboard');
                                } else {
                                    $url = route('login');
                                }
                            @endphp

                            <a href="{{ $url }}">
                                <button class="btn btn-lg btn-primary learn-button mt-2 mt-md-0">
                                    Request Maintenance Now
                                </button>
                            </a>

                        </span>
                    </div>
                </div>
            </div>

            <!-- JavaScript -->
            <script>
                const mainImage = document.getElementById('main-maintenance-image');
                const thumbs = document.querySelectorAll('.feature-thumb');

                let lastInteractionTime = new Date().getTime();
                let currentSlideIndex = 0;

                const updateMainImage = (newSrc) => {
                    mainImage.classList.add('fade');
                    setTimeout(() => {
                        mainImage.src = newSrc;
                        mainImage.classList.remove('fade');
                    }, 200);
                };

                // Hover-based preview
                thumbs.forEach((thumb, index) => {
                    thumb.addEventListener('mouseenter', () => {
                        const target = thumb.getAttribute('data-target');
                        updateMainImage(target);
                        currentSlideIndex = index;
                        lastInteractionTime = new Date().getTime();
                    });
                });

                // Auto-play if idle
                setInterval(() => {
                    const now = new Date().getTime();
                    if (now - lastInteractionTime > 5000) {
                        currentSlideIndex = (currentSlideIndex + 1) % thumbs.length;
                        const target = thumbs[currentSlideIndex].getAttribute('data-target');
                        updateMainImage(target);
                    }
                }, 4000);
            </script>



            <!-- Ready to Experience Real Support Section -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="support-card text-center p-4">
                            <h2>Ready to Experience Real Support?</h2>
                            <p class="mb-4">
                                See how our aviation experts can keep your operations running smoothly.
                            </p>
                            <div class="text-center text-md-start mt-4">
                                @php
                                    if (auth()->check()) {
                                        $url = auth()->user()->role === 'admin'
                                            ? url('/admin/dashboard')
                                            : url('/dashboard');
                                    } else {
                                        $url = route('login');
                                    }
                                @endphp

                                <div class="d-flex justify-content-center mt-3">
                                    <a href="{{ $url }}" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                                            fill="none" class="me-2">
                                            <path
                                                d="M3.15 12.1579L1.575 14.3684H0L1.05 10.5L0 6.63158H1.575L3.15 8.84211L8.925 8.84211L6.3 0H8.4L13.65 8.84211H19.425C20.2965 8.84211 21 9.58263 21 10.5C21 11.4174 20.2965 12.1579 19.425 12.1579H13.65L8.4 21H6.3L8.925 12.1579L3.15 12.1579Z"
                                                fill="white"></path>
                                        </svg>
                                        View Maintenance Records
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <style>
                .support-card {
                    background: #fff;
                    border-radius: 12px;
                    border: 1px solid #ddd;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
                    transition: all 0.3s ease;
                }

                .support-card:hover,
                .support-card:focus-within {
                    transform: translateY(-5px) scale(1.02);
                    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
                    border-color: #007bff;
                }

                @media (hover: none) {
                    .support-card:active {
                        transform: translateY(-3px) scale(1.01);
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
                    }
                }
            </style>

            <div class='container-fluid' id='gold-standard-container'>
                <div class='row my-2 text-center' id='gold-standard-headline-row'>
                    <div class='col-12 text-center' id='gold-standard-headline-column'>
                        <h2 class='display-6'>Abbyero Aviation — The Gold Standard in Aircraft Maintenance</h2>
                    </div>
                </div>
                <div class='row justify-content-center py-4'>

                    <!-- Testimonial 1 -->
                    <div class='col-12 card col-md-5 g-2 p-4 gap-2'>
                        <div class='row'>
                            <div class='text-center col-4 col-sm-4'>
                                <img alt='Ethan Bennett Photo' src='assets/images/index/IMG-20250710-WA0173.jpg'
                                    height='460' width='460'
                                    onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0175.jpg"'
                                    class='img-fluid testimonial-image'>
                            </div>
                            <div class='col' style='padding-right:50px'>
                                <p class='py-2'>
                                    "Abbyero Aviation has been instrumental in keeping my aircraft in top condition. Their
                                    maintenance crew is detail-oriented, transparent with inspections, and always on
                                    schedule. I
                                    trust them fully for both routine and urgent servicing."
                                </p>
                                <span>- Ethan Bennett, Lakeland, Florida</span>
                            </div>
                        </div>
                    </div>

                    <div class='col-2 col-sm-1'></div>

                    <!-- Testimonial 2 -->
                    <div class='col-12 card col-md-5 g-2 p-4'>
                        <div class='row'>
                            <div class='text-center col-4 col-sm-4'>
                                <img alt='Josh Larson Photo' src='assets/images/index/IMG-20250710-WA0179.jpg' height='460'
                                    width='460'
                                    onerror='this.onerror=null,this.src="assets/images/index/IMG-20250710-WA0179.jpg"'
                                    class='img-fluid testimonial-image'>
                            </div>
                            <div class='col' style='padding-right:50px'>
                                <p class='py-2'>
                                    "As a charter pilot, downtime means lost revenue. Abbyero’s maintenance turnaround is
                                    the best
                                    I've experienced — fast, reliable, and always compliant. I no longer worry about
                                    airworthiness
                                    delays."
                                </p>
                                <span>- Josh Larson, Santa Clarita, California</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div id='gold-standard-button-row'>
                    <div class='justify-content-center d-grid d-sm-flex gap-2'>
                        @php
                            if (auth()->check()) {
                                if (auth()->user()->role === 'admin') {
                                    $url = url('/admin/dashboard');
                                } else {
                                    $url = route('maintenances.create'); // for logged-in users
                                }
                            } else {
                                $url = route('login'); // for guests
                            }
                        @endphp

                        <a href="{{ $url }}">
                            <button class="btn btn-secondary btn-lg btn-cta btn-cta-generic" type="button">
                                Schedule Maintenance Now
                            </button>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Maintenance Guarantee Section -->
            <div class='container-fluid' id='guarantee-container'>
                <div class='row'>
                    <div class='col-sm-12 col-md-4 text-center' id='guarantee-badge'>
                        <img alt='Abbyero Aviation Guarantee Badge' src='assets/images/index/badge.png' height='416'
                            width='423'
                            onerror='this.onerror=null,this.src="assets/images/index/guarantee-container-badge.png"'>
                    </div>
                    <div class='col-sm-12 align-self-center col-md-6' id='guarantee-text'>
                        <h2 class='display-6'>Your Aircraft Is in Expert Hands — Guaranteed</h2>
                        <p class='content-text'>
                            At Abbyero Aviation, we take pride in delivering maintenance services that exceed regulatory and
                            performance standards. Whether it’s a pre-flight inspection, avionics check, or full airframe
                            overhaul —
                            your aircraft gets expert care backed by certified technicians and real accountability.
                        </p>
                        <p class='content-text'>
                            If you’re not completely satisfied with the outcome of any maintenance service, we’ll make it
                            right —
                            from rework at no cost to priority scheduling for your next visit. That’s our promise.
                        </p>

                        <div class='mt-3'>
                            <div class='trustpilot-widget' style='pointer-events:none'
                                data-businessunit-id='608aed0e19a0b0000196110a' data-locale='en-US'
                                data-style-height='100px' data-style-width='200px'
                                data-template-id='53aa8807dec7e10d38f59f32'>
                            </div>
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
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
        <!-- Project Scripts -->
        <script src="assets/js/navbar-solid.v2.min.js" defer></script>
        <script src="assets/js/chat.min.js" defer></script>
        <script src="assets/js/frontFunctions.v2.min.js" defer></script>
        <script src="assets/js/index.min.js" defer></script>
        <script src="assets/js/main.js" defer></script>
    </body>
@endsection