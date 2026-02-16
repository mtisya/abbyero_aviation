@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Instructor Photo -->
        <div class="col-md-5 text-center">
            @if($instructor->photo)
                <img src="{{ asset('storage/' . $instructor->photo) }}" 
                     class="img-fluid rounded shadow mb-3"
                     style="max-height: 350px; object-fit: cover;" 
                     alt="{{ $instructor->name }}">
            @else
                <img src="https://via.placeholder.com/400x350?text=No+Photo" 
                     class="img-fluid rounded shadow mb-3" 
                     alt="No Photo">
            @endif
        </div>

        <!-- Instructor Details -->
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h2 class="mb-3 blue-text">{{ $instructor->name }}</h2>
                    <p><strong>Experience:</strong> {{ $instructor->experience }} years</p>
                    <p><strong>Specialization:</strong> {{ $instructor->specialization }}</p>
                    
                    <hr>

                    <h5 class="fw-bold">Instructor Bio</h5>
                    <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                        {{ $instructor->bio }}
                    </p>

                    <a href="{{ url('/instructors') }}" class="btn btn-outline-primary mt-3">
                        ← Back to Instructors
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
