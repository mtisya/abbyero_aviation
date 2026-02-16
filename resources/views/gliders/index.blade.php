@extends('layout')

@section('content')
    {{-- Billboard Section (Desktop) --}}
    <div class="container-fluid d-none d-md-block" id="billboard-container_gliders">
        <div class="row">
            <div class="col-sm-12 col-lg-11" id="billboard-text">
                <h1 class="banner-text display-2">
                    SOAR WITH <span class="billboard-text-underline">OUR GLIDERS</span><br>
                </h1>
                <div class="row justify-content-end" id="billboard-cta-button">
                    <div class="col-lg-4 col-md-12">
                        <a href="#glider-list">
                            <button class="btn btn-primary btn-cta btn-cta-generic text-nowrap" type="button">
                                RENT A GLIDER NOW
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Billboard Section (Mobile) --}}
    <div class="container-fluid d-block d-md-none text-center" id="billboard-container_gliders">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="banner-text h2">
                    SOAR WITH <span class="billboard-text-underline">OUR GLIDERS</span>
                </h1>
                <div class="mt-3">
                    <a href="#glider-list">
                        <button class="btn btn-primary btn-cta btn-cta-generic w-10" type="button">
                            RENT A GLIDER NOW
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Heading Section --}}
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-center align-items-center mb-3 text-center">
            <div>
                <h2 class="mb-0 blue-text">Ready to Glide?</h2><br>
                <h1 class="mb-0">Experience the Freedom of Flight</h1>
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
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 5000);
    </script>

    {{-- Gliders Grid --}}
    <div class="row ms-5 me-5" id="glider-list">
        @forelse($gliders as $glider)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($glider->image)
                        <img src="{{ asset('storage/' . $glider->image) }}" class="card-img-top" alt="{{ $glider->model }}"
                            style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $glider->model }}</h5>
                        <p class="text-muted mb-1">
                            <i class="bi bi-123"></i> Registration: {{ $glider->registration }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="bi bi-people"></i> Capacity: {{ $glider->capacity }} seats
                        </p>
                        <p class="fw-bold mb-3">
                            ${{ number_format($glider->rental_price, 2) }} / hour
                        </p>
                        <p>
                            <span class="badge 
                                @if($glider->status == 'available') bg-success 
                                @elseif($glider->status == 'rented') bg-warning 
                                @else bg-danger @endif">
                                {{ ucfirst($glider->status) }}
                            </span>
                        </p>

                        <div class="mt-auto d-flex justify-content-center">
                            <a href="{{ route('gliders.show', $glider) }}" class="btn btn-info btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No gliders available at the moment.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $gliders->links() }}
    </div>

    {{-- Promo Section --}}
    <div class="container mt-5 mb-5 text-center">
        <div class="d-flex justify-content-center align-items-center mb-3 flex-column">
            <h2 class="blue-text mb-0 fst-italic">When the sky is clear...</h2>
            <h1 class="fw-bold">It’s time to glide!</h1>
        </div>
        <p class="mt-3">
            <a href="javascript:void(0)" class="btn btn-primary btn-lg">
                Reserve Your Flight Today <i class="fas fa-long-arrow-alt-right"></i>
            </a>
        </p>
    </div>

    {{-- Extra Promo Section --}}
<section class="mt-5 mb-5">
    <div class="container text-center text-white py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('/assets/images/index/IMG-20250710-WA0220.jpg') 
                        center/cover no-repeat;">

        <h2 class="mb-3">Welcome to Abbyero Aviation Gliders!</h2>
        <h4 class="mb-4">Experience the serenity of pure flight – no engine, just sky and silence.</h4>

        <p class="lead">
            Our modern gliders are perfect for students, enthusiasts, and adventure seekers alike. 
            With certified instructors and safe, scenic flights, gliding is the closest you’ll get to soaring like a bird. 
        </p>
        <p class="lead mb-4">
            Whether you’re looking for training, leisure, or just a unique experience, Abbyero Aviation has the right glider for you.
        </p>

        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
            <a href="javascript:void(0)" class="btn btn-primary btn-lg">
                Book Your Glider Ride <i class="fas fa-long-arrow-alt-right"></i>
            </a>

            <a href="#" target="_blank" class="btn btn-outline-light btn-lg">
                Learn More About Gliding
            </a>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Frequently Asked Questions</h2>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="accordion" id="gliderFaqAccordion">

                {{-- Question 1 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="gliderFaqHeadingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#gliderFaqCollapseOne" aria-expanded="false" aria-controls="gliderFaqCollapseOne">
                            Do I need prior flying experience to try gliding?
                        </button>
                    </h2>
                    <div id="gliderFaqCollapseOne" class="accordion-collapse collapse" aria-labelledby="gliderFaqHeadingOne"
                        data-bs-parent="#gliderFaqAccordion">
                        <div class="accordion-body">
                            No experience required! All introductory flights are conducted with certified instructors. 
                            You’ll be safely guided through takeoff, soaring, and landing.
                        </div>
                    </div>
                </div>

                {{-- Question 2 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="gliderFaqHeadingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#gliderFaqCollapseTwo" aria-expanded="false" aria-controls="gliderFaqCollapseTwo">
                            Is gliding safe?
                        </button>
                    </h2>
                    <div id="gliderFaqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="gliderFaqHeadingTwo"
                        data-bs-parent="#gliderFaqAccordion">
                        <div class="accordion-body">
                            Absolutely. Our gliders are maintained to the highest standards, and all flights are 
                            conducted by licensed instructors under strict safety regulations.
                        </div>
                    </div>
                </div>

                {{-- Question 3 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="gliderFaqHeadingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#gliderFaqCollapseThree" aria-expanded="false" aria-controls="gliderFaqCollapseThree">
                            What should I wear for a glider flight?
                        </button>
                    </h2>
                    <div id="gliderFaqCollapseThree" class="accordion-collapse collapse" aria-labelledby="gliderFaqHeadingThree"
                        data-bs-parent="#gliderFaqAccordion">
                        <div class="accordion-body">
                            Comfortable clothing and closed-toe shoes are recommended. Bring sunglasses and a light jacket, 
                            as the air can be cooler at higher altitudes.
                        </div>
                    </div>
                </div>

                {{-- Question 4 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="gliderFaqHeadingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#gliderFaqCollapseFour" aria-expanded="false" aria-controls="gliderFaqCollapseFour">
                            How long does a typical glider flight last?
                        </button>
                    </h2>
                    <div id="gliderFaqCollapseFour" class="accordion-collapse collapse" aria-labelledby="gliderFaqHeadingFour"
                        data-bs-parent="#gliderFaqAccordion">
                        <div class="accordion-body">
                            Introductory flights typically last 20–30 minutes depending on weather and lift conditions. 
                            Training flights may be longer as you progress.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- What Makes a Great Glider Flight Section --}}
<div class="my-5">
    <div id="gliderCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            {{-- Slide 1 --}}
            <div class="carousel-item active">
                <img src="/assets/images/index/IMG-20250710-WA0220.jpg"
                    class="d-block w-100 carousel-img" alt="Glider in flight over mountains">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center">
                    <div class="bg-dark bg-opacity-50 p-4 rounded">
                        <h2 class="fw-bold">What Makes a Great Glider Flight</h2>
                        <h3>Scenic Views</h3>
                        <p>Enjoy breathtaking views of the countryside and horizon while soaring silently through the skies.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">Learn More</a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="carousel-item">
                <img src="/assets/images/index/IMG-20250710-WA0218.jpg"
                    class="d-block w-100 carousel-img" alt="Pilot inside a glider cockpit">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center">
                    <div class="bg-dark bg-opacity-50 p-4 rounded">
                        <h2 class="fw-bold">What Makes a Great Glider Flight</h2>
                        <h3>Peaceful Experience</h3>
                        <p>No engine noise, just the sound of the wind. Gliding is pure, serene flight unlike any other.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">Learn More</a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="carousel-item">
                <img src="/assets/images/index/IMG-20250710-WA0219.jpg"
                    class="d-block w-100 carousel-img" alt="Glider landing on runway">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center">
                    <div class="bg-dark bg-opacity-50 p-4 rounded">
                        <h2 class="fw-bold">What Makes a Great Glider Flight</h2>
                        <h3>Training & Adventure</h3>
                        <p>Whether you’re here to learn or just for fun, our instructors ensure a safe, unforgettable experience.</p>
                        <a href="#" class="btn btn-primary btn-lg mt-3">Learn More</a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Controls --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#gliderCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#gliderCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>

        {{-- Dots --}}
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#gliderCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#gliderCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#gliderCarousel" data-bs-slide-to="2"></button>
        </div>
    </div>
</div>

{{-- Custom styles --}}
<style>
    .carousel-img {
        max-height: 500px; /* Reduce image height */
        object-fit: cover; /* Keep proportions, crop overflow */
    }
    .carousel-caption {
        bottom: 0; /* reset Bootstrap default bottom */
        top: 0;
        left: 0;
        right: 0;
    }
</style>

@endsection
