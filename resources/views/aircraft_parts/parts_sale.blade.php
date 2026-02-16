@extends('layout')

@section('content')

    <body>

        <main>

            <div class="container mt-5 our-solutions">
                <h2 class="text-center mb-5 mt-2">Aircraft Parts For Sale</h2>

                <div class="row">
                    @foreach($parts as $part)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="part-card position-relative">
                                <div class="part-img-wrapper">
                                    <img src="{{ $part->image ? asset('storage/' . ltrim($part->image, '/')) : asset('assets/images/index/default.jpg') }}"
                                        alt="{{ $part->name }}" class="img-fluid w-100 rounded">
                                </div>
                                <div class="overlay d-flex flex-column justify-content-center align-items-center gap-2">
                                    <!-- Quick View button opens modal -->
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#quickViewModal{{ $part->id }}">
                                        Quick View
                                    </button>
                                    <form action="{{ route('cart.add', $part->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                                    </form>
                                </div>
                                <div class="part-info mt-2 text-center">
                                    <h5>{{ $part->name }}</h5>
                                    <p class="text-muted">${{ number_format($part->price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick View Modal -->
                        <div class="modal fade" id="quickViewModal{{ $part->id }}" tabindex="-1"
                            aria-labelledby="quickViewLabel{{ $part->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quickViewLabel{{ $part->id }}">{{ $part->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Part Image -->
                                            <div class="col-md-6 text-center">
                                                <img src="{{ $part->image ? asset('storage/' . ltrim($part->image, '/')) : asset('assets/images/index/default.jpg') }}"
                                                    alt="{{ $part->name }}" class="img-fluid rounded mb-3">
                                            </div>

                                            <!-- Part Details -->
                                            <div class="col-md-6">
                                                <h4>{{ $part->name }}</h4>
                                                <p><strong>Part Number:</strong> {{ $part->part_number }}</p>
                                                <p><strong>Category:</strong> {{ $part->category }}</p>
                                                <p><strong>Quantity Available:</strong> {{ $part->quantity }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($part->status) }}</p>
                                                <h5 class="text-primary mb-3">${{ number_format($part->price, 2) }}</h5>

                                                <p class="text-muted">{{ $part->description ?? 'No description available.' }}
                                                </p>

                                                <form action="{{ route('cart.add', $part->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        Add to Cart
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

    </body>
@endsection