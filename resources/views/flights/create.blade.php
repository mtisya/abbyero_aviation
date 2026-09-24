@extends('layout')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card shadow border-0 rounded-4">

                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="mb-0">
                            <i class="fas fa-plane me-2"></i>
                            Add New Aircraft
                        </h3>
                    </div>

                    <div class="card-body p-4">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('flights.store') }}" method="POST">

                            @csrf

                            <div class="row">

                                <!-- Aircraft Model -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Aircraft Model
                                    </label>
                                    <input type="text" name="aircraft_model" class="form-control"
                                        value="{{ old('aircraft_model') }}" placeholder="e.g. Piper Cherokee PA-28-140"
                                        required>
                                </div>

                                <!-- Registration -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Registration Number
                                    </label>
                                    <input type="text" name="registration_number" class="form-control"
                                        value="{{ old('registration_number') }}" placeholder="e.g. N6326W" required>
                                </div>

                                <!-- Hourly Rate -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">
                                        Hourly Rate ($)
                                    </label>
                                    <input type="number" step="0.01" name="hourly_rate" class="form-control"
                                        value="{{ old('hourly_rate', 0) }}" required>
                                </div>

                                <!-- Current Hobbs -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">
                                        Current Hobbs
                                    </label>
                                    <input type="number" step="0.1" name="current_hobbs" class="form-control"
                                        value="{{ old('current_hobbs', 0) }}">
                                </div>

                                <!-- Current Tach -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">
                                        Current Tach
                                    </label>
                                    <input type="number" step="0.1" name="current_tach" class="form-control"
                                        value="{{ old('current_tach', 0) }}">
                                </div>

                                <!-- Next 100hr -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Next 100-Hour Inspection Due
                                    </label>
                                    <input type="number" step="0.1" name="next_100hr_due" class="form-control"
                                        value="{{ old('next_100hr_due', 100) }}">
                                </div>

                                <!-- Maintenance Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Maintenance Status
                                    </label>

                                    <select name="maintenance_status" class="form-select" required>

                                        <option value="serviceable" {{ old('maintenance_status') == 'serviceable' ? 'selected' : '' }}>
                                            Serviceable
                                        </option>

                                        <option value="due_soon" {{ old('maintenance_status') == 'due_soon' ? 'selected' : '' }}>
                                            Due Soon
                                        </option>

                                        <option value="maintenance" {{ old('maintenance_status') == 'maintenance' ? 'selected' : '' }}>
                                            Maintenance
                                        </option>

                                        <option value="grounded" {{ old('maintenance_status') == 'grounded' ? 'selected' : '' }}>
                                            Grounded
                                        </option>

                                    </select>

                                </div>

                                <!-- Aircraft Status -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Aircraft Status
                                    </label>

                                    <select name="status" class="form-select" required>

                                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>
                                            Available
                                        </option>

                                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>
                                            Maintenance
                                        </option>

                                        <option value="Grounded" {{ old('status') == 'Grounded' ? 'selected' : '' }}>
                                            Grounded
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <hr>

                            <div class="d-flex justify-content-end">

                                <a href="{{ route('flights.available') }}" class="btn btn-outline-secondary me-2">
                                    Cancel
                                </a>

                                <button type="submit" class="btn btn-primary">

                                    <i class="fas fa-save me-1"></i>
                                    Save Aircraft

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection