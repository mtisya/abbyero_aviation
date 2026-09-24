@extends('layout')

@section('content')

            <div class="container-fluid py-4">

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <script>
                    setTimeout(() => {
                        document.getElementById('success-alert')?.remove();
                        document.getElementById('error-alert')?.remove();
                    }, 5000);
                </script>

                <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                    <div class="mt-5">
                        <h3 class="mb-1">
                            ✈ Dispatch Operations Center
                        </h3>

                        <small class="text-muted bg-info px-2 py-1 rounded">
                            Aircraft Dispatch Monitoring & Tracking
                        </small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        {{-- Scheduled --}}
                        <div class="btn btn-outline-primary btn-sm px-3 py-2 shadow-sm disabled">
                            <i class="bi bi-calendar-event me-1"></i>
                            Scheduled
                            <span class="badge bg-primary ms-2">
                                {{ $stats['scheduled'] }}
                            </span>
                        </div>

                        {{-- Dispatched --}}
                        <div class="btn btn-outline-warning btn-sm px-3 py-2 shadow-sm disabled">
                            <i class="bi bi-send-check me-1"></i>
                            Dispatched
                            <span class="badge bg-warning ms-2">
                                {{ $stats['dispatched'] }}
                            </span>
                        </div>

                        {{-- Completed --}}
                        <div class="btn btn-outline-success btn-sm px-3 py-2 shadow-sm disabled">
                            <i class="bi bi-check-circle me-1"></i>
                            Completed
                            <span class="badge bg-success ms-2">
                                {{ $stats['completed'] }}
                            </span>
                        </div>

                        {{-- Cancelled --}}
                        <div class="btn btn-outline-danger btn-sm px-3 py-2 shadow-sm disabled">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancelled
                            <span class="badge bg-danger ms-2">
                                {{ $stats['cancelled'] }}
                            </span>
                        </div>

                        {{-- Scheduler --}}
                        <a href="{{ route('calendar') }}"
                        class="btn btn-primary btn-sm px-3 py-2 shadow-sm">

                            <i class="bi bi-calendar2-week me-1"></i>
                            Schedules
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-primary w-30 w-md-auto">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>

                    </div>

                </div>

        </div>

        {{-- Dispatch Table --}}
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>SN No.</th>
                                <th>Dispatch No</th>
                                <th>Aircraft</th>
                                <th>Pilot</th>
                                <th>Hobbs Out</th>
                                <th>Tach Out</th>
                                <th>Status</th>
                                <th>Dispatch Time</th>
                                <th class="text-center">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($dispatches as $d)

                                @php
                                    $badge = match ($d->status) {
                                        'Dispatched' => 'warning',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $d->dispatch_no }}</strong>
                                    </td>

                                    <td>
                                        {{ $d->aircraft?->registration_number ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $d->pilot?->name ?? 'Unknown Pilot' }}
                                    </td>

                                    <td>
                                        {{ number_format($d->hobbs_out, 1) }}
                                    </td>

                                    <td>
                                        {{ number_format($d->tach_out, 1) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $badge }}">
                                            {{ $d->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($d->dispatch_time)->format('d M Y H:i') }}
                                    </td>

                                    @php
                                        $isDispatched = $d->status === 'Dispatched';
                                        $isCompleted   = $d->status === 'Completed';
                                        $isCancelled   = $d->status === 'Cancelled';
                                    @endphp

                                    <td class="text-center">

                                        <div class="btn-group">

                                            {{-- VIEW (always active) --}}
                                            <div class="d-inline">

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#dispatchModal{{ $d->id }}"
                                                        title="View">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                            </div>

                                           {{-- COMPLETE --}}
                                            @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('dispatch.complete', $d->id) }}"
                                                    method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-success"
                                                            title="Complete"
                                                            {{ !$isDispatched ? 'disabled' : '' }}>
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- CANCEL --}}
                                            <form action="{{ route('dispatch.cancel', $d->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-warning cancel-btn"
                                                        title="Cancel"
                                                        {{ !$isDispatched ? 'disabled' : '' }}>
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>

                                            {{-- DELETE (only allowed when NOT active) --}}
                                            <form method="POST"
                                                action="{{ route('dispatch.destroy', $d->id) }}"
                                                class="delete-dispatch-form d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        title="Delete"
                                                        {{ $isDispatched ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </div>

                                    </td>

                                </tr>
                                <div class="modal fade" id="dispatchModal{{ $d->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">

                                        <div class="modal-content border-0 shadow-lg">

                                            {{-- HEADER --}}
                                            <div class="modal-header border-0 text-white py-3"
                                                style="background: linear-gradient(#4886a3);">

                                                <div class="d-flex align-items-center w-100 justify-content-between">

                                                    {{-- LEFT SIDE --}}
                                                    <div class="d-flex align-items-center">

                                                        <div class="me-3">
                                                            <i class="bi bi-airplane-engines-fill fs-2 aircraft-glow"></i>
                                                        </div>

                                                        <div>
                                                            <h5 class="mb-0 fw-bold">
                                                                Flight Dispatch Cockpit
                                                            </h5>

                                                            <small class="text-white-50">
                                                                Dispatch Operations Center
                                                            </small>
                                                        </div>

                                                    </div>

                                                    {{-- RIGHT SIDE --}}
                                                    <div class="text-end">

                                                        {{-- Dispatch Number --}}
                                                        <div class="mb-1 me-2">
                                                            <span class="badge bg-dark px-3 py-2">
                                                                {{ $d->dispatch_no }}
                                                            </span>
                                                        </div>

                                                        {{-- Live Status --}}
                                                        @php
                                                            $statusColor = match($d->status){
                                                                'Dispatched' => 'warning',
                                                                'Completed'  => 'success',
                                                                'Cancelled'  => 'danger',
                                                                default      => 'secondary'
                                                            };

                                                            $statusIcon = match($d->status){
                                                                'Dispatched' => 'bi-send-check-fill',
                                                                'Completed'  => 'bi-check-circle-fill',
                                                                'Cancelled'  => 'bi-x-circle-fill',
                                                                default      => 'bi-circle-fill'
                                                            };
                                                        @endphp

                                                        <!-- <span class="badge bg-{{ $statusColor }} px-3 py-2">
                                                            <i class="bi {{ $statusIcon }} me-1"></i>
                                                            {{ strtoupper($d->status) }}
                                                        </span> -->

                                                    </div>

                                                </div>

                                                <button type="button"
                                                        class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                                                        data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            {{-- BODY --}}
                                            <div class="modal-body bg-light">

                                                <div class="row g-3">

                                                    {{-- AIRCRAFT CARD --}}
                                                    <div class="col-md-4">
                                                        <div class="card shadow-sm border-0 h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-primary">Aircraft</h6>

                                                                <p class="mb-1">
                                                                    <strong>{{ $d->aircraft?->registration_number }}</strong>
                                                                </p>

                                                                <span class="badge bg-secondary">
                                                                    ID: {{ $d->aircraft_id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- PILOT CARD --}}
                                                    <div class="col-md-4">
                                                        <div class="card shadow-sm border-0 h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-success">Pilot</h6>

                                                                <p class="mb-1">
                                                                    {{ $d->pilot?->name ?? 'Unassigned' }}
                                                                </p>

                                                                <span class="badge bg-dark">
                                                                    ID: {{ $d->pilot_id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- STATUS CARD --}}
                                                    <div class="col-md-4">
                                                        <div class="card shadow-sm border-0 h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-warning">Status</h6>

                                                                <span class="badge bg-{{ $badge }}">
                                                                    {{ $d->status }}
                                                                </span>

                                                                <div class="mt-2">
                                                                    <small class="text-muted">
                                                                        Dispatch Time:
                                                                    </small><br>
                                                                    {{ $d->dispatch_time }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- FLIGHT METRICS --}}
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body">
                                                                <h6>Hobbs Reading</h6>

                                                                <div class="display-6 text-info">
                                                                    {{ number_format($d->hobbs_out,1) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body">
                                                                <h6>Tach Reading</h6>

                                                                <div class="display-6 text-success">
                                                                    {{ number_format($d->tach_out,1) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- REMARKS --}}
                                                    <div class="col-12">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body">
                                                                <h6>Remarks</h6>
                                                                <p class="mb-0">
                                                                    {{ $d->remarks ?? 'No remarks recorded' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- FOOTER ACTIONS --}}
                                            <div class="modal-footer bg-white">

                                                {{-- COMPLETE --}}
                                                @if($d->status === 'Dispatched')
                                                    <form action="{{ route('dispatch.complete',$d->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button class="btn btn-success">
                                                            <i class="bi bi-check-circle"></i>
                                                            Complete Flight
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($d->status === 'Dispatched')

                                                    <form id="cancel-form-{{ $d->id }}"
                                                            action="{{ route('dispatch.cancel', $d->id) }}"
                                                            method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="button"
                                                                class="btn btn-warning cancel-btn"
                                                                data-form="cancel-form-{{ $d->id }}">
                                                            <i class="bi bi-x-circle"></i>
                                                            Cancel Dispatch
                                                        </button>
                                                    </form>


                                                @endif

                                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center py-5">

                                        <i class="bi bi-airplane fs-1 d-block mb-2"></i>

                                        No dispatch records found

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                    

                </div>
                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">

                    {{-- Previous --}}
                    @if ($dispatches->onFirstPage())
                        <button class="btn btn-sm btn-light" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $dispatches->previousPageUrl() }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Info --}}
                    <span class="small text-muted">
                        Page {{ $dispatches->currentPage() }} of {{ $dispatches->lastPage() }}
                    </span>

                    {{-- Next --}}
                    @if ($dispatches->hasMorePages())
                        <a href="{{ $dispatches->nextPageUrl() }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <button class="btn btn-sm btn-light" disabled>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    @endif

                </div>
            </div>

        </div>

    </div>

@endsection
<style>
    .btn[disabled] {
        opacity: 0.35;
        pointer-events: none;
        filter: grayscale(30%);
    }
    .btn-group .btn {
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .aircraft-glow{
    color:#fff;
    text-shadow:
        0 0 5px rgba(255,255,255,.7),
        0 0 10px rgba(255,255,255,.7),
        0 0 20px rgba(255,255,255,.5);
    animation: pulseAircraft 2s infinite;
}

@keyframes pulseAircraft{
    0%{
        transform:scale(1);
        opacity:1;
    }
    50%{
        transform:scale(1.08);
        opacity:.85;
    }
    100%{
        transform:scale(1);
        opacity:1;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================= CANCEL DISPATCH ================= */
    document.addEventListener('click', function (e) {

        const cancelBtn = e.target.closest('.cancel-btn');
        if (cancelBtn) {

            e.preventDefault();

            const formId = cancelBtn.getAttribute('data-form');
            const form = document.getElementById(formId);

            if (!form) return console.error('Cancel form not found:', formId);

            Swal.fire({
                title: 'Cancel Dispatch?',
                text: "This will mark the flight as cancelled",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        /* ================= DELETE DISPATCH ================= */
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {

            e.preventDefault();

            const form = deleteBtn.closest('form');

            Swal.fire({
                title: 'Delete Dispatch?',
                text: "This action cannot be undone!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

    });

});

</script>