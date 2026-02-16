@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    @if(session('error'))
    <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm" style="z-index: 1050; width: 300px;">
        {{ session('error') }}
    </div>
    @endif

    <script>
    setTimeout(function () {
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.transition = 'opacity 0.5s ease';
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 500);
        }
    }, 5000);
    </script>

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <h2 class="mb-4 text-center text-md-start">Aircraft Part Details</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $aircraftPart->name }} ({{ $aircraftPart->part_number }})</h4>

                    <p><strong>Category:</strong> {{ $aircraftPart->category ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $aircraftPart->description ?? 'N/A' }}</p>
                    <p><strong>Quantity:</strong> {{ $aircraftPart->quantity }}</p>
                    <p><strong>Price ($):</strong> {{ number_format($aircraftPart->price, 2) }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($aircraftPart->status == 'available') bg-success
                            @elseif($aircraftPart->status == 'reserved') bg-warning
                            @else bg-info @endif">
                            {{ ucfirst($aircraftPart->status) }}
                        </span>
                    </p>

                    @if($aircraftPart->image)
                        <div class="mt-3 text-center">
                            <img src="{{ asset('storage/'.$aircraftPart->image) }}" alt="Part Image" class="img-fluid rounded shadow-sm" style="max-width: 250px;">
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('aircraft_parts.list') }}" class="btn btn-secondary btn-sm">← Back</a>
                        @auth
                            <a href="{{ route('aircraft_parts.edit', $aircraftPart->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('aircraft_parts.destroy', $aircraftPart->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this part?')">Delete</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
