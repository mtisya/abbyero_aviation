@extends('layout')

@section('content')

<!-- Billboard for Desktop (visible lg and up) -->
<div class="container-fluid d-none d-lg-block" id="billboard-container_flights">
    <div class="row">
        <div class="col-sm-12 col-lg-11 px-4 py-5" id="billboard-text">
            <h1 class="banner-text display-2 text-start">
                Your <span class="billboard-text-underline">Flight Adventure</span><br>
                Starts with Abbyero Rentals
            </h1>
            <div class="row justify-content-start mt-4" id="billboard-cta-button">
                <div class="col-sm-12 col-12 col-lg-4 col-md-12">
                    @auth
                        {{-- Logged-in users go to available flights --}}
                        <a href="{{ route('flights.available') }}">
                            <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                Book a Flight Today
                            </button>
                        </a>
                    @else
                        {{-- Guests are redirected to login first --}}
                        <a href="{{ route('login') }}">
                            <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                Book a Flight Today
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
                Starts with Abbyero Rentals
            </h2>
            <div class="row justify-content-start mt-3" id="billboard-cta-button-mobile">
                <div class="col-10">
                    @auth
                        {{-- Logged-in users go to available flights --}}
                        <a href="{{ route('flights.available') }}">
                            <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                Book a Flight Today
                            </button>
                        </a>
                    @else
                        {{-- Guests are redirected to login first --}}
                        <a href="{{ route('login') }}">
                            <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                                Book a Flight Today
                            </button>
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Aircraft: Piper Cherokee -->
<div class="container-fluid py-4" id="faa-wings-container">
    <div class="row justify-content-center gx-4 gy-4">
        <!-- Aircraft Image -->
        <div class="col-12 col-md-5 text-center" id="wings-icon">
            <img id="main-aircraft-img1" src="/assets/images/index/IMG-20250710-WA0013.jpg" alt="Piper Cherokee"
                class="img-fluid rounded shadow d-block mx-auto"
                style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover;"
                onerror="this.onerror=null; this.src='/assets/images/index/IMG-20250710-WA0013.jpg';" />

            <!-- Thumbnails -->
            <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                <img src="/assets/images/index/IMG-20250710-WA0018.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img1" style="width: 80px; height: 60px; cursor: pointer;" />
                <img src="/assets/images/index/IMG-20250710-WA0021.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img1" style="width: 80px; height: 60px; cursor: pointer;" />
                <img src="/assets/images/index/IMG-20250710-WA0014.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img1" style="width: 80px; height: 60px; cursor: pointer;" />
            </div>
        </div>

        <!-- Description -->
        <div class="col-12 col-md-6 align-self-center" id="aircraft-details">
            <h2 class="display-6 text-center text-md-start">Piper <span class="fw-bold">Cherokee PA28-140</span> (N547FL)</h2>

            <p class="content-text">
                The <strong>Piper Cherokee PA28-140</strong> is a dependable, easy-to-fly aircraft perfect for flight training and recreational use. This aircraft has been upgraded to a <strong>160-hp Lycoming O-320 engine</strong> for better climb performance.
            </p>

            <p class="content-text">
                With a stable low-wing design and intuitive cockpit, it's ideal for beginners and experienced pilots alike.
            </p>

            <ul class="content-text">
                <li><strong>Model:</strong> PA28-140</li>
                <li><strong>Engine:</strong> 160-hp Lycoming O-320</li>
                <li><strong>Features:</strong> Optional retractable gear, constant-speed prop</li>
                <li><strong>Registration:</strong> N547FL (FAA-certified)</li>
            </ul>

            <div class="text-center text-md-start mt-4">
                @auth
                    <a href="{{ route('flights.available') }}" class="btn btn-lg btn-primary">Rent This Aircraft</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Rent This Aircraft</a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Aircraft: Piper Arrow -->
<div class="container-fluid py-4" id="faa-wings-container">
    <div class="row justify-content-center gx-4 gy-4">
        <!-- Description -->
        <div class="col-12 col-md-6 align-self-center" id="aircraft-details">
            <h2 class="display-6 text-center text-md-start">Piper <span class="fw-bold">Arrow PA-28R-200</span> (N2204T)</h2>

            <p class="content-text">
                The <strong>Piper Arrow PA-28R-200</strong> is a complex aircraft with a <strong>200-hp Lycoming IO-360 engine</strong> and <strong>retractable landing gear</strong> — ideal for advanced students and experienced pilots.
            </p>

            <p class="content-text">
                Its streamlined build, IFR-ready avionics, and responsive handling make it a favorite for cross-country flights and commercial training.
            </p>

            <ul class="content-text">
                <li><strong>Model:</strong> PA-28R-200</li>
                <li><strong>Engine:</strong> 200-hp Lycoming IO-360-C1C</li>
                <li><strong>Features:</strong> Retractable gear, fast cruise speed</li>
                <li><strong>Registration:</strong> N2204T (FAA-certified)</li>
            </ul>

            <div class="text-center text-md-start mt-4">
                @auth
                    <a href="{{ route('flights.available') }}" class="btn btn-lg btn-primary">Rent This Aircraft</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Rent This Aircraft</a>
                @endguest
            </div>
        </div>

        <!-- Image -->
        <div class="col-12 col-md-5 text-center" id="wings-icon">
            <img id="main-aircraft-img2" src="/assets/images/index/IMG-20250710-WA0150.jpg" alt="Piper Arrow"
                class="img-fluid rounded shadow d-block mx-auto"
                style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover;"
                onerror="this.onerror=null; this.src='/assets/images/index/IMG-20250710-WA0154.jpg';" />

            <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                <img src="/assets/images/index/IMG-20250710-WA0152.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img2" style="width: 80px; height: 60px; cursor: pointer;" />
                <img src="/assets/images/index/IMG-20250710-WA0151.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img2" style="width: 80px; height: 60px; cursor: pointer;" />
                <img src="/assets/images/index/IMG-20250710-WA0159.jpg" class="img-thumbnail aircraft-thumb"
                    data-target="main-aircraft-img2" style="width: 80px; height: 60px; cursor: pointer;" />
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
