@extends('layout')

@section('content')

<section class="section-padding container mt-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card card-light p-5 text-center">

                <i class="fas fa-link-slash fa-3x text-danger mb-3"></i>

                <h2 class="gold-text">
                    Onboarding Link Unavailable
                </h2>

                <p class="text-muted">
                    This onboarding link has expired or has already
                    been used.
                </p>

                <p>
                    Please contact Abbyero Aviation LLC if you need
                    a new onboarding link.
                </p>

                <a
                    href="{{ url('/') }}"
                    class="btn btn-primary"
                >
                    Return to Website
                </a>

            </div>

        </div>

    </div>

</section>

@endsection