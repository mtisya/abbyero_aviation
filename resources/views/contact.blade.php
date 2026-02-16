@extends('layout')

@section('content')
    <!-- Contact Info Section -->
    <section class="ttm-row padding_zero-section mt-5 mb-5 clearfix">
        <div class="container">
            <div class="row g-4">
                <!-- Address -->
                <div class="col-lg-4 col-md-6">
                    <div class="featured-icon-box text-center p-4 shadow-sm bg-white h-100 rounded">
                        <div class="featured-icon mb-3">
                            <i class="flaticon flaticon-location-1 fs-1 text-primary"></i>
                        </div>
                        <h4 class="blue-text">Physical Address</h4>
                        <p class="text-muted">
                            12028 Mesquite River Dr, <br>
                            El Paso, Texas, United States 79934
                        </p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-lg-4 col-md-6">
                    <div class="featured-icon-box text-center p-4 shadow-sm bg-white h-100 rounded">
                        <div class="featured-icon mb-3">
                            <i class="flaticon flaticon-call-1 fs-1 text-primary"></i>
                        </div>
                        <h4 class="blue-text">Call Us</h4>
                        <p class="text-muted mb-0">+1.3163026304</p>
                        <p class="text-muted mb-0">+1 (915) 9995352</p>
                        <p class="text-muted">+254 714795773</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-lg-4 col-md-12">
                    <div class="featured-icon-box text-center p-4 shadow-sm bg-white h-100 rounded">
                        <div class="featured-icon mb-3">
                            <i class="flaticon flaticon-envelope fs-1 text-primary"></i>
                        </div>
                        <h4 class="blue-text">Email Us</h4>
                        <p class="text-muted">info@abbyeroaviation.com</p>
                        <p class="text-muted">sumbugu@gmail.com</p>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <div class="container py-5">
        <h1 class="text-center blue-text mb-4">Contact Us</h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="formResult"></div>

                <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-10">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Google Map -->
    <div class="google_map mt-5">
        <div class="map_container clearfix">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3385.1043675849915!2d-106.3748452!3d31.9580652!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86e7525f44f7e511%3A0xc5d1eb9d742ce10e!2s12028%20Mesquite%20River%20Dr%2C%20El%20Paso%2C%20TX%2079934%2C%20USA!5e0!3m2!1sen!2ske!4v1756519708028!5m2!1sen!2ske"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("contactForm");
            const result = document.getElementById("formResult");

            form.addEventListener("submit", function (e) {
                e.preventDefault(); // stop normal submission

                result.innerHTML = `<div class="alert alert-info">Sending message...</div>`;

                fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            result.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            form.reset();
                        } else {
                            result.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                        }
                    })
                    .catch(() => {
                        result.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again later.</div>`;
                    });
            });
        });
    </script>
@endpush