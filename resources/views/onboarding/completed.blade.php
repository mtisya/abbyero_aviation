@extends('layout')

@section('content')

<section class="section-padding container pt-5 pb-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card card-light p-5 text-center">

                <img
                    src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                    alt="Abbyero Aviation"
                    style="max-width: 220px;"
                    class="mb-4"
                >

                <div class="mb-3">

                    <i
                        class="fas fa-check-circle text-success"
                        style="font-size: 60px;"
                    ></i>

                </div>

                <h2 class="gold-text">
                    Onboarding Completed
                </h2>

                <p class="mt-3">

                    Thank you for completing your Abbyero Aviation
                    onboarding information.

                </p>

                <p class="text-muted">

                    Our team will review your information and contact
                    you regarding the next steps in your flight training
                    enrollment.

                </p>

                <a
                    href="/"
                    class="btn btn-primary mt-3"
                >
                    Return to Website
                </a>

            </div>

        </div>

    </div>

</section>

@endsection