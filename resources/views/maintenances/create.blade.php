@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2>Request Maintenance Service</h2>

    <form action="{{ route('maintenances.store') }}" method="POST">
        @csrf

        {{-- Row 1 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Aircraft Model</label>
                <input type="text" name="aircraft_model" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Registration Number</label>
                <input type="text" name="registration_number" class="form-control" required>
            </div>
        </div>

        {{-- Row 2 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Manufacturer</label>
                <input type="text" name="manufacturer" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Serial Number</label>
                <input type="text" name="serial_number" class="form-control">
            </div>
        </div>

        {{-- Row 3 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Engine Type</label>
                <input type="text" name="engine_type" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Last Maintenance Hours</label>
                <input type="number" step="0.1" name="last_maintenance_hours" class="form-control">
            </div>
        </div>

        {{-- Row 4 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Maintenance Date</label>
                <input type="date" name="maintenance_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Next Due Date</label>
                <input type="date" name="next_due_date" class="form-control">
            </div>
        </div>

        {{-- Row 5 (full width for description) --}}
        <div class="mb-3">
            <label>Issue Description</label>
            <textarea name="issue_description" class="form-control" rows="3" required></textarea>
        </div>

        {{-- Row 6 (full width for remarks) --}}
        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control" rows="2"></textarea>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
