@extends('layout')

@section('content')

    <!-- Billboard for Desktop (visible lg and up) -->
    <div class="container-fluid d-none d-lg-block" id="billboard-container_flights">
        <div class="row">
            <div class="col-sm-12 col-lg-11 px-4 py-5" id="billboard-text">
                <h1 class="banner-text display-2 text-start">
                    Your <span class="billboard-text-underline">Flight Adventure</span><br>
                    Starts with AbbyEro Aircrafts
                </h1>
                <div class="row justify-content-start mt-4" id="billboard-cta-button">
                    <div class="col-sm-12 col-12 col-lg-4 col-md-12">
                        @auth
                            {{-- Logged-in users go to available flights --}}
                            <a href="{{ route('flights.available') }}">
                                <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                    Schedule Flight Today
                                </button>
                            </a>
                        @else
                            {{-- Guests are redirected to login first --}}
                            <a href="{{ route('login') }}">
                                <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                    Schedule Flight Today
                                </button>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Billboard for Mobile/Tablet (visible below lg) -->
    <div class="container-fluid d-block d-lg-none" id="billboard-container_flights">
        <div class="row">
            <div class="col-12 px-3 py-4 text-center" id="billboard-text-mobile">
                <h2 class="banner-text display-5">
                    Your <span class="billboard-text-underline">Flight Adventure</span><br>
                    Starts with AbbyEro Aircrafts
                </h2>
                <div class="row justify-content-start mt-3" id="billboard-cta-button-mobile">
                    <div class="col-10">
                        @auth
                            {{-- Logged-in users go to available flights --}}
                            <a href="{{ route('flights.available') }}">
                                <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                    Schedule Flight Today
                                </button>
                            </a>
                        @else
                            {{-- Guests are redirected to login first --}}
                            <a href="{{ route('login') }}">
                                <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                    Schedule Flight Today
                                </button>
                            </a>
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Aircraft: Piper Cherokee -->
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="row align-items-center g-4">
                <!-- Aircraft Image -->
                <div class="col-lg-5 text-center">
                    <img id="main-aircraft-img1" src="{{ asset('assets/images/index/IMG-20250710-WA0013.jpg') }}"
                        alt="Cessna 310J" class="img-fluid rounded-4 shadow-sm mb-2"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />
                    <img id="main-aircraft-img3" src="{{ asset('assets/images/index/N6326W_images (5).jpeg') }}"
                        alt="Cessna 310J" class="img-fluid rounded-4 shadow-sm"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <!-- Thumbnails -->
                    <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                        <img src="{{ asset('assets/images/index/IMG-20250710-WA0013.jpg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img1"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/N6326W_images (1).jpeg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img1"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/N6326W_images (15).jpeg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img1"
                            style="width: 80px; height: 60px; cursor: pointer;" />
                    </div>
                </div>


                <!-- Aircraft Details -->
                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">
                        ✈️ Piper Cherokee PA-28-140 <span class="text-muted">(160 HP STC)</span>
                    </h2>

                    <p class="text-muted">
                        The Piper Cherokee PA-28-140 is a proven primary training aircraft known for durability, stability,
                        and simplicity.
                        This aircraft is upgraded with a <strong>160 HP STC Lycoming O-320 engine</strong>, along with GPS
                        430W, dual G5s,
                        ILS capability, and a full engine monitor.
                    </p>

                    <p class="text-muted">
                        The upgrade improves climb and takeoff performance while maintaining the forgiving characteristics
                        of the Cherokee 140.
                    </p>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mt-4">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#cherokee-specs">
                                ⚙️ Specs
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cherokee-training">
                                🧠 Training
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cherokee-cost">
                                💰 Cost
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- SPECS -->
                        <div class="tab-pane fade show active" id="cherokee-specs">
                            <h6 class="fw-bold">Base Aircraft</h6>
                            <ul>
                                <li>105–115 knots cruise</li>
                                <li>6–8.5 GPH fuel burn</li>
                                <li>630–660 ft/min climb</li>
                                <li>48–52 knots stall</li>
                                <li>4 seats</li>
                            </ul>

                            <h6 class="fw-bold mt-3">160 HP STC Upgrade</h6>
                            <ul>
                                <li>Lycoming O-320 (160 HP)</li>
                                <li>+20 HP increase</li>
                                <li>~7–8.5 GPH fuel burn</li>
                                <li>+100–150 fpm climb improvement</li>
                                <li>+3–8 knots cruise gain</li>
                            </ul>

                            <p>👉 Performance closely matches Cherokee 160 / Warrior-class aircraft.</p>
                        </div>

                        <!-- TRAINING -->
                        <div class="tab-pane fade" id="cherokee-training">
                            <h6 class="fw-bold">Primary Training Platform</h6>
                            <ul>
                                <li>Private Pilot training</li>
                                <li>Time building</li>
                                <li>Early instrument training</li>
                            </ul>

                            <h6 class="fw-bold">Performance Benefits</h6>
                            <ul>
                                <li>Better takeoff & climb</li>
                                <li>Higher safety margin</li>
                            </ul>

                            <h6 class="fw-bold">Handling & Systems</h6>
                            <ul>
                                <li>Stable, forgiving airframe</li>
                                <li>Predictable stall characteristics</li>
                                <li>Simple systems (fixed gear & prop)</li>
                            </ul>

                            <p>👉 Ideal for building strong foundational flying skills.</p>
                        </div>

                        <!-- COST -->
                        <div class="tab-pane fade" id="cherokee-cost">
                            <ul>
                                <li>Aircraft: $120/hr</li>
                                <li>Fuel: ~$42/hr</li>
                                <li>Instructor: $50/hr</li>
                            </ul>

                            <table class="table table-sm">
                                <tr>
                                    <td>Aircraft</td>
                                    <td>$120</td>
                                </tr>
                                <tr>
                                    <td>Fuel</td>
                                    <td>$42</td>
                                </tr>
                                <tr>
                                    <td>Instructor</td>
                                    <td>$50</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>$212/hr</td>
                                </tr>
                            </table>

                            <p class="fw-bold text-success">👉 ~$210–$230 per hour</p>
                        </div>

                    </div>

                    <!-- Strengths -->
                    <div class="mt-4">
                        <h5 class="fw-bold">👍 Why Train in This Aircraft?</h5>
                        <ul>
                            <li>✔ Enhanced performance</li>
                            <li>✔ Low operating cost</li>
                            <li>✔ Excellent for fundamentals</li>
                            <li>✔ Stable and predictable</li>
                            <li>✔ Prepares for advanced aircraft</li>
                        </ul>
                    </div>

                    <!-- CTA -->
                    <div class="mt-4 d-flex flex-column flex-md-row gap-3 align-items-md-center">
                        <span class="fw-bold">📞 316-302-6304</span>

                        @auth
                            <a href="{{ route('flights.available') }}" class="btn btn-primary px-4">
                                Schedule This Aircraft
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary px-4">
                                Login to Schedule
                            </a>
                        @endguest
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="row align-items-center g-4">

            <!-- Aircraft Image -->
            <div class="col-lg-5 text-center">

                <img id="main-aircraft-img4"
                    src="{{ asset('assets/images/index/N6326W_images (12).jpeg') }}"
                    alt="Piper Cherokee PA-28-140"
                    class="img-fluid rounded-4 shadow-sm mb-2"
                    style="max-height:380px; object-fit:cover; width:100%;">

                <!-- Optional second image -->
                <img id="main-aircraft-img5"
                    src="{{ asset('assets/images/index/N6326W_images (11).jpeg') }}"
                    alt="Piper Cherokee PA-28-140"
                    class="img-fluid rounded-4 shadow-sm"
                    style="max-height:380px; object-fit:cover; width:100%;">

                <!-- Thumbnails -->
                <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">

                    <img src="{{ asset('assets/images/index/N6326W_images (14).jpeg') }}"
                        class="img-thumbnail aircraft-thumb"
                        data-target="main-aircraft-img4"
                        style="width:80px;height:60px;cursor:pointer;">

                    <img src="{{ asset('assets/images/index/N6326W_images (13).jpeg') }}"
                        class="img-thumbnail aircraft-thumb"
                        data-target="main-aircraft-img4"
                        style="width:80px;height:60px;cursor:pointer;">

                    <img src="{{ asset('assets/images/index/N6326W_images (10).jpeg') }}"
                        class="img-thumbnail aircraft-thumb"
                        data-target="main-aircraft-img4"
                        style="width:80px;height:60px;cursor:pointer;">

                </div>

            </div>

            <!-- Aircraft Details -->
            <div class="col-lg-7">

                <h2 class="fw-bold mb-2">
                    ✈️ Piper Cherokee PA-28-140
                    <span class="text-muted">(150 HP) – N6326W</span>
                </h2>

                <p class="text-muted">
                    The Piper Cherokee PA-28-140 is one of the world's most respected primary
                    training aircraft. Known for its forgiving handling, dependable reliability,
                    and economical operation, it has trained thousands of pilots worldwide.
                </p>

                <p class="text-muted">
                    N6326W features an upgraded
                    <strong>150 HP Lycoming O-320 engine</strong>, delivering stronger climb
                    performance, improved takeoff capability, and greater operational flexibility
                    while preserving the Cherokee's stable and confidence-inspiring flight
                    characteristics.
                </p>

                <!-- Tabs -->
                <ul class="nav nav-tabs mt-4">

                    <li class="nav-item">
                        <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#cherokee150-specs">
                            ⚙️ Specs
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#cherokee150-training">
                            🧠 Training
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#cherokee150-cost">
                            💰 Cost
                        </button>
                    </li>

                </ul>

                <div class="tab-content mt-3">

                    <!-- Specs -->
                    <div class="tab-pane fade show active"
                        id="cherokee150-specs">

                        <h6 class="fw-bold">
                            Aircraft Specifications
                        </h6>

                        <ul>
                            <li>150 HP Lycoming O-320 engine</li>
                            <li>4-seat low-wing aircraft</li>
                            <li>Cruise speed: 105–115 knots</li>
                            <li>Average fuel burn: ~7 GPH</li>
                            <li>Improved climb performance</li>
                            <li>Excellent takeoff capability</li>
                            <li>Simple fixed landing gear</li>
                            <li>Reliable certified trainer</li>
                        </ul>

                        <h6 class="fw-bold mt-3">
                            Performance Highlights
                        </h6>

                        <ul>
                            <li>Improved climb over original 140 HP model</li>
                            <li>Efficient cross-country performance</li>
                            <li>Excellent handling at training speeds</li>
                            <li>Predictable landing characteristics</li>
                            <li>Low operating costs</li>
                        </ul>

                        <p>
                            👉 A dependable, economical aircraft ideal for both local and
                            cross-country flight training.
                        </p>

                    </div>

                    <!-- Training -->
                    <div class="tab-pane fade"
                        id="cherokee150-training">

                        <h6 class="fw-bold">
                            Primary Flight Training
                        </h6>

                        <ul>
                            <li>Private Pilot License (PPL)</li>
                            <li>Flight Reviews</li>
                            <li>Time Building</li>
                            <li>Cross-country Training</li>
                            <li>Proficiency Training</li>
                        </ul>

                        <h6 class="fw-bold mt-3">
                            Maneuvers Taught
                        </h6>

                        <ul>
                            <li>Takeoffs & Landings</li>
                            <li>Slow Flight</li>
                            <li>Power-on & Power-off Stalls</li>
                            <li>Steep Turns</li>
                            <li>Ground Reference Maneuvers</li>
                            <li>Emergency Procedures</li>
                            <li>Radio Communication</li>
                            <li>Traffic Pattern Operations</li>
                        </ul>

                        <h6 class="fw-bold mt-3">
                            Why Students Love It
                        </h6>

                        <ul>
                            <li>Forgiving flight characteristics</li>
                            <li>Smooth, balanced controls</li>
                            <li>Excellent longitudinal stability</li>
                            <li>Predictable stall behavior</li>
                            <li>Simple cockpit layout</li>
                        </ul>

                        <p>
                            👉 Designed to help students build confidence while mastering
                            essential flying skills.
                        </p>

                    </div>

                    <!-- Cost -->
                    <div class="tab-pane fade"
                        id="cherokee150-cost">

                        <table class="table table-bordered">

                            <tr>
                                <td>Aircraft Rental (Dry)</td>
                                <td>$120/hr</td>
                            </tr>

                            <tr>
                                <td>Fuel</td>
                                <td>Not Included</td>
                            </tr>

                            <tr>
                                <td>Average Fuel Burn</td>
                                <td>~7 GPH</td>
                            </tr>

                            <tr>
                                <td>Instructor</td>
                                <td>$50/hr</td>
                            </tr>

                        </table>

                        <p class="text-success fw-bold">
                            ✔ One of the most economical certified training aircraft available.
                        </p>

                    </div>

                </div>

                <!-- Strengths -->
                <div class="mt-4">

                    <h5 class="fw-bold">
                        👍 Why Train in N6326W?
                    </h5>

                    <ul>
                        <li>✔ Proven primary flight trainer</li>
                        <li>✔ Reliable 150 HP Lycoming engine</li>
                        <li>✔ Enhanced climb performance</li>
                        <li>✔ Stable and forgiving handling</li>
                        <li>✔ Excellent for solo and cross-country training</li>
                        <li>✔ Spacious side-by-side seating</li>
                        <li>✔ Low operating costs</li>
                        <li>✔ Builds confidence from first lesson to checkride</li>
                    </ul>

                </div>

                <!-- CTA -->
                <div class="mt-4 d-flex flex-column flex-md-row gap-3 align-items-md-center">

                    <span class="fw-bold">
                        📞 316-302-6304
                    </span>

                    @auth
                        <a href="{{ route('flights.available') }}"
                            class="btn btn-primary px-4">
                            Schedule This Aircraft
                        </a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                            class="btn btn-primary px-4">
                            Login to Schedule
                        </a>
                    @endguest

                </div>

            </div>

        </div>
    </div>
    </div>

    <!-- Aircraft: Piper Arrow II -->
    <div class="container py-5" id="faa-wings-container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="row align-items-center g-4">

                <!-- Aircraft Details (LEFT on desktop) -->
                <div class="col-lg-7 order-2 order-lg-1">

                    <h2 class="fw-bold mb-2">
                        ✈️ Piper Arrow II <span class="text-muted">(N2204T)</span>
                    </h2>

                    <p class="text-muted">
                        The Piper Arrow II (PA-28R-200) is a widely used complex training aircraft featuring a
                        <strong>200 HP Lycoming IO-360 engine</strong>, retractable landing gear, and a constant-speed
                        propeller.
                    </p>

                    <p class="text-muted">
                        It bridges the gap between basic trainers and high-performance aircraft, making it ideal for
                        commercial training and complex endorsements.
                    </p>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mt-4">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#arrow-specs">
                                ⚙️ Specs
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#arrow-training">
                                🧠 Training
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#arrow-cost">
                                💰 Cost
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- SPECS -->
                        <div class="tab-pane fade show active" id="arrow-specs">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-gear-fill text-primary me-2"></i>Lycoming IO-360 (200 HP)</li>
                                <li><i class="bi bi-speedometer2 text-primary me-2"></i>130–143 knots cruise</li>
                                <li><i class="bi bi-fuel-pump text-primary me-2"></i>~8–10 GPH</li>
                                <li><i class="bi bi-globe text-primary me-2"></i>~600 NM range</li>
                                <li><i class="bi bi-arrow-up text-primary me-2"></i>830–900 ft/min climb</li>
                                <li><i class="bi bi-cloud text-primary me-2"></i>15,000 ft ceiling</li>
                                <li><i class="bi bi-people text-primary me-2"></i>4 occupants</li>
                            </ul>

                            <p class="mt-2">
                                👉 Optimized for efficient training at ~8 GPH fuel burn.
                            </p>
                        </div>

                        <!-- TRAINING -->
                        <div class="tab-pane fade" id="arrow-training">

                            <h6 class="fw-bold">1. Complex Aircraft Training</h6>
                            <ul>
                                <li>Retractable landing gear</li>
                                <li>Constant-speed propeller</li>
                                <li>Power/prop/mixture coordination</li>
                            </ul>

                            <h6 class="fw-bold">2. Forgiving Handling</h6>
                            <ul>
                                <li>Stable and predictable</li>
                                <li>~56 knot stall speed</li>
                                <li>Student-friendly controls</li>
                            </ul>

                            <h6 class="fw-bold">3. Training Progression</h6>
                            <ul>
                                <li>Step up from Cessna 172 / Warrior</li>
                                <li>Prepares for commercial & IFR flying</li>
                            </ul>

                            <p>
                                👉 Learn complex systems without overwhelming workload.
                            </p>
                        </div>

                        <!-- COST -->
                        <div class="tab-pane fade" id="arrow-cost">

                            <p><strong>Assumptions:</strong></p>
                            <ul>
                                <li>Aircraft: $140/hr</li>
                                <li>Fuel: 8 GPH</li>
                                <li>Instructor: $50/hr</li>
                            </ul>

                            <p><strong>Fuel Cost:</strong> 8 × $6 = $48/hr</p>

                            <table class="table table-sm">
                                <tr>
                                    <td>Aircraft</td>
                                    <td>$140</td>
                                </tr>
                                <tr>
                                    <td>Fuel</td>
                                    <td>$48</td>
                                </tr>
                                <tr>
                                    <td>Instructor</td>
                                    <td>$50</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>$238/hr</td>
                                </tr>
                            </table>

                            <p class="fw-bold text-success">👉 ~$230–$250 per hour</p>
                        </div>

                    </div>

                    <!-- Strengths -->
                    <div class="mt-4">
                        <h5 class="fw-bold">👍 Why Train in the Arrow II?</h5>
                        <ul>
                            <li>✔ Cost-effective complex training</li>
                            <li>✔ Teaches gear & prop management</li>
                            <li>✔ Stable and predictable</li>
                            <li>✔ Ideal for IFR & commercial training</li>
                            <li>✔ Versatile for cross-country</li>
                        </ul>
                    </div>

                    <!-- Verdict -->
                    <div class="mt-3">
                        <p><strong>⭐ Verdict:</strong> Best for complex endorsement and commercial training.</p>

                        <p>
                            <strong>🏁 Final Thoughts:</strong> A balanced aircraft delivering essential complex experience
                            with manageable workload and strong cost efficiency.
                        </p>
                    </div>

                    <!-- CTA -->
                    <div class="mt-4 d-flex flex-column flex-md-row gap-3 align-items-md-center">
                        <span class="fw-bold">📞 316-302-6304</span>

                        @auth
                            <a href="{{ route('flights.available') }}" class="btn btn-primary px-4">
                                Schedule This Aircraft
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary px-4">
                                Login to Schedule
                            </a>
                        @endguest
                    </div>

                </div>

                <!-- Aircraft Image (RIGHT on desktop) -->
                <div class="col-lg-5 text-center order-1 order-lg-2">

                    <img id="main-aircraft-img2" src="{{ asset('assets/images/index/IMG-20250710-WA0150.jpg') }}"
                        class="img-fluid rounded-4 shadow-sm mb-2"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <img id="main-aircraft-img2" src="{{ asset('assets/images/index/IMG-20250710-WA0154.jpg') }}"
                        class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <!-- Thumbnails -->
                    <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                        <img src="{{ asset('assets/images/index/IMG-20250710-WA0152.jpg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img2"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/IMG-20250710-WA0151.jpg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img2"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/IMG-20250710-WA0159.jpg') }}"
                            class="img-thumbnail aircraft-thumb" data-target="main-aircraft-img2"
                            style="width: 80px; height: 60px; cursor: pointer;" />
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- Aircraft: Cessna 310J -->
    <div class="container py-5" id="faa-wings-container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="row align-items-center g-4">

                <!-- Aircraft Image -->
                <div class="col-lg-5 text-center">
                    <img id="main-aircraft-img3" src="{{ asset('assets/images/index/Cessna.jpeg') }}" alt="Cessna 310J"
                        class="img-fluid rounded-4 shadow-sm mb-2"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />
                    <img id="main-aircraft-img3" src="{{ asset('assets/images/index/Cessna7.jpeg') }}" alt="Cessna 310J"
                        class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <!-- Thumbnails -->
                    <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                        <img src="{{ asset('assets/images/index/Cessna4.jpeg') }}" class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img3" style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/Cessna7.jpeg') }}" class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img3" style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/Cessna.jpeg') }}" class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img3" style="width: 80px; height: 60px; cursor: pointer;" />
                    </div>
                </div>



                <!-- Aircraft Details -->
                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">
                        ✈️ Cessna 310J <span class="text-muted">(N310J)</span>
                    </h2>

                    <p class="text-muted">
                        The Cessna 310J is a classic light twin-engine aircraft produced during the mid-1960s, part of the
                        long-running 310 series (1954–1980).
                        It is widely respected for its performance, speed, and twin-engine training value.
                    </p>

                    <p class="text-muted">
                        The “J” model (1965) introduced refinements including increased gross weight (~5,100 lbs) while
                        retaining the twin Continental IO-470 engines (260 HP each).
                    </p>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mt-4">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#specs">
                                ⚙️ Specs
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#training">
                                🧠 Training
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cost">
                                💰 Cost
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- SPECS -->
                        <div class="tab-pane fade show active" id="specs">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-gear-fill text-primary me-2"></i>2 × Continental IO-470 (260 HP)</li>
                                <li><i class="bi bi-speedometer2 text-primary me-2"></i>190–194 knots (~220 mph)</li>
                                <li><i class="bi bi-fuel-pump text-primary me-2"></i>~25 GPH (22 GPH training)</li>
                                <li><i class="bi bi-globe text-primary me-2"></i>687–1000 NM range</li>
                                <li><i class="bi bi-arrow-up text-primary me-2"></i>1,500–1,600 ft/min climb</li>
                                <li><i class="bi bi-cloud text-primary me-2"></i>20,000 ft ceiling</li>
                                <li><i class="bi bi-people text-primary me-2"></i>4–6 occupants</li>
                            </ul>

                            <p class="mt-2">
                                👉 22 GPH burn aligns well with real-world training operations at reduced power settings.
                            </p>
                        </div>

                        <!-- TRAINING -->
                        <div class="tab-pane fade" id="training">

                            <h6 class="fw-bold">1. Real Twin-Engine Systems</h6>
                            <ul>
                                <li>Fuel management (tip & auxiliary tanks)</li>
                                <li>Engine-out procedures</li>
                                <li>Constant-speed propellers</li>
                                <li>Electrical redundancy</li>
                            </ul>

                            <h6 class="fw-bold">2. Performance Margin</h6>
                            <ul>
                                <li>~1,500+ ft/min climb</li>
                                <li>Single-engine climb ~300–400 ft/min</li>
                                <li>Vmc demonstrations</li>
                                <li>Engine failure drills</li>
                                <li>Feathering & restart techniques</li>
                            </ul>

                            <h6 class="fw-bold">3. Complex Aircraft Experience</h6>
                            <ul>
                                <li>Retractable landing gear</li>
                                <li>Constant-speed props</li>
                                <li>Advanced systems management</li>
                            </ul>

                            <p>
                                👉 More powerful and realistic than Seminole or Duchess trainers.
                            </p>
                        </div>

                        <!-- COST -->
                        <div class="tab-pane fade" id="cost">

                            <p><strong>Assumptions:</strong></p>
                            <ul>
                                <li>Aircraft: $200/hr (dry)</li>
                                <li>Fuel: 22 GPH</li>
                                <li>Instructor: $50/hr</li>
                            </ul>

                            <p><strong>Fuel Cost:</strong> 22 × $6 = $132/hr</p>

                            <table class="table table-sm">
                                <tr>
                                    <td>Aircraft</td>
                                    <td>$200</td>
                                </tr>
                                <tr>
                                    <td>Fuel</td>
                                    <td>$132</td>
                                </tr>
                                <tr>
                                    <td>Instructor</td>
                                    <td>$50</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>$382/hr</td>
                                </tr>
                            </table>

                            <p class="fw-bold text-success">👉 ~$380–$400 per hour</p>

                        </div>

                    </div>

                    <!-- Strengths -->
                    <div class="mt-4">
                        <h5 class="fw-bold">👍 Why Train in the 310J?</h5>
                        <ul>
                            <li>✔ ~190 knot cruise for efficient cross-country</li>
                            <li>✔ Strong climb performance</li>
                            <li>✔ Real-world twin-engine experience</li>
                            <li>✔ Stable IFR handling</li>
                            <li>✔ Comfortable cabin</li>
                        </ul>
                    </div>

                    <!-- Verdict -->
                    <div class="mt-3">
                        <p><strong>⭐ Verdict:</strong> Best for serious multi-engine training and time building.</p>

                        <p>
                            <strong>🏁 Final Thoughts:</strong> A premium training aircraft delivering realistic workload,
                            strong performance, and career-relevant flying experience.
                        </p>
                    </div>

                    <!-- CTA -->
                    <div class="mt-4 d-flex flex-column flex-md-row gap-3 align-items-md-center">
                        <span class="fw-bold">📞 316-302-6304</span>

                        @auth
                            <a href="{{ route('flights.available') }}" class="btn btn-primary px-4">
                                Coming Soon: Schedule This Aircraft
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary px-4">
                                Login to Schedule
                            </a>
                        @endguest
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Aircraft: Aero Commander 560E -->
    <div class="container py-5" id="aero-commander-container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="row align-items-center g-4">

                <!-- Aircraft Details -->
                <div class="col-lg-7 order-2 order-lg-1">

                    <h2 class="fw-bold mb-2">
                        ✈️ Aero Commander 560E <span class="text-muted">(N4618E)</span>
                    </h2>

                    <p class="text-muted">
                        The Aero Commander 560E is a professional-grade twin-engine aircraft designed for advanced
                        multi-engine, IFR, and commercial flight training.
                    </p>

                    <p class="text-muted">
                        Operated by Abbyero Aviation LLC, N4618E provides students with a real-world corporate aviation
                        training environment featuring powerful piston engines, exceptional stability, and advanced systems management.
                    </p>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mt-4">
                        <li class="nav-item">
                            <button class="nav-link active"
                                    data-bs-toggle="tab"
                                    data-bs-target="#commander-specs">
                                ⚙️ Specs
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link"
                                    data-bs-toggle="tab"
                                    data-bs-target="#commander-training">
                                🧠 Training
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link"
                                    data-bs-toggle="tab"
                                    data-bs-target="#commander-operational">
                                🛠 Operations
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- SPECS -->
                        <div class="tab-pane fade show active"
                            id="commander-specs">

                            <ul class="list-unstyled">
                                <li>
                                    <i class="bi bi-gear-fill text-primary me-2"></i>
                                    Twin piston engines
                                </li>

                                <li>
                                    <i class="bi bi-speedometer2 text-primary me-2"></i>
                                    Approx. 190–220 knot cruise
                                </li>

                                <li>
                                    <i class="bi bi-fuel-pump text-primary me-2"></i>
                                    Advanced fuel & engine systems
                                </li>

                                <li>
                                    <i class="bi bi-globe text-primary me-2"></i>
                                    Excellent cross-country capability
                                </li>

                                <li>
                                    <i class="bi bi-arrow-up text-primary me-2"></i>
                                    Strong multi-engine climb performance
                                </li>

                                <li>
                                    <i class="bi bi-cloud text-primary me-2"></i>
                                    Stable IFR platform
                                </li>

                                <li>
                                    <i class="bi bi-people text-primary me-2"></i>
                                    Spacious executive-style cabin
                                </li>
                            </ul>

                            <p class="mt-2">
                                👉 Built for serious professional multi-engine training.
                            </p>

                        </div>

                        <!-- TRAINING -->
                        <div class="tab-pane fade"
                            id="commander-training">

                            <h6 class="fw-bold">
                                1. Multi-Engine Training
                            </h6>

                            <ul>
                                <li>Engine-out procedures</li>
                                <li>Vmc demonstrations</li>
                                <li>Asymmetric thrust management</li>
                                <li>Feathering procedures</li>
                            </ul>

                            <h6 class="fw-bold">
                                2. Advanced IFR Operations
                            </h6>

                            <ul>
                                <li>Professional cockpit management</li>
                                <li>Instrument approaches</li>
                                <li>Crew coordination concepts</li>
                                <li>Systems monitoring discipline</li>
                            </ul>

                            <h6 class="fw-bold">
                                3. Commercial Transition Experience
                            </h6>

                            <ul>
                                <li>High-performance aircraft handling</li>
                                <li>Complex systems awareness</li>
                                <li>Corporate aviation environment</li>
                                <li>Cross-country operational planning</li>
                            </ul>

                            <p>
                                👉 Experience real-world multi-engine workload in a stable and capable aircraft.
                            </p>

                        </div>

                        <!-- OPERATIONS -->
                        <div class="tab-pane fade"
                            id="commander-operational">

                            <h6 class="fw-bold">
                                Operational Strengths
                            </h6>

                            <ul>
                                <li>✔ Exceptional directional stability</li>
                                <li>✔ Smooth ride quality in turbulence</li>
                                <li>✔ Strong single-engine controllability</li>
                                <li>✔ Spacious cabin for instruction</li>
                                <li>✔ Airline-style training environment</li>
                            </ul>

                            <h6 class="fw-bold mt-4">
                                Maintenance Considerations
                            </h6>

                            <ul>
                                <li>Detailed inspection schedules</li>
                                <li>Structural monitoring programs</li>
                                <li>Corrosion prevention management</li>
                                <li>Professional engine & prop maintenance</li>
                            </ul>

                            <p class="fw-bold text-success">
                                👉 A premium training platform requiring professional operational discipline.
                            </p>

                        </div>

                    </div>

                    <!-- Strengths -->
                    <div class="mt-4">

                        <h5 class="fw-bold">
                            👍 Why Train in the Aero Commander?
                        </h5>

                        <ul>
                            <li>✔ Professional multi-engine environment</li>
                            <li>✔ Excellent IFR capability</li>
                            <li>✔ Superior engine-out training platform</li>
                            <li>✔ Stable “big airplane” handling feel</li>
                            <li>✔ Corporate aviation exposure</li>
                            <li>✔ Spacious and comfortable cabin</li>
                        </ul>

                    </div>

                    <!-- Verdict -->
                    <div class="mt-3">

                        <p>
                            <strong>⭐ Verdict:</strong>
                            Ideal for serious multi-engine, IFR, and commercial pilot development.
                        </p>

                        <p>
                            <strong>🏁 Final Thoughts:</strong>
                            The Aero Commander 560E N4618E delivers an authentic professional aviation training experience
                            rarely found in traditional flight schools.
                        </p>

                    </div>

                    <!-- CTA -->
                    <div class="mt-4 d-flex flex-column flex-md-row gap-3 align-items-md-center">

                        <span class="fw-bold">
                            📞 316-302-6304
                        </span>

                        @auth
                            <a href="{{ route('flights.available') }}"
                            class="btn btn-primary px-4">
                                Schedule This Aircraft
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                            class="btn btn-primary px-4">
                                Login to Schedule
                            </a>
                        @endguest

                    </div>

                </div>

                <!-- Aircraft Images -->
                <div class="col-lg-5 text-center order-1 order-lg-2">

                    <!-- Main Image -->
                    <img id="main-aircraft-img-commander"
                        src="{{ asset('assets/images/index/commander.jpeg') }}"
                        class="img-fluid rounded-4 shadow-sm mb-2"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <!-- Secondary Image -->
                    <img src="{{ asset('assets/images/index/commander1.jpg') }}"
                        class="img-fluid rounded-4 shadow-sm"
                        style="max-height: 380px; object-fit: cover; width: 100%;" />

                    <!-- Thumbnails -->
                    <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">

                        <img src="{{ asset('assets/images/index/commander2.jpeg') }}"
                            class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img-commander"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/commander3.jpg') }}"
                            class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img-commander"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                        <img src="{{ asset('assets/images/index/commander4.jpg') }}"
                            class="img-thumbnail aircraft-thumb"
                            data-target="main-aircraft-img-commander"
                            style="width: 80px; height: 60px; cursor: pointer;" />

                    </div>

                </div>

            </div>
        </div>
    </div>
    
    <!-- Image Swap Script -->
    <script>
        document.querySelectorAll('.aircraft-thumb').forEach(thumb => {
            thumb.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const mainImage = document.getElementById(targetId);
                if (mainImage) {
                    mainImage.classList.add('fade-out');
                    setTimeout(() => {
                        mainImage.src = this.src;
                        mainImage.classList.remove('fade-out');
                    }, 150);
                }
            });
        });
    </script>

    <!-- Optional CSS (can go in your CSS file) -->
    <style>
        .fade-out {
            opacity: 0.4;
            transition: opacity 0.15s ease-in-out;
        }
    </style>

@endsection