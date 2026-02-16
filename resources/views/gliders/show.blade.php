@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4">Glider Details</h1>

    <div class="card shadow-sm border-0">
        <div class="row g-0">
            {{-- Image Column --}}
            <div class="col-md-5">
                @if($glider->image)
                    <img src="{{ asset('storage/' . $glider->image) }}" 
                         class="img-fluid rounded-start w-100 h-100 object-fit-cover" 
                         alt="{{ $glider->model }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light h-100 text-muted">
                        <span>No Image</span>
                    </div>
                @endif
            </div>

            {{-- Details Column --}}
            <div class="col-md-7">
                <div class="card-body">
                    <h3 class="card-title">{{ $glider->model }}</h3>
                    <p class="card-text">
                        <strong>Registration:</strong> {{ $glider->registration }}
                    </p>
                    <p class="card-text">
                        <strong>Capacity:</strong> {{ $glider->capacity }} seats
                    </p>
                    <p class="card-text">
                        <strong>Rental Price:</strong> ${{ number_format($glider->rental_price, 2) }} / hour
                    </p>
                    <p class="card-text">
                        <strong>Status:</strong> 
                        <span class="badge 
                            @if($glider->status == 'available') bg-success 
                            @elseif($glider->status == 'rented') bg-warning 
                            @else bg-danger @endif">
                            {{ ucfirst($glider->status) }}
                        </span>
                    </p>
                    <p class="card-text">
                        <strong>Description:</strong><br>
                        {{ $glider->description ?? 'No description provided.' }}
                    </p>

                    <p class="card-text">
                        <small class="text-muted">
                            Added on {{ $glider->created_at->format('M d, Y') }}
                        </small>
                    </p>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('gliders.index') }}" class="btn btn-secondary">⬅ Back</a>
                        <div>
                            <a href="{{ route('gliders.edit', $glider->id) }}" class="btn btn-primary">✏ Edit</a>

                            <form action="{{ route('gliders.destroy', $glider->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this glider?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">🗑 Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection
