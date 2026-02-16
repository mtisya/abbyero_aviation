@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    {{-- Alerts --}}
    @if(session('success'))
        <div id="success-alert" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm" style="z-index: 1050; max-width: 90%; width: 300px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm" style="z-index: 1050; max-width: 90%; width: 300px;">
            {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(function () {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }

            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 5000);
    </script>

    <h2 class="mb-4 text-center text-md-start">Available Aircraft for Rent</h2>

    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('flights.create') }}" class="btn btn-primary mb-3">
                ➕ Add New Flight
            </a>

            <a href="{{ route('flights.schedules') }}" class="btn btn-success mb-3 ms-2">
                🗓️ Schedule Flight
            </a>
        @endif
    @endauth


    {{-- Responsive table wrapper --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Aircraft</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flights as $index => $flight)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $flight->aircraft_model }} ({{ $flight->registration_number }})</td>
                        <td>
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y H:i') }}<br>
                            <small class="text-muted">{{ $flight->departure_location }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y H:i') }}<br>
                            <small class="text-muted">{{ $flight->arrival_location }}</small>
                        </td>
                        <td>${{ number_format($flight->price, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ ucfirst($flight->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                @auth
                                    <a href="{{ route('flights.show', $flight->id) }}" class="btn btn-sm btn-primary">
                                        {{ Auth::user()->role === 'admin' ? 'View' : 'Rent' }}
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('flights.edit', $flight->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                        <form id="delete-form-{{ $flight->id }}" action="{{ route('flights.destroy', $flight->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $flight->id }})">
                                            Delete
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No available flights.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
