@extends('layout')

@section('content')
    <div class="container-fluid my-4">

        {{-- Page Header --}}
        <div class="row g-3 mb-3">

            <!-- =====================================================
                FLIGHT DISPATCH HEADER
            ===================================================== -->
            <div class="col-12 col-xl-9">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <!-- TITLE -->
                        <div class="flex-grow-1">

                            <h3 class="mb-1 fw-bold">
                                ✈ Flight Dispatch Calendar
                            </h3>

                            <small class="text-muted">
                                Flight Scheduling & Dispatch Operations Center
                            </small>

                        </div>


                        <!-- ACTION BUTTONS -->
                        <div class="dispatch-header-actions d-flex gap-2 flex-wrap">

                            <a href="{{ route('dispatches.index') }}"
                            class="btn btn-success">
                                <i class="bi bi-send-check me-1"></i>
                                Dispatch Details
                            </a>


                            <a href="{{ route('flight.schedules') }}"
                            class="btn btn-primary">
                                <i class="bi bi-calendar-event me-1"></i>
                                Scheduled / Calendar View
                            </a>


                            <a href="javascript:history.back()"
                            class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Back
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                WORLD TIME / UTC
                ===================================================== -->
            <div class="col-12 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3">

                        <!-- Timezone Label -->
                        <div class="small text-muted mb-1" id="worldClockTimezone">
                            🌍 UTC
                        </div>

                        <!-- Current Time -->
                        <div class="fs-4 fw-bold text-dark" id="worldClockTime">
                            --:--:--
                        </div>

                        <!-- Current Date -->
                        <div class="small text-secondary" id="worldClockDate">
                            Loading...
                        </div>

                    </div>

                </div>

            </div>


        </div>


        {{-- Status Legend --}}
        <div class="mb-3 d-flex gap-4 flex-wrap">
            <span><span class="legend scheduled"></span> Scheduled</span>
            <span><span class="legend dispatched"></span> Dispatched</span>
            <span><span class="legend completed"></span> Completed</span>
            <span><span class="legend cancelled"></span> Cancelled</span>
            <span><span class="legend maintenance"></span> Maintenance</span>
        </div>

        {{-- Calendar --}}
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
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
@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.10/main.min.css" rel="stylesheet">

    <style>
        #calendar {
            height: 65vh;
            background: #fff;
        }

        /* Status Colors */
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
            background: #f39c12;
        }

        /* Improve timeline look */
        .fc-timeline-event {
            border-radius: 6px;
            font-size: 13px;
        }

        .fc-resource-group {
            background: #f8f9fa;
            font-weight: 600;
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

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/app.js', 'resources/js/dispatch.js'])
@endpush
<!-- Dispatch Modal -->
<div class="modal fade" id="dispatchModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

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

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" id="confirmDispatchBtn" class="btn btn-primary">
                        Dispatch Aircraft
                    </button>

                </div>

            </div>

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

            const deleteBtn =
                e.target.closest('.delete-btn');

            if (deleteBtn) {

                e.preventDefault();

                const form =
                    deleteBtn.closest('form');

                if (!form) return;

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

        });


        /* ======================================================
        DISPATCH STORE (AJAX)
        ====================================================== */

        $(document).on(
            'submit',
            '#dispatchForm',
            function (e) {

                e.preventDefault();

                const form = $(this);

                const btn =
                    form.find('button[type="submit"]');

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
                            .text('Dispatch');

                        if (res.success) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Dispatched',

                                text:
                                    'Aircraft dispatched successfully'

                            });

                            form.trigger('reset');
                        }

                    },

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