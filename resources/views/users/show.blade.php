<div class="container-fluid py-4">

    {{-- Back --}}
    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Back to Users
        </a>
    </div>


    {{-- USER HEADER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex align-items-center gap-3">

                <div class="user-profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h4 class="mb-1 fw-bold">
                        {{ $user->name }}
                    </h4>

                    <div class="text-muted">
                        <i class="fas fa-envelope me-1"></i>
                        {{ $user->email }}
                    </div>

                    <div class="mt-2">

                        <span class="badge bg-light text-dark border">
                            {{ ucfirst($user->role) }}
                        </span>

                        @if ($user->status === 'approved')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Approved
                            </span>
                        @elseif ($user->status === 'inactive')
                            <span class="badge bg-secondary">
                                Inactive
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($user->status) }}
                            </span>
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>
        {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Schedules
                    </div>

                    <div class="fs-3 fw-bold text-primary">
                        {{ $user->flightSchedules->count() }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Dispatches
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $user->dispatches->count() }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Logbooks
                    </div>

                    <div class="fs-3 fw-bold text-info">
                        {{ $user->logbooks->count() }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Approved Logbooks
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $user->logbooks->where('approved', true)->count() }}
                    </div>

                </div>
            </div>
        </div>

    </div>
        {{-- SCHEDULES --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                Flight Schedule
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Date</th>
                            <th>Flight</th>
                            <th>Instructor</th>
                            <th>Start</th>
                            <th>End</th>
                            <th class="text-center">Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($user->flightSchedules as $schedule)

                            <tr>

                                <td>
                                    {{ $schedule->start_time?->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $schedule->flight?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $schedule->instructor?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $schedule->start_time?->format('H:i') }}
                                </td>

                                <td>
                                    {{ $schedule->end_time?->format('H:i') }}
                                </td>

                                <td class="text-center">

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($schedule->status) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-4">
                                    No flight schedules found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
        {{-- DISPATCHES --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">
                <i class="fas fa-paper-plane me-2 text-warning"></i>
                Dispatches
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Dispatch No.</th>
                            <th>Date</th>
                            <th>Aircraft</th>
                            <th>Pilot</th>
                            <th>Hobbs Out</th>
                            <th>Tach Out</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($user->dispatches as $dispatch)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $dispatch->dispatch_no ?? '—' }}
                                </td>

                                <td>
                                    {{ $dispatch->dispatch_time?->format('d M Y H:i') ?? '—' }}
                                </td>

                                <td>
                                    {{ $dispatch->aircraft?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $dispatch->pilot?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $dispatch->hobbs_out ?? '—' }}
                                </td>

                                <td>
                                    {{ $dispatch->tach_out ?? '—' }}
                                </td>

                                <td>

                                    @php
                                        $dispatchClass = match ($dispatch->status) {
                                            'dispatched' => 'bg-warning text-dark',
                                            'in_flight' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $dispatchClass }}">
                                        {{ ucwords(str_replace('_', ' ', $dispatch->status)) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No dispatches found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
        {{-- LOGBOOKS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">
                <i class="fas fa-book me-2 text-info"></i>
                Logbooks
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Date</th>
                            <th>Aircraft</th>
                            <th>Route</th>
                            <th>Type</th>
                            <th>Hours</th>
                            <th>Tach</th>
                            <th>Approval</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($user->logbooks as $logbook)

                            <tr>

                                <td>
                                    {{ $logbook->flight_date
                                        ? \Carbon\Carbon::parse($logbook->flight_date)->format('d M Y')
                                        : '—' }}
                                </td>

                                <td>
                                    {{ $logbook->aircraft ?? '—' }}
                                </td>

                                <td>
                                    {{ $logbook->route ?? '—' }}
                                </td>

                                <td>
                                    {{ ucfirst($logbook->type ?? '—') }}
                                </td>

                                <td>
                                    {{ number_format((float) $logbook->hours, 2) }}
                                </td>

                                <td>
                                    {{ $logbook->tach_hours ?? '—' }}
                                </td>

                                <td>

                                    @if ($logbook->approved)

                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Approved
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>
                                            Pending
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No logbooks found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>