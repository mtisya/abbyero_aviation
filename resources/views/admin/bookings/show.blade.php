@extends('layout')

@section('content')
<div class="container mt-4 mb-4" style="max-width: 1000px;">

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Booking Details
        </div>

        <div class="card-body">

            <div class="row">
                {{-- LEFT COLUMN --}}
                <div class="col-md-6">

                    <h5 class="mb-3">📄 Booking Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Reference</th>
                            <td>{{ $booking->reference }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $booking->status === 'Cancelled' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Booked At</th>
                            <td>{{ \Carbon\Carbon::parse($booking->booked_at)->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4 mb-3">👤 User Details</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $booking->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $booking->user->email }}</td>
                        </tr>
                    </table>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-md-6">

                    <h5 class="mb-3">✈ Flight Details</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Flight No</th>
                            <td>{{ $booking->flight->flight_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Aircraft</th>
                            <td>
                                {{ $booking->flight->aircraft_model ?? '' }}
                                ({{ $booking->flight->registration_number ?? '' }})
                            </td>
                        </tr>
                        <tr>
                            <th>Departure</th>
                            <td>
                                {{ $booking->flight->departure_location }}<br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->flight->departure_time)->format('d M Y H:i') }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Arrival</th>
                            <td>
                                {{ $booking->flight->arrival_location }}<br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->flight->arrival_time)->format('d M Y H:i') }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>
                                ${{ number_format($booking->flight->price, 2) }}
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="mt-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    ← Back
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
