@extends('layout')

@section('content')

    <body>

        <main>
            <div class='container-fluid' id='billboard-container_parts'>

                {{-- Desktop version (visible on md/lg screens) --}}
                <div class="d-none d-md-block">
                    <div class='row'>
                        <div class='col-sm-12 col-lg-11' id='billboard-text'>
                            <h1 class='banner-text display-2'>
                                Find the <span class='billboard-text-underline'>Right Parts</span><br>
                                to Keep Your Aircraft Flying!
                            </h1>
                            <div class='row justify-content-end' id='billboard-cta-button'>
                                <div class='col-sm-12 col-lg-4 col-md-12'>
                                    @if(auth()->check())
                                        <a href="{{ route('aircraft_parts.partsSale') }}">
                                            <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                                Request Parts Qoutes
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('login', ['redirect' => url()->current()]) }}">
                                            <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                                Request Parts Qoutes
                                            </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile version (visible only on small screens) --}}
                <div class="d-block d-md-none">
                    <div class='row text-center'>
                        <div class='col-12' id='billboard-text'>
                            <h2 class='banner-text h2 mt-5'>
                                Find the <span class='billboard-text-underline'>Right Parts</span><br>
                                to Keep Your Aircraft Flying!
                            </h2>
                            <div class='mt-3 text-center'>
                                @if(auth()->check())
                                    <a href="{{ route('aircraft_parts.partsSale') }}">
                                        <button class="btn btn-primary btn-cta w-auto px-4" type="button">
                                            Request Parts Quotes
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}">
                                        <button class="btn btn-primary btn-cta w-auto px-4" type="button">
                                            Request Parts Quotes
                                        </button>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            <div class="container mt-5 mb-5" id="instructor_banner_container">
                <div class="row align-items-center">

                    <!-- Left Column: Text -->
                    <div class="col-12 col-lg-6 text-center text-lg-start">
                        <h2 class="mb-3">Aircraft Spare Parts And Engines Distribution</h2>
                        <p class="h5 mb-4">
                            We are a reliable supplier and distributor of the commercial and corporate aircraft parts.
                            Access to various parts inventories around the world,
                            directly or through consignments has been a main key of providing AOG services. A modern
                            approach to supplies,
                            competitive prices and custom-tailored services helped us to maintain an advance position and
                            keep up stable growth rates in this field.
                            We are presenting the lowest and a competitive maintenance cost to the operators with our
                            dedicated team.
                        </p>

                        @php
                            if (auth()->check()) {
                                if (auth()->user()->role === 'admin') {
                                    $url = url('/aircraft-parts');
                                } elseif (auth()->user()->role === 'user') {
                                    $url = url('/aircraftparts/parts-sale');
                                } else {
                                    $url = url('/dashboard'); // fallback for other roles
                                }
                            } else {
                                $url = route('login');
                            }
                        @endphp

                        <a href="{{ $url }}">
                            <button class="btn btn-primary btn-lg mt-2 mb-2" type="button">
                                Aircraft Parts For Sale
                            </button>
                        </a>

                    </div>

                    <!-- Right Column: Image -->
                    <div class="col-12 col-lg-6 text-center">
                        <img src="{{ asset('assets/images/index/IMG-20250710-WA0037.jpg') }}" alt="Aircraft Maintenance"
                            class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>



            <!-- Our Parts Section -->
            <div class="row m-5 m-5 our-solutions">
                <div class="col-12">
                    <h2 class="text-center mb-5 mt-5">Aircraft Parts For Sale</h2>
                </div>

                <!-- Example Part 1 -->
                <div class="col-md-6 col-lg-3 mb-4 mt-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/IMG-20250710-WA0235.jpg') }}" alt="Engine Part">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Aircraft Engine</h5>
                            <p class="text-muted">$15,000</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 2 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/110-2-262x320.jpg') }}" alt="Landing Gear">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Oil Filters</h5>
                            <p class="text-muted">$8,500</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 3 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/13-06722.jpg') }}" alt="Aircraft Carburetor">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Propeller Parts</h5>
                            <p class="text-muted">$12,000</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/IMG-20250710-WA0047.jpg') }}" alt="Flap Roller">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Engine Part</h5>
                            <p class="text-muted">$5,200</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-5 m-5 our-solutions">
                <!-- Example Part 1 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/IMG-20250710-WA0050.jpg') }}" alt="Engine Part">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Engine Parts</h5>
                            <p class="text-muted">$15,000</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 2 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/IMG-20250710-WA0125.jpg') }}" alt="Landing Gear">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Tools</h5>
                            <p class="text-muted">$8,500</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 3 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/IMG-20250710-WA0081.jpg') }}" alt="Aircraft Carburetor">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Carburetor</h5>
                            <p class="text-muted">$12,000</p>
                        </div>
                    </div>
                </div>

                <!-- Example Part 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="part-card position-relative">
                        <div class="part-img-wrapper">
                            <img src="{{ asset('assets/images/index/FLAP_ROLLER.jpg') }}" alt="Flap Roller">
                        </div>
                        <div class="overlay d-flex justify-content-center align-items-center">
                            <a href="#" class="btn btn-light btn-sm">Quick View</a>
                        </div>
                        <div class="part-info mt-2 text-center">
                            <h5>Flap Roller</h5>
                            <p class="text-muted">$5,200</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid py-5" id="gold-standard-container">
                <!-- Section Title -->
                <div class="row mb-4 text-center" id="gold-standard-headline-row">
                    <div class="col-12" id="gold-standard-headline-column">
                        <h2 class="display-6 fw-bold">
                            Abbyero Aviation — Reliable Aircraft Parts & Sourcing
                        </h2>
                        <p class="text-muted">
                            Comprehensive spare parts, certified engines, and fast AOG support — tailored to meet your
                            operational needs.
                        </p>
                    </div>
                </div>

                <!-- Services Row -->
                <div class="row justify-content-center g-4">
                    <!-- Service 1: Spare Parts & Distribution -->
                    <div class="col-12 col-md-4">
                        <div class="card h-100 shadow-sm border-0 p-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-4 text-center">
                                    <img src="assets/images/index/IMG-20250710-WA0228.jpg" alt="Aircraft Parts Distribution"
                                        class="img-fluid rounded testimonial-image"
                                        onerror="this.onerror=null;this.src='assets/images/index/IMG-20250710-WA0175.jpg';">
                                </div>
                                <div class="col">
                                    <p class="mb-2 fs-5">
                                        Reliable <strong>spare parts & engine distribution</strong> backed by worldwide
                                        inventory access.
                                        Our <strong>AOG services</strong> minimize downtime with competitive pricing and
                                        tailored support.
                                    </p>
                                    <span class="fw-semibold blue-text">— Aircraft Parts & Engines Distribution</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service 2: Sales & Exchange -->
                    <div class="col-12 col-md-4">
                        <div class="card h-100 shadow-sm border-0 p-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-4 text-center">
                                    <img src="assets/images/index/IMG-20250710-WA0033.jpg" alt="Sales and Exchange"
                                        class="img-fluid rounded testimonial-image"
                                        onerror="this.onerror=null;this.src='assets/images/index/IMG-20250710-WA0179.jpg';">
                                </div>
                                <div class="col">
                                    <p class="mb-2 fs-5">
                                        Supplying parts on <strong>outright or exchange</strong> basis — from consumables
                                        and rotables
                                        to <strong>engines & APU’s</strong>. All parts come with full traceability and
                                        FAA/EASA certifications.
                                    </p>
                                    <span class="fw-semibold blue-text">— Sales & Exchange Solutions</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service 3: Engines & APU’s -->
                    <div class="col-12 col-md-4">
                        <div class="card h-100 shadow-sm border-0 p-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-4 text-center">
                                    <img src="assets/images/index/IMG-20250710-WA0179.jpg" alt="Engines and APUs"
                                        class="img-fluid rounded testimonial-image"
                                        onerror="this.onerror=null;this.src='assets/images/index/IMG-20250710-WA0175.jpg';">
                                </div>
                                <div class="col">
                                    <p class="mb-2 fs-5">
                                        Stock of <strong>engines & APU’s</strong> with certifications from approved global
                                        repair shops.
                                        We support <strong>V2500-A5, CFM56, PW4000, CF6 series</strong>, plus APU’s
                                        <strong>GTCP131-9A/-9B, 331-200/500</strong>.
                                    </p>
                                    <span class="fw-semibold blue-text">— Engine & APU Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="row mt-5" id="gold-standard-button-row">
                    <div class="col text-center">
                        @php
                            if (auth()->check()) {
                                $url = auth()->user()->role === 'admin'
                                    ? url('/admin/dashboard')
                                    : route('maintenances.create');
                            } else {
                                $url = route('login');
                            }
                        @endphp

                        <a href="{{ $url }}">
                            <button class="btn btn-secondary btn-lg px-5 shadow-sm btn-cta" type="button">
                                Request Parts Now
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
            <div class="partner-logos py-1" style="background-color: #529dc0ff;">
                <div class="container text-center">
                    <h2 class="text-white mb-4 mt-4">Our Partners</h2>

                    <div class="logos-slider mb-5">
                        <div class="logos-track">
                            <!-- Example logos (repeat as needed) -->
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/Turbo-final.png') }}" alt="Partner 1">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/Ajw_logo-1.png') }}" alt="Partner 2">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/Standard_aero.png') }}" alt="Partner 3">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/rockewell-collins-1.png') }}" alt="Partner 4">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/Lufthansa-logo.png') }}" alt="Partner 5">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/eirtrade-logo-1.png') }}" alt="Partner 6">
                            </a>
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/index/Lufthansa-logo.png') }}" alt="Partner 7">
                            </a>
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