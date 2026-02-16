@extends('layout')

@section('content')
    {{-- Billboard Section (Desktop) --}}
    <div class="container-fluid d-none d-md-block" id="billboard-container_skydiving">
        <div class="row">
            <div class="col-sm-12 col-lg-11" id="billboard-text">
                <h1 class="banner-text display-2">
                    THE SKY IS <span class="billboard-text-underline">CALLING</span><br>
                </h1>
                <div class="row justify-content-end" id="billboard-cta-button">
                    <div class="col-lg-4 col-md-12">
                        <a href="#skydiving-events">
                            <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                BOOK A SKYDIVE NOW
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Billboard Section (Mobile) --}}
    <div class="container-fluid d-block d-md-none text-center" id="billboard-container_skydiving">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="banner-text h2">
                    THE SKY IS<span class="billboard-text-underline">CALLING</span>
                </h1>
                <div class="mt-3">
                    <a href="#skydiving-events">
                        <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                            BOOK A SKYDIVE NOW
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- Skydiving Events Section --}}
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-center align-items-center mb-3 text-center">
            <div>
                <h2 class="mb-0 blue-text">Ready to Fly?</h2><br>
                <h1 class="mb-0">Live Your Best Life</h1>
            </div>
        </div>
    </div>


    {{-- Success Message --}}
    @if (session('success'))
        <div id="alert-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div id="alert-message" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(function () {
            let alertBox = document.getElementById('alert-message');
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500); // Remove from DOM after fade out
            }
        }, 5000);
    </script>


    {{-- Events Grid --}}
    <div class="row ms-5 me-5" id="skydiving-events">
        @forelse($skydiving as $event)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="{{ $event->title }}"
                            style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="text-muted mb-1">
                            <i class="bi bi-geo-alt"></i> {{ $event->location }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}
                            at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                        </p>
                        <p class="fw-bold mb-3">
                            Price: ${{ number_format($event->price, 2) }}
                        </p>

                        <div class="mt-auto d-flex justify-content-center">
                            <a href="{{ route('skydiving.show', $event) }}" class="btn btn-info btn-sm">
                                Read More
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No skydiving events available.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $skydiving->links() }}
    </div>

    <div class="container mt-5 mb-5 text-center">
        <!-- Heading Section -->
        <div class="d-flex justify-content-center align-items-center mb-3 flex-column">
            <h2 class="blue-text mb-0 fst-italic">As long as the weather allows</h2>
            <h1 class="fw-bold">We’re ready to fly, so be spontaneous.</h1>
        </div>

        <!-- Button -->
        <p class="mt-3">
            <a href="javascript:void(0)" class="btn btn-primary btn-lg">
                Reserve Your Jump Today <i class="fas fa-long-arrow-alt-right"></i>
            </a>
        </p>
    </div>


    {{-- Extra Promo Section --}}
    <section class="mt-5 mb-5">
        <div class="container text-center text-white py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                            url('https://www.texasskydiving.com/wp-content/uploads/texas-skydiving-photo-34-1440x960.jpg') 
                            center/cover no-repeat;">

            <h2 class="mb-3">Welcome to Texas Skydiving!</h2>
            <h4 class="mb-4">We’re a small dropzone with big dropzone amenities - which is just how we like it.</h4>

            <p class="lead">
                We’ve created a resort-style oasis right here in the Abbyero Aviation countryside, making for a
                memorable mini-vacation with a life-changing experience baked right in! Located just a short drive
                from Austin, College Station, San Antonio, and Houston, we’re the #1 choice for skydiving in Central Texas.
            </p>
            <p class="lead mb-4">
                Oh, and did we mention we’re on a picture-perfect ranch complete with friendly, free-roaming farm animals?
            </p>

            <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                <a href="javascript:void(0)" class="btn btn-primary btn-lg">
                    Reserve Your Jump Today <i class="fas fa-long-arrow-alt-right"></i>
                </a>

                <a href="#" target="_blank" class="btn btn-outline-light btn-lg">
                    Read Our Story
                </a>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Frequently Asked Questions</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="accordion" id="faqAccordion">

                    {{-- Question 1 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeadingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapseOne" aria-expanded="false" aria-controls="faqCollapseOne">
                                Is there a minimum age requirement to skydive?
                            </button>
                        </h2>
                        <div id="faqCollapseOne" class="accordion-collapse collapse" aria-labelledby="faqHeadingOne"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We follow United States Parachute Association guidelines, which require all jumpers to be at
                                least
                                18 years of age. Bring a valid, government-issued photo ID with you as proof of age. Sorry,
                                permission
                                from a parent or guardian is not acceptable.
                            </div>
                        </div>
                    </div>

                    {{-- Question 2 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeadingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                Is there a weight limit to skydive?
                            </button>
                        </h2>
                        <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, students must be 250 lbs or less and height/weight proportionate in order to jump.
                                A fee of $30 will be applied to students over 220 lbs. Weight limits are imposed by gear
                                manufacturers
                                for safety reasons; they are not intended to be discriminatory or exclusive.
                            </div>
                        </div>
                    </div>

                    {{-- Question 3 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeadingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                What should I wear to skydive?
                            </button>
                        </h2>
                        <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Dress appropriately for the weather. In warmer months, T-shirt and shorts are fine; when
                                it’s cold, layers are a good idea.
                                Athletic shoes that lace up are best – no open-toed shoes, heels, boots, or anything with
                                hooks.
                                Empty your pockets, remove most jewelry, and tie back long hair before you skydive.
                            </div>
                        </div>
                    </div>

                    {{-- Question 4 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeadingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                                Can I bring a group skydiving?
                            </button>
                        </h2>
                        <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can and you should! Groups get a discount: groups of 6 to 9 people get $20 off per
                                jumper;
                                groups of 10 or more get $30 off per jumper.
                            </div>
                        </div>
                    </div>

                    {{-- Question 5 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeadingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapseFive" aria-expanded="false" aria-controls="faqCollapseFive">
                                What is your refund / reschedule / cancellation policy?
                            </button>
                        </h2>
                        <div id="faqCollapseFive" class="accordion-collapse collapse" aria-labelledby="faqHeadingFive"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Every reservation requires a $100 non-refundable deposit per person.
                                To reschedule, use the "Manage Booking" link in your confirmation email or contact us more
                                than 48 hours prior
                                to your arrival to avoid forfeiting your deposit (rescheduling due to weather is excepted).
                                No-shows will be charged full price.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- What Makes a Great Skydive Section --}}
    <div class="my-5">

        {{-- Full-width carousel --}}
        <div id="skydiveCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                {{-- Slide 1 --}}
                <div class="carousel-item active">
                    <img src="https://www.texasskydiving.com/wp-content/uploads/texas-skydiving-photo-18-960x640.jpg"
                        class="d-block w-100 carousel-img" alt="Smiling man waving at camera under canopy">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h2 class="fw-bold">What Makes a Great Skydive</h2>
                        <h3>The People</h3>
                        <p>At Abbyero Aviation Skydiving, you’re our guest – not just a number. We keep things low-volume by
                            choice
                            to provide a personal touch. Our team is passionate and crazy grateful to share the sky with
                            you.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="carousel-item">
                    <img src="https://www.texasskydiving.com/wp-content/uploads/texasskydiving-exit-skydiving-960x640.jpg"
                        class="d-block w-100 carousel-img" alt="Tandem student exiting aircraft with instructor">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h2 class="fw-bold">What Makes a Great Skydive</h2>
                        <h3>Culture of Safety</h3>
                        <p>Skydiving is risky, but we mitigate that risk through rigorous aircraft maintenance,
                            USPA-certified instructors, and state-of-the-art equipment that exceeds safety standards.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="carousel-item">
                    <img src="https://www.texasskydiving.com/wp-content/uploads/homepage-image-960x640.jpg"
                        class="d-block w-100 carousel-img" alt="Student preparing to exit aircraft">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h2 class="fw-bold">What Makes a Great Skydive</h2>
                        <h3>Price</h3>
                        <p>Cheapest isn’t always best. Maintaining aircraft, gear, and an A+ team comes at a cost,
                            but ensures safety and unforgettable experiences.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- Slide 4 --}}
                <div class="carousel-item">
                    <img src="https://www.texasskydiving.com/wp-content/uploads/david-moore-tandem-skydive-960x640.jpg"
                        class="d-block w-100 carousel-img" alt="Freefall tandem skydive with instructor">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h2 class="fw-bold">What Makes a Great Skydive</h2>
                        <h3>Professionalism</h3>
                        <p>This is our profession. From once-in-a-lifetime tandem jumps to advanced training,
                            we’ve been doing this for years and take customer service seriously.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- Slide 5 --}}
                <div class="carousel-item">
                    <img src="https://www.texasskydiving.com/wp-content/uploads/Eduardo-3-960x640.jpg"
                        class="d-block w-100 carousel-img" alt="Tandem landing in grassy field">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h2 class="fw-bold">What Makes a Great Skydive</h2>
                        <h3>Preparation</h3>
                        <p>For the best experience: get a good night’s sleep, arrive on time, bring your ID,
                            stay hydrated, eat normally, and wear lace-up shoes. We’ll take it from there!</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>

            {{-- Controls --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#skydiveCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#skydiveCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>

            {{-- Dots --}}
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#skydiveCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#skydiveCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#skydiveCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#skydiveCarousel" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#skydiveCarousel" data-bs-slide-to="4"></button>
            </div>
        </div>
    </div>



    </div>
@endsection