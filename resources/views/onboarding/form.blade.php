@extends('layout')

@section('content')

<section class="section-padding container pt-5 pb-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card card-light p-4 p-md-5">

                <div class="text-center mb-4">

                    <img
                        src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                        alt="Abbyero Aviation"
                        style="max-width: 220px;"
                        class="mb-3"
                    >

                    <h2 class="gold-text">
                        Abbyero Aviation Onboarding
                    </h2>

                    <p class="text-muted mb-0">
                        Welcome,
                        <strong>
                            {{ $application->name }}
                        </strong>
                    </p>

                </div>

                <hr>

                <div class="alert alert-info">

                    <strong>
                        Application Received
                    </strong>

                    <p class="mb-0 mt-2">
                        Your application has been received.
                        Please complete the onboarding information
                        below to continue your enrollment process.
                    </p>

                </div>

                <form
                    method="POST"
                    action="{{ route('onboarding.store', $token) }}"
                >

                    @csrf

                    {{-- Temporary placeholder --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            value="{{ $application->email }}"
                            readonly
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $application->phone }}"
                            readonly
                        >

                    </div>

                    <div class="d-flex justify-content-end">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Continue
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

@endsection