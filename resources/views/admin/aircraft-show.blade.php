@extends('layout')

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- HEADER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h3 class="mb-0">
                    {{ $aircraft->registration_number }}
                </h3>
                <small class="text-muted">
                    {{ $aircraft->aircraft_model }}
                </small>
            </div>

            <a href="{{ route('aircraftmaintenance.dashboard') }}" class="btn btn-outline-primary w-30 w-md-auto">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

        {{-- AIRCRAFT SUMMARY --}}
        <div class="row mb-4">

            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Current Tach</small>
                        <h4>{{ $aircraft->current_tach ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Total Schedules</small>
                        <h4>{{ $aircraft->maintenanceSchedules->count() }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Status</small>
                        <h4 class="text-success">Active</h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- SCHEDULES --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">
                <h5 class="mb-0">Maintenance Schedules</h5>
            </div>

            <div class="card-body p-0">

                @forelse($aircraft->maintenanceSchedules as $schedule)

                    @php
                        $status = strtolower($schedule->status ?? 'pending');
                    @endphp

                    <div class="border-bottom p-3 d-flex flex-column flex-lg-row justify-content-between gap-3">

                        {{-- LEFT --}}
                        <div>

                            <div class="d-flex flex-wrap align-items-center gap-2">

                                <strong>
                                    {{ $schedule->maintenanceType->name ?? 'N/A' }}
                                </strong>

                                {{-- STATUS BADGE --}}
                                @if($status === 'completed')
                                    <span class="badge bg-success px-2 py-2">Completed</span>
                                @elseif($status === 'overdue')
                                    <span class="badge bg-danger px-2 py-2">Overdue</span>
                                @elseif($status === 'due_soon')
                                    <span class="badge bg-warning text-dark px-2 py-2">Due Soon</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-2">Pending</span>
                                @endif

                            </div>

                            <small class="text-muted d-block">
                                Last Done:
                                {{ $schedule->last_done_date ?? '—' }}
                                • Tach:
                                {{ $schedule->last_done_tach ?? '—' }}
                            </small>

                            <small class="text-muted">
                                Next Due Tach:
                                {{ $schedule->next_due_tach ?? '-' }}
                                • Next Due Date:
                                {{ $schedule->next_due_date ?? '-' }}
                            </small>

                        </div>

                        {{-- RIGHT ACTIONS --}}
                        <div class="d-flex flex-column flex-sm-row gap-2 w-40 w-lg-auto">

                            @if($status === 'completed')

                                <button type="button"
                                        <button class="btn btn-success btn-sm w-40"
                                        disabled
                                        style="cursor: not-allowed; opacity: 1;">

                                    ✔ Performed
                                </button>

                            @else

                                <button class="btn btn-success btn-sm" onclick="loadMaintenance({{ $schedule->id }})"
                                    data-bs-toggle="modal" data-bs-target="#performMaintenanceModal">

                                    Perform Maintenance
                                </button>

                            @endif

                            <a href="{{ route('aircraft.history', $aircraft) }}" class="btn btn-outline-secondary btn-sm w-50">

                                History
                            </a>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-5">
                        No maintenance schedules found
                    </div>

                @endforelse

            </div>

        </div>

    </div>
    @include('admin.modals.perform-modal')
@endsection