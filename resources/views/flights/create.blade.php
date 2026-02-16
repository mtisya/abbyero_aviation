@extends('layout') {{-- or whatever your layout file is named --}}

@section('content')
<div class="container mt-5 mb-5">
    <h2>Add New Flight</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('flights.store') }}" method="POST">
    @csrf

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Aircraft Model</label>
            <input type="text" name="aircraft_model" class="form-control" value="{{ old('aircraft_model') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Registration Number</label>
            <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Departure Location</label>
            <input type="text" name="departure_location" class="form-control" value="{{ old('departure_location') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Departure Time</label>
            <input type="datetime-local" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Arrival Location</label>
            <input type="text" name="arrival_location" class="form-control" value="{{ old('arrival_location') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Arrival Time</label>
            <input type="datetime-local" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Price (USD)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="">Select status</option>
                <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
            </select>
        </div>
    </div>

    {{-- 🔹 New Instructor Dropdown --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">Assign Instructor</label>
            <select name="instructor_id" class="form-select">
                <option value="">-- Select Instructor --</option>
                @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                        {{ $instructor->name }} ({{ $instructor->specialization ?? 'General' }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-success me-2">Save Flight</button>
        <a href="{{ route('flights.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

</div>
@endsection
