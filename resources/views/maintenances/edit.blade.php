@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2>Edit Maintenance Record</h2>

    <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Row 1 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Aircraft Model</label>
                <input type="text" name="aircraft_model" class="form-control"
                       value="{{ old('aircraft_model', $maintenance->aircraft_model) }}" required>
            </div>
            <div class="col-md-6">
                <label>Registration Number</label>
                <input type="text" name="registration_number" class="form-control"
                       value="{{ old('registration_number', $maintenance->registration_number) }}" required>
            </div>
        </div>

        {{-- Row 2 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Manufacturer</label>
                <input type="text" name="manufacturer" class="form-control"
                       value="{{ old('manufacturer', $maintenance->manufacturer) }}">
            </div>
            <div class="col-md-6">
                <label>Serial Number</label>
                <input type="text" name="serial_number" class="form-control"
                       value="{{ old('serial_number', $maintenance->serial_number) }}">
            </div>
        </div>

        {{-- Row 3 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Engine Type</label>
                <input type="text" name="engine_type" class="form-control"
                       value="{{ old('engine_type', $maintenance->engine_type) }}">
            </div>
            <div class="col-md-6">
                <label>Last Maintenance Hours</label>
                <input type="number" step="0.1" name="last_maintenance_hours" class="form-control"
                       value="{{ old('last_maintenance_hours', $maintenance->last_maintenance_hours) }}">
            </div>
        </div>

        {{-- Row 4 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Maintenance Date</label>
                <input type="date" name="maintenance_date" class="form-control"
                       value="{{ old('maintenance_date', $maintenance->maintenance_date) }}" required>
            </div>
            <div class="col-md-6">
                <label>Next Due Date</label>
                <input type="date" name="next_due_date" class="form-control"
                       value="{{ old('next_due_date', $maintenance->next_due_date) }}">
            </div>
        </div>

        {{-- Row 5 --}}
        <div class="mb-3">
            <label>Issue Description</label>
            <textarea name="issue_description" class="form-control" rows="3" required>{{ old('issue_description', $maintenance->issue_description) }}</textarea>
        </div>

        {{-- Row 6 --}}
        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $maintenance->remarks) }}</textarea>
        </div>

        {{-- Row 7 - Status --}}
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
                <option value="Pending" {{ old('status', $maintenance->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Progress" {{ old('status', $maintenance->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
