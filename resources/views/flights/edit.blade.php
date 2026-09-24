@extends('layout')

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm border-0">

            <div class="card-header mb-0 fw-bold" style="background: linear-gradient(#4886a3);">
                ✈ Edit Aircraft
            </div>

            <div class="card-body">

                {{-- VALIDATION ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('flights.update', $flight->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- AIRCRAFT INFO --}}
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Aircraft Model</label>
                            <input type="text" name="aircraft_model" class="form-control"
                                value="{{ old('aircraft_model', $flight->aircraft_model) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control"
                                value="{{ old('registration_number', $flight->registration_number) }}" required>
                        </div>

                        {{-- TACH SYSTEM --}}
                        <div class="col-md-4">
                            <label class="form-label">Current Tach</label>
                            <input type="number" step="0.01" name="current_tach" class="form-control"
                                value="{{ old('current_tach', $flight->current_tach) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Current Hobbs</label>
                            <input type="number" step="0.01" name="current_hobbs" class="form-control"
                                value="{{ old('current_hobbs', $flight->current_hobbs) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Hourly Rate ($)</label>
                            <input type="number" step="0.01" name="hourly_rate" class="form-control"
                                value="{{ old('hourly_rate', $flight->hourly_rate) }}">
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-12">
                            <label class="form-label">Aircraft Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['available', 'maintenance', 'grounded'] as $status)
                                    <option value="{{ $status }}" {{ old('status', $flight->status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('flights.index') }}"
                            class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back
                        </a>

                        <button type="submit" class="btn btn-success">
                            Update Aircraft
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection