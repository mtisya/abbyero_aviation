@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2>Edit Flight</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('flights.update', $flight->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Aircraft Model</label>
                <input type="text" name="aircraft_model" class="form-control" value="{{ old('aircraft_model', $flight->aircraft_model) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Registration Number</label>
                <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $flight->registration_number) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Departure Location</label>
                <input type="text" name="departure_location" class="form-control" value="{{ old('departure_location', $flight->departure_location) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Departure Time</label>
                <input type="datetime-local" name="departure_time" class="form-control"
                    value="{{ old('departure_time', \Carbon\Carbon::parse($flight->departure_time)->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Arrival Location</label>
                <input type="text" name="arrival_location" class="form-control" value="{{ old('arrival_location', $flight->arrival_location) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Arrival Time</label>
                <input type="datetime-local" name="arrival_time" class="form-control"
                    value="{{ old('arrival_time', \Carbon\Carbon::parse($flight->arrival_time)->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Price (USD)</label>
                <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $flight->price) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Select status</option>
                    @foreach(['Scheduled', 'Cancelled', 'Completed', 'Available'] as $status)
                        <option value="{{ $status }}" {{ old('status', $flight->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-success me-2">Update Flight</button>
            <a href="{{ route('flights.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
