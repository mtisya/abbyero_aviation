@extends('layout')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h5>Booking Details</h5>
            <span class="badge {{ $booking->status === 'Cancelled' ? 'bg-danger' : 'bg-success' }}">
                {{ $booking->status }}
            </span>
        </div>

        <div class="card-body">
            <p><strong>Reference:</strong> {{ $booking->reference }}</p>
            <p><strong>Booked At:</strong> {{ $booking->booked_at }}</p>

            <hr>

            <h6>Flight Information</h6>
            <p><strong>Route:</strong> {{ $booking->flight->from }} → {{ $booking->flight->to }}</p>
            <p><strong>Departure:</strong> {{ $booking->flight->departure_time }}</p>

            <hr>

            <h6>Passenger</h6>
            <p><strong>Name:</strong> {{ $booking->user->name }}</p>
            <p><strong>Email:</strong> {{ $booking->user->email }}</p>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('flights.show', $booking->flight->id) }}"
               class="btn btn-sm btn-primary">
                <i class="bi bi-eye"></i> View Flight
            </a>

            <a href="{{ route('bookings.index') }}"
               class="btn btn-sm btn-secondary">
                Back
            </a>
        </div>
    </div>
</div>
@endsection
