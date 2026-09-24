@extends('layout')

@section('content')
<div class="container mt-5 mb-5">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Flight Schedule
                    </h4>

                    {{-- Error Alert --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('flights.schedule.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Aircraft --}}
                        <div class="mb-3">
                            <label class="form-label">Aircraft</label>
                            <select name="flight_id" class="form-select" required>
                                @foreach($flights as $flight)
                                    <option value="{{ $flight->id }}"
                                        {{ $flight->id == $schedule->flight_id ? 'selected' : '' }}>
                                        {{ $flight->registration_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Time --}}
                        <div class="mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="datetime-local"
                                   name="start_time"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d\TH:i') }}"
                                   required>
                        </div>

                        {{-- End Time --}}
                        <div class="mb-3">
                            <label class="form-label">End Time</label>
                            <input type="datetime-local"
                                   name="end_time"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('Y-m-d\TH:i') }}"
                                   required>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="scheduled" {{ $schedule->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $schedule->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $schedule->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="maintenance" {{ $schedule->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('flight.schedules') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Schedule
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection