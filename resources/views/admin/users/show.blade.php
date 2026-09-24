@extends('layout')

@section('content')

    <div class="container-fluid py-4 px-md-4 px-lg-5">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}
        <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center
                        gap-3 mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    User Profile
                </h4>

                <p class="text-muted small mb-0 text-secondary">
                    View user information, flight activity and records
                </p>
            </div>

            <a href="{{ route('users.list') }}" class="btn btn-outline-primary btn-sm">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Users

            </a>

        </div>


        {{-- =========================================================
        USER PROFILE HEADER
        ========================================================== --}}

        <div class="card border-0 shadow-sm mb-4 user-profile-card">

            <div class="card-body p-4">

                <div class="row align-items-center g-4">

                    {{-- AVATAR --}}
                    <div class="col-auto">

                        <div class="user-profile-avatar">

                            @if ($user->profile_image)

                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}">

                            @else

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            @endif

                        </div>

                    </div>


                    {{-- USER INFORMATION --}}
                    <div class="col">

                        <div class="d-flex flex-column flex-md-row
                                        justify-content-between
                                        align-items-md-center gap-3">

                            <div>

                                <h3 class="fw-bold mb-1">
                                    {{ $user->name }}
                                </h3>

                                <div class="text-muted mb-2">

                                    <i class="fas fa-envelope me-1"></i>

                                    {{ $user->email }}

                                </div>

                                <div class="d-flex flex-wrap gap-2">

                                    {{-- ROLE --}}
                                    <span class="badge bg-light text-dark border">

                                        <i class="fas fa-user-tag me-1"></i>

                                        {{ ucfirst($user->role) }}

                                    </span>


                                    {{-- STATUS --}}
                                    @if ($user->status === 'approved')

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Approved

                                        </span>

                                    @elseif ($user->status === 'inactive')

                                        <span class="badge bg-secondary">

                                            <i class="fas fa-user-slash me-1"></i>

                                            Inactive

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            <i class="fas fa-clock me-1"></i>

                                            {{ ucfirst($user->status) }}

                                        </span>

                                    @endif


                                    {{-- EMAIL --}}
                                    @if ($user->email_verified_at)

                                        <span class="badge bg-success-subtle
                                                            text-success border
                                                            border-success-subtle">

                                            <i class="fas fa-envelope-circle-check me-1"></i>

                                            Email Verified

                                        </span>

                                    @else

                                        <span class="badge bg-warning-subtle
                                                            text-warning-emphasis border
                                                            border-warning-subtle">

                                            <i class="fas fa-envelope me-1"></i>

                                            Email Pending

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- USER ID --}}
                            <div class="text-md-end">

                                <div class="small text-muted">
                                    User ID
                                </div>

                                <div class="fw-bold text-dark">
                                    #{{ $user->id }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- INVOICE SUMMARY --}}
                <div class="border-top mt-4 pt-4">

                    {{-- HEADER --}}
                    <div class="d-flex flex-column flex-lg-row
                    justify-content-between
                    align-items-lg-center
                    gap-3 mb-3">

                        <div>
                            <div class="d-flex align-items-center gap-2">

                                <span class="d-inline-flex align-items-center
                                 justify-content-center rounded-circle
                                 bg-primary-subtle text-primary" style="width: 34px; height: 34px;">

                                    <i class="fas fa-file-invoice-dollar"></i>

                                </span>

                                <h6 class="fw-bold mb-0">
                                    Invoice Summary
                                </h6>

                            </div>

                            <small class="text-muted d-block mt-1">
                                Billing overview for this user
                            </small>
                        </div>

                        <a href="#flight-approvedLogbooks" class="btn btn-sm btn-outline-primary align-self-start align-self-lg-center">

                            <i class="fas fa-file-invoice me-1"></i>
                            View Invoices

                        </a>

                    </div>


                    {{-- SUMMARY CARDS --}}
                    <div class="row g-3">

                        {{-- TOTAL INVOICES --}}
                        <div class="col-6 col-xl-3">

                            <div class="invoice-summary-card
                            bg-light
                            border
                            rounded-3
                            p-3
                            h-100">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <div class="small text-muted mb-1">
                                            Total Invoices
                                        </div>

                                        <div class="fs-5 fw-bold text-dark">
                                            {{ $user->invoices->count() }}
                                        </div>
                                    </div>

                                    <i class="fas fa-file-invoice
                                  text-secondary
                                  opacity-75"></i>

                                </div>

                            </div>

                        </div>


                        {{-- PAID --}}
                        <div class="col-6 col-xl-3">

                            <div class="invoice-summary-card
                            bg-success-subtle
                            border
                            border-success-subtle
                            rounded-3
                            p-3
                            h-100">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <div class="small text-success mb-1">
                                            Paid
                                        </div>

                                        <div class="fs-5 fw-bold text-success">
                                            {{ $user->invoices->where('status', 'paid')->count() }}
                                        </div>
                                    </div>

                                    <i class="fas fa-circle-check
                                  text-success
                                  opacity-75"></i>

                                </div>

                            </div>

                        </div>


                        {{-- PENDING --}}
                        <div class="col-6 col-xl-3">

                            <div class="invoice-summary-card
                            bg-warning-subtle
                            border
                            border-warning-subtle
                            rounded-3
                            p-3
                            h-100">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <div class="small text-warning-emphasis mb-1">
                                            Pending
                                        </div>

                                        <div class="fs-5 fw-bold text-warning-emphasis">
                                            {{ $user->invoices->where('status', 'pending')->count() }}
                                        </div>
                                    </div>

                                    <i class="fas fa-clock
                                  text-warning
                                  opacity-75"></i>

                                </div>

                            </div>

                        </div>


                        {{-- TOTAL INVOICED --}}
                        <div class="col-6 col-xl-3">

                            <div class="invoice-summary-card
                            bg-primary-subtle
                            border
                            border-primary-subtle
                            rounded-3
                            p-3
                            h-100">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <div class="small text-abbyero mb-1">
                                            Total Invoiced
                                        </div>

                                        <div class="fs-5 fw-bold text-abbyero">

                                            ${{ number_format(
                                                $user->invoices->sum('total'),
                                                2
                                            ) }}

                                        </div>
                                    </div>

                                    <i class="fas fa-dollar-sign
                                  text-primary
                                  opacity-75"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        SUMMARY CARDS
        ========================================================== --}}
        <div class="row g-3 mb-4">

            {{-- SCHEDULES --}}
            <div class="col-6 col-xl-3">

                <a href="#flight-schedules" class="text-decoration-none text-reset d-block h-100">

                    <div class="card border-0 shadow-sm h-100 summary-card">

                        <div class="card-body">

                            <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                                <div>

                                    <div class="summary-label">
                                        Schedules
                                    </div>

                                    <div class="summary-value text-secondary">
                                        {{ $user->flightSchedules->count() }}
                                    </div>

                                </div>

                                <div class="summary-icon bg-secondary-subtle
                                        text-primary">

                                    <i class="fas fa-calendar-alt"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </a>

            </div>



            {{-- DISPATCHES --}}
            <div class="col-6 col-xl-3">

                <a href="#flight-dispatch" class="text-decoration-none text-reset d-block h-100">

                    <div class="card border-0 shadow-sm h-100 summary-card">

                        <div class="card-body">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center">

                                <div>

                                    <div class="summary-label">
                                        Dispatches
                                    </div>

                                    <div class="summary-value text-warning">
                                        {{ $user->dispatches->count() }}
                                    </div>

                                </div>

                                <div class="summary-icon bg-warning-subtle
                                            text-warning">

                                    <i class="fas fa-paper-plane"></i>

                                </div>

                            </div>

                        </div>

                    </div>
                </a>

            </div>


            {{-- LOGBOOKS --}}
            <div class="col-6 col-xl-3">
                <a href="#flight-logbooks" class="text-decoration-none text-reset d-block h-100">

                    <div class="card border-0 shadow-sm h-100 summary-card">

                        <div class="card-body">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center">

                                <div>

                                    <div class="summary-label">
                                        Logbooks
                                    </div>

                                    <div class="summary-value text-info">
                                        {{ $user->logbooks->count() }}
                                    </div>

                                </div>

                                <div class="summary-icon bg-info-subtle
                                            text-info">

                                    <i class="fas fa-book"></i>

                                </div>

                            </div>

                        </div>

                    </div>
                </a>

            </div>


            {{-- APPROVED LOGBOOKS --}}
            <div class="col-6 col-xl-3">

                <a href="#flight-approvedLogbooks" class="text-decoration-none text-reset d-block h-100">

                    <div class="card border-0 shadow-sm h-100 summary-card">

                        <div class="card-body">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center">

                                <div>

                                    <div class="summary-label">
                                        Approved Logbooks
                                    </div>

                                    <div class="summary-value text-success">
                                        {{ $user->logbooks->where('approved', true)->count() }}
                                    </div>

                                </div>

                                <div class="summary-icon bg-success-subtle
                                            text-success">

                                    <i class="fas fa-check-double"></i>

                                </div>

                            </div>

                        </div>

                    </div>
                </a>

            </div>

        </div>

        {{-- =========================================================
        INVOICES
        Future: Generated from approved logbooks
        ========================================================== --}}
        <div id="flight-approvedLogbooks" class="card border-0 shadow-sm mb-4">

            {{-- HEADER --}}
            <div class="card-header bg-white border-0
                        d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center
                        gap-2 py-3 px-4">

                <div>
                    <h5 class="fw-bold mb-1">

                        <i class="fas fa-file-invoice-dollar
                                me-2 text-success"></i>

                        Invoices

                    </h5>

                    <small class="text-muted">
                        Billing generated from approved flight logbooks
                    </small>
                </div>

                {{-- INVOICE COUNT --}}
                <span class="badge bg-success-subtle text-success
                            border border-success-subtle px-3 py-2">

                    <i class="fas fa-check-circle me-1"></i>

                    {{ $invoices->count() }}
                    {{ Str::plural('Invoice', $invoices->count()) }}

                </span>

            </div>


            {{-- BODY --}}
            <div class="card-body p-0">

                @if($invoices->isNotEmpty())

                    {{-- RESPONSIVE TABLE --}}
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4 text-nowrap">
                                        Invoice #
                                    </th>

                                    <th class="text-nowrap">
                                        Flight
                                    </th>

                                    <th class="text-nowrap">
                                        Date
                                    </th>

                                    <th class="text-nowrap text-center">
                                        Hours
                                    </th>

                                    <th class="text-nowrap text-end">
                                        Amount
                                    </th>

                                    <th class="text-nowrap text-center">
                                        Status
                                    </th>

                                    <th class="text-nowrap text-center pe-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($invoices as $invoice)

                                    @foreach($invoice->items as $item)

                                        <tr>

                                            {{-- INVOICE NUMBER --}}
                                            <td class="ps-4">

                                                <div class="fw-semibold text-abbyero">

                                                    {{ $invoice->invoice_number }}

                                                </div>

                                            </td>


                                            {{-- FLIGHT --}}
                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $item->flight?->name
                                                        ?? $item->flight?->flight_number
                                                        ?? 'Flight Training' }}

                                                </div>

                                                @if($item->description)

                                                    <small class="text-muted">

                                                        {{ $item->description }}

                                                    </small>

                                                @endif

                                            </td>


                                            {{-- DATE --}}
                                            <td>

                                                <span class="text-nowrap">

                                                    {{ $invoice->invoice_date
                                                        ? \Carbon\Carbon::parse(
                                                            $invoice->invoice_date
                                                        )->format('M d, Y')
                                                        : '—' }}

                                                </span>

                                            </td>


                                            {{-- HOURS --}}
                                            <td class="text-center">

                                                <span class="fw-semibold">

                                                    {{ number_format(
                                                        (float) $item->quantity,
                                                        2
                                                    ) }}

                                                </span>

                                                <small class="text-muted">
                                                    hrs
                                                </small>

                                            </td>


                                            {{-- AMOUNT --}}
                                            <td class="text-end">

                                                <span class="fw-bold">

                                                    {{ $invoice->currency }}
                                                    {{ number_format(
                                                        (float) $item->amount,
                                                        2
                                                    ) }}

                                                </span>

                                            </td>


                                            {{-- STATUS --}}
                                            <td class="text-center">

                                                @php
                                                    $status = strtolower(
                                                        $invoice->status ?? 'pending'
                                                    );
                                                @endphp

                                                @if($status === 'paid')

                                                    <span class="badge
                                                                bg-success-subtle
                                                                text-success
                                                                border
                                                                border-success-subtle
                                                                px-2 py-1">

                                                        <i class="fas fa-check-circle
                                                                me-1"></i>

                                                        Paid

                                                    </span>

                                                @elseif($status === 'cancelled')

                                                    <span class="badge
                                                                bg-danger-subtle
                                                                text-danger
                                                                border
                                                                border-danger-subtle
                                                                px-2 py-1">

                                                        <i class="fas fa-times-circle
                                                                me-1"></i>

                                                        Cancelled

                                                    </span>

                                                @else

                                                    <span class="badge
                                                                bg-warning-subtle
                                                                text-warning-emphasis
                                                                border
                                                                border-warning-subtle
                                                                px-2 py-1">

                                                        <i class="fas fa-clock
                                                                me-1"></i>

                                                        Pending

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- VIEW INVOICE --}}
                                            <td class="text-center pe-4">

                                                <a href="{{ route(
                                                    'invoices.show',
                                                    $invoice->id
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary
                                                        text-nowrap">

                                                    <i class="fas fa-eye me-1"></i>

                                                    View

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    {{-- EMPTY STATE --}}
                    <div class="text-center text-muted py-5 px-4">

                        <div class="mb-3">

                            <span class="d-inline-flex
                                        align-items-center
                                        justify-content-center
                                        rounded-circle
                                        bg-light
                                        text-muted"
                                style="width: 64px; height: 64px;">

                                <i class="fas fa-file-invoice-dollar fa-2x"></i>

                            </span>

                        </div>

                        <h6 class="fw-bold mb-2">
                            No invoices yet
                        </h6>

                        <p class="small mb-0">
                            Invoices generated from your approved flight
                            logbooks will appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>

        {{-- =========================================================
        LOGBOOKS
        ========================================================== --}}
        <div id="flight-logbooks" class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0
                            d-flex justify-content-between
                            align-items-center py-3 px-4">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fas fa-book
                                      me-2 text-info"></i>

                        Flight Logbooks

                    </h5>

                    <small class="text-muted">
                        Flight records and approval status
                    </small>

                </div>

                <span class="badge bg-info-subtle text-info">

                    {{ $user->logbooks->count() }}

                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 user-detail-table">

                        <thead>

                            <tr>

                                <th>Date</th>
                                <th>Aircraft</th>
                                <th>Route</th>
                                <th>Type</th>
                                <th>Hours</th>
                                <th>Tach</th>
                                <th class="text-center">Approval</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($user->logbooks as $logbook)

                                                <tr>

                                                    <td class="text-nowrap fw-semibold">

                                                        {{ $logbook->flight_date
                                ? \Carbon\Carbon::parse($logbook->flight_date)->format('d M Y')
                                : '—'
                                                                                    }}

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

                                                    <td class="fw-semibold">

                                                        {{ number_format((float) $logbook->hours, 2) }}

                                                    </td>

                                                    <td>

                                                        {{ $logbook->tach_hours ?? '—' }}

                                                    </td>

                                                    <td class="text-center">

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

                                    <td colspan="7" class="text-center text-muted py-5">

                                        <i class="fas fa-book-open
                                                              fa-2x mb-2 opacity-50"></i>

                                        <div class="fw-semibold">
                                            No logbooks
                                        </div>

                                        <small>
                                            No flight logbooks have been recorded.
                                        </small>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- =========================================================
        DISPATCHES
        ========================================================== --}}
        <div id="flight-dispatch" class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0
                            d-flex justify-content-between
                            align-items-center py-3 px-4">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fas fa-paper-plane
                                      me-2 text-warning"></i>

                        Dispatches

                    </h5>

                    <small class="text-muted">
                        Aircraft dispatch records
                    </small>

                </div>

                <span class="badge bg-warning-subtle text-warning-emphasis">

                    {{ $user->dispatches->count() }}

                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 user-detail-table">

                        <thead>

                            <tr>

                                <th>Dispatch No.</th>
                                <th>Date</th>
                                <th>Aircraft</th>
                                <th>Pilot</th>
                                <th>Hobbs Out</th>
                                <th>Tach Out</th>
                                <th class="text-center">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($user->dispatches as $dispatch)

                                @php
                                    $dispatchStatus = strtolower($dispatch->status ?? '');

                                    $dispatchStatusClass = match ($dispatchStatus) {
                                        'dispatched' => 'status-dispatched',
                                        'in_flight' => 'status-scheduled',
                                        'completed' => 'status-completed',
                                        default => 'status-default',
                                    };
                                @endphp


                                <tr>

                                    <td class="fw-semibold">

                                        {{ $dispatch->dispatch_no ?? '—' }}

                                    </td>

                                    <td class="text-nowrap">

                                        {{ $dispatch->dispatch_time?->format('d M Y H:i') ?? '—' }}

                                    </td>

                                    <td>

                                        <span class="fw-semibold">

                                            {{ $dispatch->aircraft?->registration_number ?? '—' }}

                                        </span>

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

                                    <td class="text-center">

                                        <span class="badge {{ $dispatchStatusClass }}">

                                            {{ ucwords(str_replace('_', ' ', $dispatch->status)) }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-5">

                                        <i class="fas fa-paper-plane
                                                              fa-2x mb-2 opacity-50"></i>

                                        <div class="fw-semibold">
                                            No dispatches
                                        </div>

                                        <small>
                                            No aircraft dispatch records found.
                                        </small>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- =========================================================
        FLIGHT SCHEDULES
        ========================================================== --}}
        <div id="flight-schedules" class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0
                            d-flex justify-content-between
                            align-items-center py-3 px-4">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fas fa-calendar-alt
                                      me-2 text-primary"></i>

                        Flight Schedules

                    </h5>

                    <small class="text-muted">
                        Scheduled flights assigned to this user
                    </small>

                </div>

                <span class="badge bg-primary-subtle text-primary">

                    {{ $user->flightSchedules->count() }}

                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 user-detail-table">

                        <thead>

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

                                    <td class="fw-semibold text-nowrap">

                                        {{ $schedule->start_time?->format('d M Y') ?? '—' }}

                                    </td>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $schedule->flight?->registration_number ?? '—' }}

                                        </div>

                                    </td>

                                    <td>

                                        {{ $schedule->instructor?->name ?? '—' }}

                                    </td>

                                    <td class="text-nowrap">

                                        <i class="fas fa-clock
                                                              text-muted me-1"></i>

                                        {{ $schedule->start_time?->format('H:i') ?? '—' }}

                                    </td>

                                    <td class="text-nowrap">

                                        {{ $schedule->end_time?->format('H:i') ?? '—' }}

                                    </td>

                                    <td class="text-center">

                                        @php
                                            $scheduleStatusClass = match ($schedule->status) {
                                                'scheduled' => 'status-scheduled',
                                                'completed' => 'status-completed',
                                                'cancelled' => 'status-cancelled',
                                                'maintenance' => 'status-maintenance',
                                                'dispatched' => 'status-dispatched',
                                                default => 'status-default',
                                            };
                                        @endphp

                                        <span class="badge status-badge {{ $scheduleStatusClass }}">
                                            {{ ucwords(str_replace('_', ' ', $schedule->status)) }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center text-muted py-5">

                                        <i class="fas fa-calendar-times
                                                              fa-2x mb-2 opacity-50"></i>

                                        <div class="fw-semibold">
                                            No flight schedules
                                        </div>

                                        <small>
                                            This user has no scheduled flights.
                                        </small>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    PAGE STYLING
    ========================================================= --}}
    <style>
        :root {
            --abbyero-blue: #072743;
        }

        /* =========================================================
            FLIGHT STATUS COLORS
            ========================================================= */

        .summary-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        a:hover .summary-card {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.12) !important;
        }

        a:focus-visible .summary-card {
            outline: 2px solid #3788d8;
            outline-offset: 2px;
        }

        .status-badge {
            color: #fff !important;
            border: 0;
            font-weight: 600;
            padding: .45rem .7rem;
        }

        /* Scheduled */
        .status-scheduled {
            background: #3788d8 !important;
        }

        /* Completed */
        .status-completed {
            background: #2ecc71 !important;
        }

        /* Cancelled */
        .status-cancelled {
            background: #e74c3c !important;
        }

        /* Maintenance */
        .status-maintenance {
            background: #8e44ad !important;
        }

        /* Dispatched */
        .status-dispatched {
            background: #ffd620 !important;
            color: #212529 !important;
        }

        /* Unknown status */
        .status-default {
            background: #6c757d !important;
        }

        /* =========================================================
               PROFILE
            ========================================================= */

        .user-profile-card {
            border-left: 4px solid var(--abbyero-blue) !important;
        }

        .user-profile-avatar {
            width: 76px;
            height: 76px;
            min-width: 76px;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            border-radius: 50%;

            background: var(--abbyero-blue);
            color: #fff;

            font-size: 1.6rem;
            font-weight: 700;

            box-shadow: 0 .25rem .75rem rgba(7, 39, 67, .18);
        }

        .user-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        /* =========================================================
               SUMMARY CARDS
            ========================================================= */

        .summary-card {
            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        .summary-label {
            color: #6c757d;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-top: .25rem;
        }

        .summary-icon {
            width: 44px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            font-size: 1.1rem;
        }


        /* =========================================================
               DETAIL TABLES
            ========================================================= */

        .user-detail-table {
            font-size: .88rem;
        }

        .user-detail-table thead th {
            padding: .8rem .9rem;

            background: #f8f9fa;

            color: #495057;

            font-size: .74rem;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .025em;

            white-space: nowrap;
            border-bottom: 1px solid #dee2e6;
        }

        .user-detail-table tbody td {
            padding: .8rem .9rem;
        }

        .user-detail-table tbody tr {
            transition: background-color .15s ease;
        }

        .user-detail-table tbody tr:hover {
            background-color: rgba(7, 39, 67, .025);
        }


        /* =========================================================
               BADGES
            ========================================================= */

        .badge {
            font-weight: 600;
            letter-spacing: .01em;
        }


        /* =========================================================
               MOBILE
            ========================================================= */

        @media (max-width: 767.98px) {

            .container-fluid {
                padding-left: .75rem;
                padding-right: .75rem;
            }

            .user-profile-avatar {
                width: 60px;
                height: 60px;
                min-width: 60px;

                font-size: 1.25rem;
            }

            .user-profile-card .card-body {
                padding: 1rem !important;
            }

            .user-profile-card h3 {
                font-size: 1.2rem;
            }

            .summary-value {
                font-size: 1.45rem;
            }

            .summary-icon {
                width: 38px;
                height: 38px;

                border-radius: 10px;

                font-size: .95rem;
            }

            .summary-label {
                font-size: .68rem;
            }

            .user-detail-table {
                min-width: 700px;
                font-size: .82rem;
            }

            .user-detail-table thead th,
            .user-detail-table tbody td {
                padding: .65rem .7rem;
            }

        }


        /* =========================================================
               SMALL PHONES
            ========================================================= */

        @media (max-width: 575.98px) {

            .summary-card .card-body {
                padding: .8rem;
            }

            .summary-value {
                font-size: 1.3rem;
            }

            .summary-icon {
                width: 34px;
                height: 34px;
            }

            .user-profile-card h3 {
                font-size: 1.1rem;
            }

        }
    </style>

@endsection