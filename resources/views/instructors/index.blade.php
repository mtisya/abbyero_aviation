@extends('layout')

@section('content')

    <div class="container mt-5 mb-5" id="instructor_banner_container">
        <div class="row align-items-center mb-5">
            <!-- Left Column: General Info -->
            <div class="col-12 col-lg-6 text-center text-lg-start">
                <h2 class="mb-3 blue-text">Meet Our Expert Flight Instructors</h2>
                <p class="h5 mb-4">
                    At <strong>Abbyero Aviation</strong>, we pride ourselves on having some of the
                    most qualified and passionate instructors in the aviation industry.
                    With years of experience in flight training, aircraft handling,
                    and specialized aviation skills, our team is dedicated to
                    ensuring that every student pilot receives the highest quality
                    education and guidance.
                    <br><br>
                    Whether you’re just starting your journey or working toward advanced
                    certifications, our instructors are committed to helping you achieve
                    your aviation dreams.
                </p>
            </div>

            <!-- Right Column: Single General Image -->
            <div class="col-12 col-lg-6 text-center">
                <img src="{{ asset('assets/images/index/IMG-20250710-WA0149.jpg') }}" alt="Our Flight Instructors"
                    class="img-fluid rounded shadow animate-slide" style="max-height: 400px; object-fit: cover;">
            </div>


        </div>
    </div>


    <div class="container mt-5 mb-5">
        <h2 class="mb-3 text-center mt-5 mb-5 blue-text">Flight Instructors</h2>
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('instructors.create') }}" class="btn btn-primary mb-3">➕ Add Instructor</a>
            @endif
        @endauth

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    let alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.transition = "opacity 0.5s ease";
                        alertBox.style.opacity = "0";
                        setTimeout(() => alertBox.remove(), 500); // Remove from DOM after fade out
                    }
                }, 5000);
            </script>
        @endif

        <div class="row">
            @forelse($instructors as $instructor)
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm">
                        @if($instructor->photo)
                            <img src="{{ asset('storage/' . $instructor->photo) }}" class="card-img-top"
                                style="height:200px; object-fit:cover;" alt="{{ $instructor->name }}">
                        @else
                            <img src="https://via.placeholder.com/400x200?text=No+Photo" class="card-img-top" alt="No Photo">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $instructor->name }}</h5>
                            <p class="card-text">
                                <strong>License:</strong> {{ $instructor->license_number }} <br>
                                <strong>Specialization:</strong> {{ $instructor->specialization }} <br>
                                <strong>Email:</strong> {{ $instructor->email }}
                            </p>
                            <a href="{{ route('instructors.show', $instructor) }}" class="btn btn-info btn-sm">View</a>
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('instructors.edit', $instructor) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('instructors.destroy', $instructor) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this instructor?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <p>No instructors found. <a href="{{ route('instructors.create') }}">Add one</a>.</p>
            @endforelse
        </div>
    </div>
@endsection