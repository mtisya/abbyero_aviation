@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Available Flights</h2>

    <a href="{{ route('flights.create') }}" class="btn btn-primary mb-3">Add New Flight</a>


    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Aircraft</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($flights as $flight)
            <tr>
                <td>{{ $flight->aircraft_model }} ({{ $flight->registration_number }})</td>
                <td>
                    {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y H:i') }} <br>
                    <small>{{ $flight->departure_location }}</small>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y H:i') }} <br>
                    <small>{{ $flight->arrival_location }}</small>
                </td>
                <td>${{ number_format($flight->price, 2) }}</td>
                <td>{{ ucfirst($flight->status) }}</td>
                <td>
                    <a href="{{ route('flights.edit', $flight->id) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('flights.destroy', $flight->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this flight?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No flights available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
