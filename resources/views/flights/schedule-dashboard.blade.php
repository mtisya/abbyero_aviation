@extends('layout')

@section('content')
    <div class="container-fluid my-4">

        {{-- ================= ALERTS ================= --}}
        @if(session('success'))
            <div id="success-alert" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm"
                style="z-index:1050;width:300px">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm"
                style="z-index:1050;width:300px">
                {{ session('error') }}
            </div>
        @endif

        <script>
            setTimeout(() => {
                document.getElementById('success-alert')?.remove();
                document.getElementById('error-alert')?.remove();
            }, 5000);
        </script>

        {{-- ================= HEADER ================= --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <h3 class="mb-0">✈ Flight Scheduling Dashboard</h3>

            <div class="d-flex gap-2 align-items-center flex-wrap">

                {{-- UTC Time --}}
                <button type="button"
                        class="btn btn-sm btn-dark d-flex align-items-center"
                        disabled>
                    <i class="bi bi-globe me-1"></i>
                    <span id="utcButtonTime">UTC --:--:--</span>
                </button>

                {{-- Calendar --}}
                <a href="{{ route('calendar') }}" class="btn btn-sm btn-primary d-flex align-items-center">
                    <i class="bi bi-calendar2-week me-1"></i>
                   Full Calendar View
                </a>

                {{-- Dispatch --}}
                <a href="{{ route('dispatches.index') }}" class="btn btn-sm btn-success d-flex align-items-center">
                    <i class="bi bi-send-check me-1"></i>
                    Dispatch Details
                </a>
                <a href="javascript:history.back()"
                class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>

            </div>
        </div>

        <div class="row flex-wrap">

            {{-- ================= LEFT COLUMN → TABLE ================= --}}
            <div class="col-12 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Scheduled Flights</h5>
                            <span class="badge bg-secondary">
                                Total: {{ $schedules->total() }}
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Aircraft</th>
                                        <th>Pilot</th>
                                        <th>Instructor</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Status</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($schedules as $s)

                                        @php
                                            $color = match ($s->status) {
                                                'scheduled' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'maintenance' => 'info',
                                                'dispatched' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp

                                        <tr>

                                            {{-- Aircraft --}}
                                            <td>
                                                <strong>
                                                    {{ $s->flight?->registration_number ?? 'N/A' }}
                                                </strong>
                                            </td>

                                            {{-- Student/Pilot --}}
                                            <td>
                                                <div>
                                                    <i class="bi bi-person"></i>
                                                    {{ $s->user?->name ?? 'Unassigned' }}
                                                </div>
                                            </td>

                                            {{-- Instructor --}}
                                            <td>
                                                <div>
                                                    <i class="bi bi-person-badge"></i>
                                                    {{ $s->instructor?->name ?? 'Not Assigned' }}
                                                </div>
                                            </td>

                                            {{-- Start --}}
                                            <td>
                                                {{ $s->start_time->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $s->start_time->format('H:i') }}
                                                </small>
                                            </td>

                                            {{-- End --}}
                                            <td>
                                                {{ $s->end_time->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $s->end_time->format('H:i') }}
                                                </small>
                                            </td>

                                            {{-- Status --}}
                                            <td>
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($s->status) }}
                                                </span>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                <a href="{{ route('flights.schedule.edit', $s->id) }}"
                                                    class="btn btn-sm btn-outline-warning mb-1 mb-md-0">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form method="POST" action="{{ route('flights.schedule.destroy', $s->id) }}"
                                                    class="d-inline delete-schedule-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                No schedules found
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">

                            {{-- Previous --}}
                            @if ($schedules->onFirstPage())
                                <button class="btn btn-sm btn-light" disabled>
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            @else
                                <a href="{{ $schedules->previousPageUrl() }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Page Info --}}
                            <span class="small text-muted">
                                Page {{ $schedules->currentPage() }} of {{ $schedules->lastPage() }}
                            </span>

                            {{-- Next --}}
                            @if ($schedules->hasMorePages())
                                <a href="{{ $schedules->nextPageUrl() }}" class="btn btn-sm btn-primary">
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

            {{-- ================= RIGHT COLUMN → CALENDAR ================= --}}
            <div class="col-12 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">

                        {{-- Status Legend --}}
                        <div class="mb-3 d-flex gap-4 flex-wrap">
                            <span><span class="legend scheduled"></span> Scheduled</span>
                            <span><span class="legend dispatched"></span> Dispatched</span>
                            <span><span class="legend completed"></span> Completed</span>
                            <span><span class="legend cancelled"></span> Cancelled</span>
                            <span><span class="legend maintenance"></span> Maintenance</span>
                        </div>

                        <div id="calendar" style="min-height: 400px;"></div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.10/main.min.css" rel="stylesheet">

    <style>
        /* Calendar */
        #calendar {
            width: 100%;
            height: 70vh;
            min-height: 400px;
            background: #fff;
        }

        /* Status Legend */
        .legend {
            width: 14px;
            height: 14px;
            display: inline-block;
            border-radius: 4px;
            margin-right: 6px;
        }

        .scheduled {
            background: #3788d8;
        }

        .completed {
            background: #2ecc71;
        }

        .cancelled {
            background: #e74c3c;
        }

        .maintenance {
            background: #8e44ad;
        }
        .dispatched {
            background: #ffd620;
        }

        /* Table responsiveness on small screens */
        /* Wrap FullCalendar header buttons on small screens */
        @media (max-width: 768px) {
            .fc-toolbar-chunk {
                display: flex;
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .fc-button {
                flex: 1 1 48%;
                margin-bottom: 0.25rem;
                font-size: 0.8rem;
            }
        }

        /* =========================================================
           DISPATCH MODAL - NAVBAR SAFE POSITIONING
        ========================================================= */

        #dispatchModal {
            padding-top: 90px !important;
        }

        #dispatchModal .modal-dialog {
            max-width: 900px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        #dispatchModal .modal-content {
            max-height: calc(100vh - 110px);
            display: flex;
            flex-direction: column;
        }

        #dispatchModal .modal-body {
            overflow-y: auto;
        }

        /* Keep header visible while scrolling */
        #dispatchModal .modal-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Keep footer visible while scrolling */
        #dispatchModal .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: #fff;
        }

        /* =========================================================
        DISPATCH SWEETALERT - NAVBAR SAFE
        ========================================================= */

        .swal2-container {
            padding-top: 90px !important;
            padding-bottom: 20px !important;
        }

        .dispatch-swal-popup {
            max-height: calc(100vh - 110px) !important;
        }

        .dispatch-swal-body {
            max-height: calc(100vh - 260px) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding-right: 8px !important;
        }

        /* Scrollbar */

        .dispatch-swal-body::-webkit-scrollbar {
            width: 6px;
        }

        .dispatch-swal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .dispatch-swal-body::-webkit-scrollbar-thumb {
            background: #aaa;
            border-radius: 10px;
        }

        /* =========================================================
        MOBILE
        ========================================================= */

        @media (max-width: 768px) {

            .swal2-container {
                padding: 90px 10px 10px !important;
            }

            .dispatch-swal-popup {
                width: calc(100% - 25px) !important;
                max-height: calc(100vh - 90px) !important;
            }

            .dispatch-swal-body {
                max-height: calc(100vh - 220px) !important;
            }
        }
        
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- @vite(['resources/js/app.js']) -->
    @vite(['resources/js/app.js', 'resources/js/dispatch.js'])
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const deleteForms = document.querySelectorAll('.delete-schedule-form');

        deleteForms.forEach(form => {

            const btn = form.querySelector('.delete-btn');

            btn.addEventListener('click', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This schedule will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,

                    customClass: {
                        confirmButton: 'btn btn-info me-2',
                        cancelButton: 'btn btn-secondary'
                    },

                    buttonsStyling: false,

                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'

                }).then(result => {

                    if (!result.isConfirmed) {
                        return;
                    }

                    // Prevent multiple clicks
                    btn.disabled = true;

                    // Submit using AJAX/fetch instead of normal form submission
                    fetch(form.action, {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': form.querySelector(
                                'input[name="_token"]'
                            ).value,

                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: new FormData(form)
                    })
                    .then(response => {

                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(
                                    data.message ||
                                    'Unable to delete the schedule.'
                                );
                            });
                        }

                        return response.json();
                    })
                    .then(data => {

                        if (data.success) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                timer: 1800,
                                showConfirmButton: false
                            }).then(() => {

                                // Refresh the schedules table
                                window.location.reload();

                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: data.message ||
                                    'Unable to delete the schedule.'
                            });

                            btn.disabled = false;
                        }
                    })
                    .catch(error => {

                        console.error('Delete error:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message ||
                                'Something went wrong while deleting the schedule.'
                        });

                        btn.disabled = false;
                    });

                });

            });

        });

    });
</script>
@endpush
<div class="modal fade" id="aircraftCalendarModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="aircraftTitle">Aircraft Calendar</h5>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

            <div class="modal-body">
                <div id="aircraftCalendar" style="height: 70vh;"></div>
            </div>

        </div>
    </div>
</div>
<form id="dispatchForm">
<div class="modal fade" id="dispatchModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <!-- HEADER -->
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    ✈ Confirm Dispatch
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <input type="hidden" id="scheduleId">
                <input type="hidden" id="aircraftId">
                <input type="hidden" id="pilotId">
                <div id="dispatchWarningBanner" class="alert alert-danger d-none m-3">
                    🚨 AIRCRAFT GROUNDED FOR INSPECTION
                </div>
                <!-- AIRCRAFT INFO -->
                <div class="border-bottom p-3 bg-white">

                    <div class="d-flex justify-content-between">

                        <div>
                            <strong>Aircraft:</strong>
                            <span id="dispatchAircraft"></span>
                        </div>

                        <div>
                            <strong>Status:</strong>
                            <span id="dispatchStatus"></span>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-2">

                        <div>
                            <strong>Departure:</strong>
                            <span id="dispatchDeparture"></span>
                        </div>

                        <div>
                            <strong>Destination:</strong>
                            <span id="dispatchDestination"></span>
                        </div>

                    </div>

                    <div class="mt-2">
                        <strong>Dispatch No:</strong>
                        <span id="dispatchNo"></span>
                    </div>

                </div>

                <!-- FLIGHT INFO -->
                <div class="p-3 border-bottom bg-light d-flex justify-content-between">

                    <div>
                        <strong>Start:</strong>
                        <span id="dispatchStart"></span>
                    </div>

                    <div>
                        <strong>End:</strong>
                        <span id="dispatchEnd"></span>
                    </div>

                    <div>
                        <strong>User:</strong>
                        <span id="dispatchUser"></span>
                    </div>

                </div>

                <!-- PILOT SECTION -->
                <div class="p-3 border-bottom bg-white">

                    <div class="d-flex justify-content-between">

                        <div>
                            <strong>
                                Pilot:
                                <span id="dispatchPilotName"></span>
                            </strong>
                        </div>

                        <div>
                            <strong>
                                Medical:
                                <span id="dispatchPilotMedical"></span>
                            </strong>
                        </div>

                    </div>

                    <div class="mt-2">
                        <strong>Co-Pilot:</strong>
                        <span id="dispatchCopilot"></span>
                    </div>

                </div>

                <!-- REMARKS -->
                <div class="p-3 border-bottom">

                    <strong>Remarks:</strong><br>
                    <span id="dispatchRemarks"></span>

                </div>
                <!-- AIRCRAFT METRICS -->
                <div class="p-3 bg-light border-top">

                    <h6 class="fw-bold mb-3">
                        📊 Aircraft Metrics
                    </h6>

                    <div class="row g-3">

                        <!-- TOTAL HOBBS -->
                        <div class="col-md-6">
                            <div class="border rounded bg-white p-3 h-100 text-center">

                                <small class="text-muted d-block">
                                    Hobbs Out
                                </small>

                                <div class="fw-bold text-primary fs-4">
                                    <span id="dispatchTotalHobbs">0.0</span> hrs
                                </div>

                            </div>
                        </div>

                        <!-- TOTAL TACH -->
                        <div class="col-md-6">
                            <div class="border rounded bg-white p-3 h-100 text-center">

                                <small class="text-muted d-block">
                                    Tach Out
                                </small>

                                <div class="fw-bold text-danger fs-4">
                                    <span id="dispatchTotalTach">0.0</span> hrs
                                </div>

                            </div>
                        </div>

                        <!-- MAINTENANCE -->
                        <div class="col-md-6">
                            <div class="border rounded bg-white p-3 text-center h-100">

                                <small class="text-muted d-block">
                                    Maintenance
                                </small>

                                <span id="dispatchMaintenance" class="badge bg-secondary px-3 py-2">
                                    OK
                                </span>

                            </div>
                        </div>

                        <!-- SERVICE TRACKER -->
                        <div class="col-md-6">
                            <div class="border rounded bg-white p-3 text-center h-100">

                                <small class="text-muted d-block">
                                    Service Tracker
                                </small>

                                <span id="dispatchServiceTracker" class="badge bg-info px-3 py-2">
                                    Normal
                                </span>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <!-- FOOTER -->
            <div class="modal-footer d-flex justify-content-between">

        <div>
            <input type="checkbox" id="printDispatch">
            <label for="printDispatch">Print</label>
        </div>

        <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit"
                    id="confirmDispatchBtn"
                    class="btn btn-primary">
                Dispatch Aircraft
            </button>

        </div>

    </div>
        </form>
        </div>
    </div>
