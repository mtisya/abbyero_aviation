@extends('layout')

@section('content')
    <div class="container mt-5 mb-5">
        <h1 class="mb-4">Gliders</h1>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('gliders.create') }}" class="btn btn-primary">➕ Add Glider</a>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>

        <div class="row">
            @forelse($gliders as $glider)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($glider->image)
                            <img src="{{ asset('storage/' . $glider->image) }}" class="card-img-top" alt="{{ $glider->model }}"
                                style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/400x200?text=Glider" class="card-img-top" alt="Placeholder">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $glider->model }}</h5>
                            <p class="card-text mb-1"><strong>Registration:</strong> {{ $glider->registration }}</p>
                            <p class="card-text mb-1"><strong>Capacity:</strong> {{ $glider->capacity }} seat(s)</p>
                            <p class="card-text mb-1"><strong>Rental Price:</strong>
                                ${{ number_format($glider->rental_price, 2) }}/hr</p>
                            <p class="card-text">
                                <span class="badge 
                                        @if($glider->status == 'available') bg-success 
                                        @elseif($glider->status == 'maintenance') bg-warning 
                                        @else bg-secondary @endif">
                                    {{ ucfirst($glider->status) }}
                                </span>
                            </p>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <a href="{{ route('gliders.show', $glider) }}"
                                class="btn btn-sm btn-outline-info flex-grow-1 mx-1">View</a>
                            <a href="{{ route('gliders.edit', $glider) }}"
                                class="btn btn-sm btn-outline-warning flex-grow-1 mx-1">Edit</a>
                            <form action="{{ route('gliders.destroy', $glider) }}" method="POST"
                                class="d-inline flex-grow-1 mx-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <p class="text-muted">No gliders found. Add one to get started.</p>
            @endforelse
        </div>
    </div>
@endsection