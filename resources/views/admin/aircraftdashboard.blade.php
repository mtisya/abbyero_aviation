@extends('layout')

@section('content')
    <div class="container-fluid px-3 px-md-5 py-4">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h3 class="mb-0">Aircraft Maintenance Dashboard</h3>
                <small class="text-muted">
                    Manage maintenance types, schedules and completed maintenance.
                </small>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-lg-auto">

                <a href="{{ route('maintenance-types.index') }}" class="btn btn-primary flex-fill">
                    <i class="bi bi-tools me-1"></i>
                    Maintenance Types
                </a>

                <a href="{{ route('maintenance-schedules.index') }}" class="btn btn-success flex-fill">
                    <i class="bi bi-calendar-check me-1"></i>
                    Maintenance Schedules
                </a>

                <a href="{{ route('maintenance-history.index') }}" class="btn btn-secondary flex-fill">
                    <i class="bi bi-clock-history me-1"></i>
                    Maintenance History
                </a>

                <a href="{{ route('flights.available') }}" class="btn btn-warning flex-fill">
                    <i class="bi bi-airplane me-1"></i>
                    Aircraft Summary
                </a>

                <a href="javascript:history.back()" class="btn btn-outline-primary flex-fill">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>

            </div>
        </div>

        <div class="row mb-4">

            <div class="col-12 col-sm-6 col-xl-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6>Total Aircraft</h6>
                        <h2>{{ $aircraft->count() }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6>Total Maintenance Schedules</h6>
                        <h2 class="text-success">
                            {{ $aircraft->sum(fn($plane) => $plane->maintenanceSchedules->count()) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6>Due Soon</h6>
                        <h2 class="text-warning">
                            {{ $dueSoon }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6>Overdue</h6>
                        <h2 class="text-danger">
                            {{ $overdue }}
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            @forelse($aircraft as $plane)

                <div class="col-lg-4 mb-4">

                    <div class="card shadow-sm h-100">

                        <div class="card-header bg-white">

                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">

                                <div>

                                    <h5 class="mb-0 text-break text-secondary fw-semibold">
                                        {{ $plane->registration_number }}
                                    </h5>

                                    <small class="text-muted text-break">
                                        {{ $plane->aircraft_model }}
                                    </small>

                                </div>

                               <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        onclick="openAircraftSummary({{ $plane->id }})">
                                    <i class="bi bi-eye"></i>
                                    View Summary
                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="bi bi-tools me-1 text-primary"></i>
                                    Maintenance Schedules
                                </h6>

                                <span class="badge bg-primary rounded-pill">
                                    {{ $plane->maintenanceSchedules->where('status', '!=', 'completed')->count() }}
                                </span>
                            </div>

                            @php
                                $schedules = $plane->maintenanceSchedules->where('status', '!=', 'completed');
                            @endphp

                            <div
                                @if($schedules->count() > 2)
                                    style="max-height:320px; overflow-y:auto;"
                                @endif
                            >

                                @forelse($schedules as $schedule)

                                    <div class="card border-0 shadow-sm mb-3">

                                        <div class="card-body py-3">

                                            <div class="d-flex justify-content-between align-items-start">

                                                <div>

                                                    <h6 class="mb-1 fw-semibold">

                                                        @if($schedule->maintenanceType)

                                                            <i class="bi bi-wrench-adjustable-circle text-primary me-1"></i>

                                                            {{ $schedule->maintenanceType->name }}

                                                        @else

                                                            <span class="badge bg-secondary">
                                                                Not Assigned
                                                            </span>

                                                        @endif

                                                    </h6>

                                                    <div class="small text-muted">

                                                        <div class="mb-1">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            <strong>Due Date:</strong>
                                                            {{ $schedule->next_due_date?->format('d M Y') ?? 'Based on Tach Hours' }}
                                                        </div>

                                                        <div>
                                                            <i class="bi bi-speedometer2 me-1"></i>
                                                            <strong>Due Tach:</strong>
                                                            {{ $schedule->next_due_tach ?? '--' }} hrs
                                                        </div>

                                                    </div>

                                                </div>

                                                <span class="badge
                                                    @if($schedule->status == 'overdue') bg-danger
                                                    @elseif($schedule->status == 'due_soon') bg-warning text-dark
                                                    @else bg-success
                                                    @endif">
                                                    {{ ucwords(str_replace('_', ' ', $schedule->status)) }}
                                                </span>

                                            </div>

                                            <hr class="my-3">

                                            <div class="d-grid">

                                                <button
                                                    class="btn btn-success btn-sm"
                                                    onclick="loadMaintenance({{ $schedule->id }})"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#performMaintenanceModal">

                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Perform Maintenance

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="text-center py-4">

                                        <i class="bi bi-tools display-6 text-muted"></i>

                                        <p class="text-muted mt-2 mb-0">
                                            No maintenance schedules available.
                                        </p>

                                    </div>

                                @endforelse

                            </div>

                        </div>

                        <div class="card-footer bg-white">

                            <div class="d-grid">

                                <a href="{{ route('aircraft.history', $plane) }}" class="btn btn-outline-secondary btn-sm">
                                    View History
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-info">

                        No aircraft found.

                    </div>

                </div>

            @endforelse

        </div>

    </div>
    @include('admin.modals.perform-modal')
    
    @include('admin.modals.view-aircraft-modal')
    
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const summaryButtons = document.querySelectorAll('.aircraft-summary-btn');

        summaryButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const aircraftId = this.getAttribute('data-aircraft-id');
                window.location.href = `/aircraft/${aircraftId}/summary`;
            });
        });
    }); 
</script>