</div>
<script>
    window.dispatchStoreUrl = "{{ route('dispatch.store') }}";
    window.logbooksIndexUrl = @json(route('logbooks.index'));
    window.unDispatchUrl = @json(route('dispatch.unDispatch'));
    window.scheduleEditUrl = @json(route('flights.schedule.edit', ['id' => '__ID__']));
    window.scheduleDeleteUrl = @json(route('flights.schedule.destroy', ['id' => '__ID__']));

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    /* ======================================================
       GLOBAL CLICK HANDLER
    ====================================================== */

    document.addEventListener('click', function (e) {

        /* ==================================================
           CANCEL DISPATCH
        ================================================== */

        const cancelBtn = e.target.closest('.cancel-btn');

        if (cancelBtn) {

            e.preventDefault();

            const formId =
                cancelBtn.getAttribute('data-form');

            const form =
                document.getElementById(formId);

            if (!form) {
                console.error(
                    'Cancel form not found:',
                    formId
                );
                return;
            }

            Swal.fire({

                title: 'Cancel Dispatch?',

                text: 'This will mark the flight as cancelled',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                confirmButtonText: 'Yes, cancel it'

            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });
        }


        /* ==================================================
           DELETE DISPATCH
        ================================================== */

       const deleteBtn = e.target.closest('.delete-btn');

        if (deleteBtn) {

            e.preventDefault();

            const form = deleteBtn.closest('form');

            if (!form) return;

            // Do NOT handle flight schedule deletion here.
            // The schedule-specific AJAX handler handles it.
            if (form.classList.contains('delete-schedule-form')) {
                return;
            }

            Swal.fire({

                title: 'Delete Dispatch?',

                text: 'This action cannot be undone!',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Yes, delete'

            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });
        }


    /* ======================================================
       DISPATCH STORE (AJAX)
    ====================================================== */

   $(document).on(
    'submit',
    '#dispatchForm',
    function (e) {
        e.preventDefault();

        const form = $(this);

        const btn = form.find(
            'button[type="submit"]'
        );

        $.ajax({
            url: '/dispatch/store',
            method: 'POST',
            data: form.serialize(),

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },

            beforeSend: function () {

                btn
                    .prop('disabled', true)
                    .text('Dispatching...');
            },

            success: function (res) {

                btn
                    .prop('disabled', false)
                    .text('Dispatch Aircraft');

                if (!res.success) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | FIND CALENDAR EVENT
                |--------------------------------------------------------------------------
                */

                const event = calendar.getEventById(
                    String(res.schedule_id)
                );

                /*
                |--------------------------------------------------------------------------
                | UPDATE EVENT TO DISPATCHED
                |--------------------------------------------------------------------------
                */

                if (event) {

                    const dispatchedColor = '#f39c12';

                    // Update status
                    event.setExtendedProp(
                        'status',
                        'dispatched'
                    );

                    // Update dispatch number if returned
                    if (res.dispatch_no) {
                        event.setExtendedProp(
                            'dispatch_no',
                            res.dispatch_no
                        );
                    }

                    // Update calendar color
                    event.setProp(
                        'backgroundColor',
                        dispatchedColor
                    );

                    event.setProp(
                        'borderColor',
                        dispatchedColor
                    );

                    event.setProp(
                        'textColor',
                        '#ffffff'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CLOSE MODAL
                |--------------------------------------------------------------------------
                */

                const modalElement =
                    document.getElementById(
                        'dispatchModal'
                    );

                const modal =
                    bootstrap.Modal.getInstance(
                        modalElement
                    );

                if (modal) {
                    modal.hide();
                }

                /*
                |--------------------------------------------------------------------------
                | RESET FORM
                |--------------------------------------------------------------------------
                */

                form.trigger('reset');

                /*
                |--------------------------------------------------------------------------
                | REFRESH CALENDAR DATA
                |--------------------------------------------------------------------------
                |
                | This makes sure the event matches the database.
                |
                */

                calendar.refetchEvents();

                /*
                |--------------------------------------------------------------------------
                | SUCCESS MESSAGE
                |--------------------------------------------------------------------------
                */

                Swal.fire({
                    icon: 'success',
                    title: 'Dispatched',
                    text: res.message ||
                        'Aircraft dispatched successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
            },

            error: function (xhr) {

                btn
                    .prop('disabled', false)
                    .text('Dispatch Aircraft');

                let message =
                    'Unable to dispatch aircraft.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {
                    message =
                        xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Dispatch Failed',
                    text: message
                });
            }
        });
    }
);

                error: function (xhr) {

                    let message =
                        'Something went wrong';

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    } else if (xhr.responseText) {

                        try {

                            const res =
                                JSON.parse(
                                    xhr.responseText
                                );

                            if (res.message) {
                                message = res.message;
                            }

                        } catch (e) {

                            console.log(
                                'Non-JSON response:',
                                xhr.responseText
                            );

                        }
                    }


                    /* ======================================
                       ALREADY DISPATCHED
                    ====================================== */

                    if (xhr.status === 409) {

                        Swal.fire({

                            icon: 'warning',

                            title: 'Already Dispatched',

                            text: message,

                            confirmButtonColor: '#f39c12'

                        });

                        return;
                    }


                    /* ======================================
                       DEFAULT ERROR
                    ====================================== */

                    Swal.fire({

                        icon: 'error',

                        title: 'Dispatch Failed',

                        text: message

                    });

                }

            });

        }
    );


    /* ======================================================
       OPEN CHECK-IN MODAL
       Only when mode=checkin
    ====================================================== */

    @if(($mode ?? 'logbook') === 'checkin' && $selectedSchedule)

        const modalElement =
            document.getElementById(
                'createLogbookModal'
            );

        if (modalElement) {

            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );

            modal.show();

        } else {

            console.error(
                'Modal #createLogbookModal was not found.'
            );

        }

    @endif

});
</script>