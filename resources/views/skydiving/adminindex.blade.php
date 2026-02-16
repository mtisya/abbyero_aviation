@extends('layout')

@section('content')
    {{-- Billboard Section --}}


    {{-- Skydiving Events Section --}}
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">SkyDiving Events</h2>
            <a href="{{ route('skydiving.create') }}" class="btn btn-primary">
                + Add Event
            </a>
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
        <div class="row">
            @forelse($skydiving as $event)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}"
                                 class="card-img-top"
                                 alt="{{ $event->title }}"
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

                            <div class="mt-auto">
                                <a href="{{ route('skydiving.show', $event) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('skydiving.edit', $event) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('skydiving.destroy', $event) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
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
    </div>
@endsection
