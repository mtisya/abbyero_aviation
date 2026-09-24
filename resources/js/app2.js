import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
import timeGridPlugin from '@fullcalendar/timegrid';


let aircraftCalendar = null;
let calendar = null;
let selectedSlots = [];
let selectedResourceId = null;
let selectedCalendarEvent = null;

const Swal = window.Swal;

document.addEventListener('DOMContentLoaded', () => {

    const calendarEl = document.getElementById('calendar');

    const format = (date) => {
        if (!date) return 'N/A';

        return new Date(date).toLocaleString('en-US', {
            timeZone: 'UTC',
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, resourceTimelinePlugin],
        initialView: 'resourceTimelineDay',
        selectable: true,
        selectMirror: true,
        selectMinDistance: 5,
        editable: true,
        eventResourceEditable: true,
        eventResizableFromStart: true,
        slotDuration: '00:30:00',
        snapDuration: '00:30:00',
        timeZone: 'UTC',
        eventDisplay: 'block',
        eventOverlap: false,
        nowIndicator: true,
        height: 'auto',
        headerToolbar: window.innerWidth < 768 ? {
            left: 'prev,next today',
            center: 'title',
            right: 'scheduleSelected resourceTimelineDay'
        } : {
            left: 'prev,next today',
            center: 'title',
            right: 'scheduleSelected resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
        },

        customButtons: {
            scheduleSelected: {
                text: 'Schedule',
                click: function () {

                    if (!selectedResourceId || selectedSlots.length === 0) {
                        Swal.fire(
                            'No Slots Selected',
                            'Please select one or more 30-minute slots.',
                            'info'
                        );
                        return;
                    }

                    const resource =
                        calendar.getResourceById(selectedResourceId);

                    if (!resource) {
                        Swal.fire(
                            'Error',
                            'Aircraft could not be found.',
                            'error'
                        );
                        return;
                    }

                    showScheduleConfirmation(
                        selectedResourceId,
                        resource.title
                    );
                }
            }
        },

        resources: '/calendar/aircraft',
        events: '/calendar/schedules',


        resourceLabelDidMount: function (info) {
            info.el.style.cursor = 'pointer';

            info.el.onclick = function () {
                window.openAircraftCalendar(info.resource.id, info.resource.title);
            };

        },

        eventClickworking: function (info) {

            const event = info.event;
            const ext = event.extendedProps || {};

            const scheduleId =
                ext.schedule_id || event.id;

            const aircraftId =
                ext.aircraft_id ||
                event.getResources()?.[0]?.id ||
                '';

            const flightId =
                ext.flight_id || '';

            const status =
                ext.status || 'scheduled';

            const normalizedStatus =
                status.toLowerCase();

            const isCompleted =
                normalizedStatus === 'completed';

            const isDispatched =
                normalizedStatus === 'dispatched';

            selectedCalendarEvent = event;

            // ======================================================
            // ACTION BUTTONS
            // ======================================================

            let actionButtons = '';

            // ------------------------------------------------------
            // COMPLETED
            // Only show Logbooks
            // ------------------------------------------------------

            if (isCompleted) {

                actionButtons = `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="logbookEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 8px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-journal-text me-2"></i>
                        Logbooks
                    </button>

                `;

            } else {

                // --------------------------------------------------
                // DISPATCH / UN-DISPATCH
                // --------------------------------------------------

                actionButtons += isDispatched ? `

                    <button
                        type="button"
                        class="btn btn-danger py-2"
                        id="unDispatchEventButton"
                    >
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Un-Dispatch
                    </button>

                ` : `

                    <button
                        type="button"
                        class="btn btn-success py-2"
                        id="dispatchEventButton"
                    >
                        <i class="bi bi-send-fill me-2"></i>
                        Dispatch
                    </button>

                `;


                // --------------------------------------------------
                // CHECK-IN
                // --------------------------------------------------

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="checkInEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 8px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-airplane-engines-fill me-2"></i>
                        Check-In
                    </button>

                `;


                // --------------------------------------------------
                // LOGBOOK
                // --------------------------------------------------

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="logbookEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 8px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-journal-text me-2"></i>
                        Logbooks
                    </button>

                `;
            }


            // ======================================================
            // SWEETALERT
            // ======================================================

            Swal.fire({

                title: `✈ ${event.title}`,

                width: 500,

                padding: '1.25rem',

                customClass: {
                    popup: 'flight-action-popup'
                },

                html: `
                    <div class="text-start">

                        <div class="mb-3">
                            <strong>Aircraft:</strong>

                            <div class="mt-1">
                                <span class="badge bg-dark px-3 py-2">
                                    ✈ ${event.title}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Status:</strong>

                            <span class="badge ${isCompleted
                        ? 'bg-success'
                        : isDispatched
                            ? 'bg-success'
                            : 'bg-primary'
                    } ms-2">
                                ${status}
                            </span>
                        </div>

                        <div class="row">

                            <div class="col-6 mb-3">

                                <strong>Start:</strong>

                                <div class="text-muted small mt-1">
                                    ${event.start ? format(event.start) : '-'}
                                </div>

                            </div>

                            <div class="col-6 mb-3">

                                <strong>End:</strong>

                                <div class="text-muted small mt-1">
                                    ${event.end ? format(event.end) : '-'}
                                </div>

                            </div>

                        </div>

                        <hr>

                        <div class="d-grid gap-2">

                            ${actionButtons}

                        </div>

                    </div>
                `,

                showConfirmButton: false,

                showCancelButton: true,

                cancelButtonText: 'Close',

                didOpen: () => {

                    // ==================================================
                    // DISPATCH
                    // ==================================================

                    document
                        .getElementById('dispatchEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            openDispatchAlert(event);

                        });


                    // ==================================================
                    // UN-DISPATCH
                    // ==================================================

                    document
                        .getElementById('unDispatchEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            openUnDispatchAlert(event);

                        });

// ======================================================
// EDIT SCHEDULE
// ======================================================

document
    .getElementById('editEventButton')
    ?.addEventListener('click', () => {

        Swal.close();

        window.location.href =
            `/flights/schedules/${scheduleId}/edit`;

    });
// ======================================================
// DELETE SCHEDULE
// ======================================================

document
    .getElementById('deleteEventButton')
    ?.addEventListener('click', () => {

        Swal.close();

        openDeleteScheduleAlert(event);

    });




                    // ==================================================
                    // CHECK-IN
                    // ==================================================

                    document
                        .getElementById('checkInEventButton')
                        ?.addEventListener('click', async () => {

                            const button =
                                document.getElementById('checkInEventButton');

                            // --------------------------------------------------
                            // DISABLE BUTTON TO PREVENT DOUBLE CLICK
                            // --------------------------------------------------

                            if (button) {
                                button.disabled = true;
                            }


                            try {

                                // ==================================================
                                // GET CURRENT SCHEDULE INFORMATION
                                // ==================================================

                                const scheduleId =
                                    ext.schedule_id || event.id;

                                const aircraftId =
                                    ext.aircraft_id ||
                                    event.getResources()?.[0]?.id ||
                                    '';

                                const flightId =
                                    ext.flight_id ||
                                    aircraftId ||
                                    '';

                                const pilotId =
                                    ext.pilot_id ||
                                    '';


                                // ==================================================
                                // CURRENT AIRCRAFT METERS
                                // ==================================================

                                const hobbsOut =
                                    parseFloat(ext.current_hobbs || 0);

                                const tachOut =
                                    parseFloat(ext.current_tach || 0);


                                // ==================================================
                                // BASIC VALIDATION
                                // ==================================================

                                if (!scheduleId) {

                                    throw new Error(
                                        'No flight schedule was selected.'
                                    );
                                }


                                if (!aircraftId) {

                                    throw new Error(
                                        'No aircraft was selected.'
                                    );
                                }


                                // ==================================================
                                // CHECK CURRENT CALENDAR STATUS
                                // ==================================================

                                const normalizedStatus =
                                    String(ext.status || 'scheduled')
                                        .toLowerCase();


                                // ==================================================
                                // IF ALREADY DISPATCHED
                                // GO DIRECTLY TO CHECK-IN
                                // ==================================================

                                if (normalizedStatus === 'dispatched') {

                                    openCheckInPage(
                                        scheduleId,
                                        aircraftId,
                                        flightId
                                    );

                                    return;
                                }


                                // ==================================================
                                // NOT DISPATCHED
                                // DISPATCH FIRST
                                // ==================================================

                                const confirm = await Swal.fire({

                                    icon: 'info',

                                    title: 'Flight Not Dispatched',

                                    html: `
                    This flight has not been dispatched yet.<br><br>

                    <strong>Dispatch the flight before Check-In?</strong>
                `,

                                    showCancelButton: true,

                                    confirmButtonText:
                                        '<i class="bi bi-send-fill me-1"></i> Dispatch & Check-In',

                                    cancelButtonText: 'Cancel',

                                    confirmButtonColor: '#4886a3',

                                    cancelButtonColor: '#6c757d',

                                    allowOutsideClick: false
                                });


                                if (!confirm.isConfirmed) {

                                    if (button) {
                                        button.disabled = false;
                                    }

                                    return;
                                }


                                // ==================================================
                                // SHOW DISPATCHING MESSAGE
                                // ==================================================

                                Swal.fire({

                                    title: 'Dispatching Flight...',

                                    html: `
                                        Please wait while the flight
                                        is being dispatched.
                                    `,

                                    allowOutsideClick: false,

                                    allowEscapeKey: false,

                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });


                                // ==================================================
                                // DISPATCH REQUEST
                                // ==================================================

                                const dispatchResponse = await fetch(
                                    window.dispatchStoreUrl,
                                    {
                                        method: 'POST',

                                        headers: {
                                            'Content-Type':
                                                'application/json',

                                            'Accept':
                                                'application/json',

                                            'X-CSRF-TOKEN':
                                                document
                                                    .querySelector(
                                                        'meta[name="csrf-token"]'
                                                    )
                                                    .getAttribute('content')
                                        },

                                        body: JSON.stringify({

                                            schedule_id:
                                                scheduleId,

                                            aircraft_id:
                                                aircraftId,

                                            pilot_id:
                                                pilotId,

                                            hobbs_out:
                                                hobbsOut,

                                            tach_out:
                                                tachOut,

                                            dispatch_no:
                                                ext.dispatch_no || '',

                                            remarks:
                                                'Automatically dispatched during Check-In.'
                                        })
                                    }
                                );


                                // ==================================================
                                // READ RESPONSE
                                // ==================================================

                                const dispatchData =
                                    await dispatchResponse.json();


                                // ==================================================
                                // HANDLE DISPATCH FAILURE
                                // ==================================================

                                if (
                                    !dispatchResponse.ok ||
                                    !dispatchData.success
                                ) {

                                    throw new Error(
                                        dispatchData.message ||
                                        'Unable to dispatch the flight.'
                                    );
                                }


                                // ==================================================
                                // DISPATCH SUCCESS
                                // ==================================================

                                await Swal.fire({

                                    icon: 'success',

                                    title: 'Flight Dispatched',

                                    html: `
                    Dispatch No:
                    <strong>
                        ${dispatchData.dispatch_no || '-'}
                    </strong>
                    <br><br>
                    Continuing to Check-In...
                `,

                                    timer: 1500,

                                    showConfirmButton: false,

                                    allowOutsideClick: false
                                });


                                // ==================================================
                                // CONTINUE TO CHECK-IN
                                // ==================================================

                                openCheckInPage(
                                    scheduleId,
                                    aircraftId,
                                    flightId
                                );


                            } catch (error) {

                                console.error(
                                    'Check-In / Dispatch error:',
                                    error
                                );


                                Swal.close();


                                Swal.fire({

                                    icon: 'error',

                                    title: 'Check-In Failed',

                                    text:
                                        error.message ||
                                        'Unable to prepare the flight for Check-In.'
                                });


                                if (button) {
                                    button.disabled = false;
                                }
                            }
                        });


                    // ======================================================
                    // OPEN CHECK-IN PAGE
                    // ======================================================

                    function openCheckInPage(
                        scheduleId,
                        aircraftId,
                        flightId
                    ) {

                        const url = new URL(
                            window.logbooksIndexUrl,
                            window.location.origin
                        );


                        url.searchParams.set(
                            'schedule_id',
                            scheduleId
                        );


                        url.searchParams.set(
                            'flight_id',
                            flightId
                        );


                        url.searchParams.set(
                            'aircraft_id',
                            aircraftId
                        );


                        // Tell Logbooks page to open Check-In
                        url.searchParams.set(
                            'mode',
                            'checkin'
                        );


                        window.location.href =
                            url.toString();
                    }



                    // ==================================================
                    // LOGBOOK
                    // ==================================================

                    document
                        .getElementById('logbookEventButton')
                        ?.addEventListener('click', () => {

                            const url = new URL(
                                window.logbooksIndexUrl,
                                window.location.origin
                            );

                            url.searchParams.set(
                                'schedule_id',
                                scheduleId
                            );

                            url.searchParams.set(
                                'aircraft_id',
                                aircraftId
                            );

                            url.searchParams.set(
                                'flight_id',
                                flightId
                            );

                            url.searchParams.set(
                                'mode',
                                'logbook'
                            );

                            window.location.href =
                                url.toString();

                        });

                }

            });

        },

        eventClick: function (info) {

            const event = info.event;
            const ext = event.extendedProps || {};

            const scheduleId =
                ext.schedule_id || event.id;

            const aircraftId =
                ext.aircraft_id ||
                event.getResources()?.[0]?.id ||
                '';

            const flightId =
                ext.flight_id || '';

            const status =
                ext.status || 'scheduled';

            const normalizedStatus =
                String(status).toLowerCase();

            const isCompleted =
                normalizedStatus === 'completed';

            const isDispatched =
                normalizedStatus === 'dispatched';

            selectedCalendarEvent = event;


            // ======================================================
            // ACTION BUTTONS
            // ======================================================

            let actionButtons = '';


            // ======================================================
            // COMPLETED
            // ======================================================

            if (isCompleted) {

                // --------------------------------------------------
                // LOGBOOK
                // --------------------------------------------------

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="logbookEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 50px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-journal-text me-2"></i>
                        Logbooks
                    </button>

                `;


                // --------------------------------------------------
                // EDIT
                // --------------------------------------------------

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-warning py-2"
                        id="editEventButton"
                    >
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Schedule
                    </button>

                `;


                // --------------------------------------------------
                // DELETE
                // --------------------------------------------------

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-danger py-2"
                        id="deleteEventButton"
                    >
                        <i class="bi bi-trash me-2"></i>
                        Delete Schedule
                    </button>

                `;

            } else {

                // ==================================================
                // DISPATCH / UN-DISPATCH
                // ==================================================

                actionButtons += isDispatched ? `

                    <button
                        type="button"
                        class="btn btn-danger py-2"
                        id="unDispatchEventButton"
                    >
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Un-Dispatch
                    </button>

                ` : `

                    <button
                        type="button"
                        class="btn btn-success py-2"
                        id="dispatchEventButton"
                    >
                        <i class="bi bi-send-fill me-2"></i>
                        Dispatch
                    </button>

                `;


                // ==================================================
                // CHECK-IN
                // ==================================================

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="checkInEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 50px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-airplane-engines-fill me-2"></i>
                        Check-In
                    </button>

                `;


                // ==================================================
                // LOGBOOK
                // ==================================================

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-primary py-2"
                        id="logbookEventButton"
                        style="
                            background: linear-gradient(
                                135deg,
                                #4886a3,
                                #2f6078
                            );
                            border: none;
                            border-radius: 50px;
                            font-weight: 600;
                        "
                    >
                        <i class="bi bi-journal-text me-2"></i>
                        Logbooks
                    </button>

                `;


                // ==================================================
                // EDIT SCHEDULE
                // ==================================================

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-warning py-2"
                        id="editEventButton"
                    >
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Schedule
                    </button>

                `;


                // ==================================================
                // DELETE SCHEDULE
                // ==================================================

                actionButtons += `

                    <button
                        type="button"
                        class="btn btn-danger py-2"
                        id="deleteEventButton"
                    >
                        <i class="bi bi-trash me-2"></i>
                        Delete Schedule
                    </button>

                `;
            }


            // ======================================================
            // SWEETALERT
            // ======================================================

            Swal.fire({

                title: `✈ ${event.title}`,

                width: 'min(500px, calc(100vw - 24px))',
                padding: '1.25rem',

                customClass: {
                    popup: 'flight-action-popup'
                },

                html: `

                    <div class="text-start">

                        <!-- AIRCRAFT -->

                        <div class="mb-3">

                            <strong>Aircraft:</strong>

                            <div class="mt-1">

                                <span class="badge bg-dark px-3 py-2">

                                    ✈ ${event.title}

                                </span>

                            </div>

                        </div>


                        <!-- STATUS -->

                        <div class="mb-3">

                            <strong>Status:</strong>

                            <span class="
                                badge
                                ${
                                    isCompleted
                                        ? 'bg-success'
                                        : isDispatched
                                            ? 'bg-success'
                                            : 'bg-primary'
                                }
                                ms-2
                            ">

                                ${status}

                            </span>

                        </div>


                        <!-- START / END -->

                        <div class="row">

                            <div class="col-6 mb-3">

                                <strong>Start:</strong>

                                <div class="text-muted small mt-1">

                                    ${
                                        event.start
                                            ? format(event.start)
                                            : '-'
                                    }

                                </div>

                            </div>


                            <div class="col-6 mb-3">

                                <strong>End:</strong>

                                <div class="text-muted small mt-1">

                                    ${
                                        event.end
                                            ? format(event.end)
                                            : '-'
                                    }

                                </div>

                            </div>

                        </div>


                        <hr>


                        <!-- ACTION BUTTONS -->

                        <div class="d-grid gap-2">

                            ${actionButtons}

                        </div>

                    </div>

                `,

                showConfirmButton: false,

                showCancelButton: true,

                cancelButtonText: 'Close',


                // ==================================================
                // BUTTON EVENTS
                // ==================================================

                didOpen: () => {


                    // ==================================================
                    // DISPATCH
                    // ==================================================

                    document
                        .getElementById('dispatchEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            openDispatchAlert(event);

                        });


                    // ==================================================
                    // UN-DISPATCH
                    // ==================================================

                    document
                        .getElementById('unDispatchEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            openUnDispatchAlert(event);

                        });


                    // ==================================================
                    // EDIT SCHEDULE
                    // ==================================================

                    document
                        .getElementById('editEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            const editUrl =
                                window.scheduleEditUrl.replace(
                                    '__ID__',
                                    scheduleId
                                );

                            window.location.href = editUrl;

                        });


                    // ==================================================
                    // DELETE SCHEDULE
                    // ==================================================

                    document
                        .getElementById('deleteEventButton')
                        ?.addEventListener('click', () => {

                            Swal.close();

                            openDeleteScheduleAlert(event);

                        });


                    // ==================================================
                    // CHECK-IN
                    // ==================================================

                    document
                        .getElementById('checkInEventButton')
                        ?.addEventListener(
                            'click',
                            async () => {

                                const button =
                                    document.getElementById(
                                        'checkInEventButton'
                                    );


                                if (button) {
                                    button.disabled = true;
                                }


                                try {

                                    // ==========================================
                                    // IDENTIFIERS
                                    // ==========================================

                                    const scheduleId =
                                        ext.schedule_id || event.id;

                                    const aircraftId =
                                        ext.aircraft_id ||
                                        event.getResources()?.[0]?.id ||
                                        '';

                                    const flightId =
                                        ext.flight_id ||
                                        aircraftId ||
                                        '';

                                    const pilotId =
                                        ext.pilot_id ||
                                        '';


                                    // ==========================================
                                    // CURRENT AIRCRAFT METERS
                                    // ==========================================

                                    const hobbsOut =
                                        parseFloat(
                                            ext.current_hobbs || 0
                                        );

                                    const tachOut =
                                        parseFloat(
                                            ext.current_tach || 0
                                        );


                                    // ==========================================
                                    // VALIDATION
                                    // ==========================================

                                    if (!scheduleId) {

                                        throw new Error(
                                            'No flight schedule was selected.'
                                        );

                                    }


                                    if (!aircraftId) {

                                        throw new Error(
                                            'No aircraft was selected.'
                                        );

                                    }


                                    // ==========================================
                                    // CURRENT STATUS
                                    // ==========================================

                                    const currentStatus =
                                        String(
                                            ext.status || 'scheduled'
                                        ).toLowerCase();


                                    // ==========================================
                                    // ALREADY DISPATCHED
                                    // ==========================================

                                    if (
                                        currentStatus === 'dispatched'
                                    ) {

                                        openCheckInPage(
                                            scheduleId,
                                            aircraftId,
                                            flightId
                                        );

                                        return;

                                    }


                                    // ==========================================
                                    // NOT DISPATCHED
                                    // ASK TO DISPATCH FIRST
                                    // ==========================================

                                    const confirm =
                                        await Swal.fire({

                                            icon: 'info',

                                            title:
                                                'Flight Not Dispatched',

                                            html: `

                                                This flight has not
                                                been dispatched yet.

                                                <br><br>

                                                <strong>
                                                    Dispatch the flight
                                                    before Check-In?
                                                </strong>

                                            `,

                                            showCancelButton: true,

                                            confirmButtonText:
                                                '<i class="bi bi-send-fill me-1"></i> Dispatch & Check-In',

                                            cancelButtonText:
                                                'Cancel',

                                            confirmButtonColor:
                                                '#4886a3',

                                            cancelButtonColor:
                                                '#6c757d',

                                            allowOutsideClick:
                                                false

                                        });


                                    if (!confirm.isConfirmed) {

                                        if (button) {
                                            button.disabled = false;
                                        }

                                        return;

                                    }


                                    // ==========================================
                                    // DISPATCHING
                                    // ==========================================

                                    Swal.fire({

                                        title:
                                            'Dispatching Flight...',

                                        html: `

                                            Please wait while the flight
                                            is being dispatched.

                                        `,

                                        allowOutsideClick:
                                            false,

                                        allowEscapeKey:
                                            false,

                                        didOpen: () => {

                                            Swal.showLoading();

                                        }

                                    });


                                    // ==========================================
                                    // DISPATCH REQUEST
                                    // ==========================================

                                    const dispatchResponse =
                                        await fetch(
                                            window.dispatchStoreUrl,
                                            {

                                                method: 'POST',

                                                headers: {

                                                    'Content-Type':
                                                        'application/json',

                                                    'Accept':
                                                        'application/json',

                                                    'X-CSRF-TOKEN':
                                                        document
                                                            .querySelector(
                                                                'meta[name="csrf-token"]'
                                                            )
                                                            .getAttribute(
                                                                'content'
                                                            )

                                                },

                                                body:
                                                    JSON.stringify({

                                                        schedule_id:
                                                            scheduleId,

                                                        aircraft_id:
                                                            aircraftId,

                                                        pilot_id:
                                                            pilotId,

                                                        hobbs_out:
                                                            hobbsOut,

                                                        tach_out:
                                                            tachOut,

                                                        dispatch_no:
                                                            ext.dispatch_no ||
                                                            '',

                                                        remarks:
                                                            'Automatically dispatched during Check-In.'

                                                    })

                                            }
                                        );


                                    // ==========================================
                                    // RESPONSE
                                    // ==========================================

                                    const dispatchData =
                                        await dispatchResponse.json();


                                    // ==========================================
                                    // DISPATCH FAILED
                                    // ==========================================

                                    if (
                                        !dispatchResponse.ok ||
                                        !dispatchData.success
                                    ) {

                                        throw new Error(
                                            dispatchData.message ||
                                            'Unable to dispatch the flight.'
                                        );

                                    }


                                    // ==========================================
                                    // SUCCESS
                                    // ==========================================

                                    await Swal.fire({

                                        icon: 'success',

                                        title:
                                            'Flight Dispatched',

                                        html: `

                                            Dispatch No:

                                            <strong>
                                                ${
                                                    dispatchData.dispatch_no ||
                                                    '-'
                                                }
                                            </strong>

                                            <br><br>

                                            Continuing to Check-In...

                                        `,

                                        timer: 1500,

                                        showConfirmButton:
                                            false,

                                        allowOutsideClick:
                                            false

                                    });


                                    // ==========================================
                                    // CONTINUE CHECK-IN
                                    // ==========================================

                                    openCheckInPage(
                                        scheduleId,
                                        aircraftId,
                                        flightId
                                    );

                                } catch (error) {

                                    console.error(
                                        'Check-In / Dispatch error:',
                                        error
                                    );


                                    Swal.close();


                                    await Swal.fire({

                                        icon: 'error',

                                        title:
                                            'Check-In Failed',

                                        text:
                                            error.message ||
                                            'Unable to prepare the flight for Check-In.'

                                    });


                                    if (button) {
                                        button.disabled = false;
                                    }

                                }

                            }
                        );


                    // ==================================================
                    // LOGBOOK
                    // ==================================================

                    document
                        .getElementById('logbookEventButton')
                        ?.addEventListener('click', () => {

                            const url = new URL(
                                window.logbooksIndexUrl,
                                window.location.origin
                            );


                            url.searchParams.set(
                                'schedule_id',
                                scheduleId
                            );


                            url.searchParams.set(
                                'aircraft_id',
                                aircraftId
                            );


                            url.searchParams.set(
                                'flight_id',
                                flightId
                            );


                            url.searchParams.set(
                                'mode',
                                'logbook'
                            );


                            window.location.href =
                                url.toString();

                        });

                }

            });


            // ======================================================
            // OPEN CHECK-IN PAGE
            // ======================================================

            function openCheckInPage(
                scheduleId,
                aircraftId,
                flightId
            ) {

                const url = new URL(
                    window.logbooksIndexUrl,
                    window.location.origin
                );


                url.searchParams.set(
                    'schedule_id',
                    scheduleId
                );


                url.searchParams.set(
                    'flight_id',
                    flightId
                );


                url.searchParams.set(
                    'aircraft_id',
                    aircraftId
                );


                url.searchParams.set(
                    'mode',
                    'checkin'
                );


                window.location.href =
                    url.toString();

            }


        },



        dateClick(info) {

            const flightId = info.resource
                ? info.resource.id
                : null;

            if (!flightId) {
                Swal.fire(
                    'Error',
                    'No aircraft selected',
                    'error'
                );
                return;
            }

            const start = new Date(info.date);

            const end = new Date(
                start.getTime() + 30 * 60 * 1000
            );

            // Prevent scheduling in the past
            if (start < new Date()) {
                Swal.fire(
                    'Invalid',
                    'Cannot schedule in the past',
                    'error'
                );
                return;
            }

            // If user changes aircraft,
            // clear previous temporary selections
            if (
                selectedResourceId !== null &&
                selectedResourceId != flightId
            ) {
                clearTemporarySelection();
            }

            selectedResourceId = flightId;

            /*
             * Check if this slot is already temporarily selected.
             * Clicking it again removes it.
             */
            const existingIndex = selectedSlots.findIndex(
                slot =>
                    slot.start.getTime() === start.getTime()
            );

            if (existingIndex !== -1) {

                selectedSlots.splice(existingIndex, 1);

                renderTemporarySelection();

                updateScheduleButton();

                return;
            }

            /*
             * Check against existing saved schedules
             */
            if (hasConflict(flightId, start, end)) {

                Swal.fire(
                    'Conflict',
                    'This 30-minute slot is already occupied.',
                    'error'
                );

                return;
            }

            /*
             * ADD SLOT
             */
            selectedSlots.push({
                start: start,
                end: end
            });

            // Keep slots chronological
            selectedSlots.sort(
                (a, b) => a.start - b.start
            );

            renderTemporarySelection();

            updateScheduleButton();
        },

        /* ---------------- CREATE ---------------- */

        selectAllow: (selectInfo) => {
            // Prevent scheduling in the past
            return selectInfo.start >= new Date();
        },


        select(info) {

            const flightId = info.resource
                ? info.resource.id
                : null;

            if (!flightId) {

                Swal.fire(
                    'Error',
                    'No aircraft selected',
                    'error'
                );

                calendar.unselect();

                return;
            }

            /*
             * Changing aircraft clears
             * previous temporary selections.
             */
            if (
                selectedResourceId !== null &&
                selectedResourceId != flightId
            ) {
                clearTemporarySelection();
            }

            selectedResourceId = flightId;

            /*
             * Break the dragged range into
             * 30-minute slots.
             */
            let current = new Date(info.start);

            const end = new Date(info.end);

            while (current < end) {

                const slotStart = new Date(current);

                const slotEnd = new Date(
                    current.getTime() + 30 * 60 * 1000
                );

                /*
                 * Check temporary selection.
                 */
                const alreadySelected =
                    selectedSlots.some(
                        slot =>
                            slot.start.getTime() ===
                            slotStart.getTime()
                    );

                /*
                 * Check existing saved schedule.
                 */
                const conflict = hasConflict(
                    flightId,
                    slotStart,
                    slotEnd
                );

                /*
                 * Add only available slots.
                 */
                if (!alreadySelected && !conflict) {

                    selectedSlots.push({
                        start: slotStart,
                        end: slotEnd
                    });
                }

                current = slotEnd;
            }

            /*
             * Sort chronologically.
             */
            selectedSlots.sort(
                (a, b) => a.start - b.start
            );

            /*
             * Render selected slots.
             */
            renderTemporarySelection();

            /*
             * Update button.
             */
            updateScheduleButton();

            /*
             * Remove FullCalendar's
             * native selection.
             */
            calendar.unselect();
        },

        /* ---------------- TOOLTIP ---------------- */

        eventMouseEnter(info) {
            info.el.style.cursor = 'pointer';

            const format = (date) => {
                if (!date) return 'N/A';

                return new Date(date).toLocaleString('en-US', {
                    timeZone: 'UTC',
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            info.el.title =
                `Aircraft: ${info.event.title}
                    Start: ${format(info.event.start)}
                    End: ${format(info.event.end)}
                    Status: ${info.event.extendedProps.status || 'scheduled'}
                    User: ${info.event.extendedProps.user || 'system'}`;
        },

        /* ---------------- UPDATE ---------------- */

        eventDrop: updateSchedule,
        eventResize: updateSchedule,

        /* ---------------- LOADING ---------------- */

        loading(isLoading) {
            if (isLoading) {
                calendarEl.classList.add('opacity-50');
            } else {
                calendarEl.classList.remove('opacity-50');
            }
        }

    });

    calendar.render();

    calendarEl.addEventListener('click', function (e) {

        const cell = e.target.closest('.fc-datagrid-cell');

        if (!cell) return;

        // Get resource ID from FullCalendar internal data
        const resourceId = cell.getAttribute('data-resource-id');

        if (!resourceId) return;

        // Find resource from calendar API
        const resource = calendar.getResourceById(resourceId);

        if (!resource) return;

        window.openAircraftCalendar(resource.id, resource.title);
    });

    /* ===================================================== */
    function openLogbookModal(event, mode = 'checkin') {

        // Only prepare the modal for Check-In
        if (mode !== 'checkin') {
            return;
        }

        const modalElement =
            document.getElementById('createLogbookModal');

        if (!modalElement) {
            console.error(
                'Modal #createLogbookModal was not found.'
            );

            Swal.fire(
                'Error',
                'Logbook form could not be found.',
                'error'
            );

            return;
        }

        // --------------------------------------------------
        // Get Event Information
        // --------------------------------------------------

        const ext =
            event.extendedProps || {};

        const scheduleId =
            ext.schedule_id || event.id;

        const aircraftId =
            ext.aircraft_id ||
            event.getResources()?.[0]?.id ||
            '';

        const flightId =
            ext.flight_id ||
            aircraftId;

        // --------------------------------------------------
        // Populate Hidden Fields
        // --------------------------------------------------

        const scheduleInput =
            document.getElementById('schedule_id');

        const flightInput =
            document.getElementById('flight_id');

        if (scheduleInput) {
            scheduleInput.value = scheduleId;
        }

        if (flightInput) {
            flightInput.value = flightId;
        }

        // --------------------------------------------------
        // Select Aircraft / Schedule
        // --------------------------------------------------

        const aircraftSelect =
            document.getElementById('aircraftSelect');

        if (aircraftSelect && aircraftId) {

            const option =
                [...aircraftSelect.options].find(
                    opt =>
                        String(opt.value) ===
                        String(aircraftId)
                );

            if (option) {
                aircraftSelect.value = aircraftId;
            }
        }

        // --------------------------------------------------
        // Flight Date
        // --------------------------------------------------

        const flightDateInput =
            document.querySelector(
                '#createLogbookModal input[name="flight_date"]'
            );

        if (
            flightDateInput &&
            event.start
        ) {

            const date =
                new Date(event.start);

            const year =
                date.getUTCFullYear();

            const month =
                String(
                    date.getUTCMonth() + 1
                ).padStart(2, '0');

            const day =
                String(
                    date.getUTCDate()
                ).padStart(2, '0');

            flightDateInput.value =
                `${year}-${month}-${day}`;
        }

        // --------------------------------------------------
        // Open Modal
        // --------------------------------------------------

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

        modal.show();
    }

    function openDispatchAlert(event) {

        const start = format(event.start);
        const end = format(event.end);

        const ext = event.extendedProps || {};

        const safeNum = (val) => {
            const n = parseFloat(val);
            return isNaN(n) ? 0 : n;
        };

        const currentHobbs = safeNum(ext.current_hobbs);
        const currentTach = safeNum(ext.current_tach);

        Swal.fire({

            title: `✈ ${event.title}`,

            width: 750,

            heightAuto: false,

            customClass: {
                popup: 'dispatch-swal-popup',
                htmlContainer: 'dispatch-swal-body'
            },

            html: `
            <div class="text-start">

                <h5 class="mb-3 border-bottom pb-2">
                    Dispatch Details
                </h5>

                <div class="row">

                    <div class="col-6 mb-2">
                        <b>Aircraft:</b><br>
                        ${event.title}
                    </div>

                    <div class="col-6 mb-2">
                        <b>Status:</b><br>
                        ${ext.status || 'Scheduled'}
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">
                            Departure
                        </label>

                        <input
                            id="swalDeparture"
                            class="form-control"
                            placeholder="From"
                            value="${ext.departure || ''}">
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">
                            Destination
                        </label>

                        <input
                            id="swalDestination"
                            class="form-control"
                            placeholder="To"
                            value="${ext.destination || ''}">
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">
                            Pilot
                        </label>

                        <input
                            id="swalPilot"
                            class="form-control"
                            value="${ext.pilot || ''}">
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">
                            Co-Pilot
                        </label>

                        <input
                            id="swalCoPilot"
                            class="form-control"
                            value="${ext.copilot || ''}">
                    </div>

                    <div class="col-6 mb-2">
                        <b>Dispatch No:</b><br>
                        ${ext.dispatch_no || 'N/A'}
                    </div>

                    <div class="col-6 mb-2">
                        <b>User:</b><br>
                        ${ext.user || 'System'}
                    </div>

                    <div class="col-6 mb-2">
                        <b>Start:</b><br>
                        ${start}
                    </div>

                    <div class="col-6 mb-2">
                        <b>End:</b><br>
                        ${end}
                    </div>

                </div>

                <hr>

                <h6 class="mb-2 text-primary">
                    📊 Aircraft Utilization
                </h6>

                <div class="row">

                    <div class="col-4 mb-2">
                        <b>Hobbs Out:</b><br>

                        <span class="badge bg-info px-3">
                            ${currentHobbs.toFixed(1)} hrs
                        </span>
                    </div>

                    <div class="col-4 mb-2">
                        <b>Tach Out:</b><br>

                        <span class="badge bg-info px-3">
                            ${currentTach.toFixed(1)} hrs
                        </span>
                    </div>

                    <div class="col-4 mb-2">
                        <b>Aircraft Status:</b><br>

                        <span class="badge bg-success px-3 mb-2">
                            ACTIVE
                        </span>
                    </div>

                </div>

                <hr>

                <div class="col-12 mb-2">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        id="swalRemarks"
                        class="form-control"
                        rows="3"
                    >${ext.remarks || ''}</textarea>

                </div>

            </div>
        `,

            showCancelButton: true,

            confirmButtonText: 'Dispatch',

            cancelButtonText: 'Close',

            confirmButtonColor: '#198754'

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | GET VALUES FROM DISPATCH FORM
            |--------------------------------------------------------------------------
            */

            const departure =
                document.getElementById('swalDeparture')?.value || '';

            const destination =
                document.getElementById('swalDestination')?.value || '';

            const pilot =
                document.getElementById('swalPilot')?.value || '';

            const copilot =
                document.getElementById('swalCoPilot')?.value || '';

            const remarks =
                document.getElementById('swalRemarks')?.value || '';

            /*
            |--------------------------------------------------------------------------
            | UPDATE EVENT PROPERTIES
            |--------------------------------------------------------------------------
            */

            event.setExtendedProp(
                'departure',
                departure
            );

            event.setExtendedProp(
                'destination',
                destination
            );

            event.setExtendedProp(
                'pilot',
                pilot
            );

            event.setExtendedProp(
                'copilot',
                copilot
            );

            event.setExtendedProp(
                'remarks',
                remarks
            );

            /*
            |--------------------------------------------------------------------------
            | OPEN EXISTING DISPATCH MODAL
            |--------------------------------------------------------------------------
            */

            openDispatchModal(event);

            const modalElement =
                document.getElementById('dispatchModal');

            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );

            modal.show();
        });
    }

function openDeleteScheduleAlert(event) {

    const ext = event.extendedProps || {};

    const scheduleId =
        ext.schedule_id || event.id;

    Swal.fire({

        icon: 'warning',

        title: 'Delete Schedule?',

        html: `

            <p class="mb-2">
                Are you sure you want to delete this
                flight schedule?
            </p>

            <strong>
                ✈ ${event.title}
            </strong>

            <br><br>

            <span class="text-danger">
                This action cannot be undone.
            </span>

        `,

        showCancelButton: true,

        confirmButtonText:
            '<i class="bi bi-trash me-1"></i> Yes, Delete',

        cancelButtonText:
            'Cancel',

        confirmButtonColor:
            '#dc3545',

        cancelButtonColor:
            '#6c757d',

        allowOutsideClick:
            false

    }).then(async (result) => {

        if (!result.isConfirmed) {
            return;
        }


        // ==============================================
        // DELETE REQUEST
        // ==============================================

        Swal.fire({

            title: 'Deleting Schedule...',

            html:
                'Please wait while the schedule is deleted.',

            allowOutsideClick:
                false,

            allowEscapeKey:
                false,

            didOpen: () => {

                Swal.showLoading();

            }

        });


        try {

            const deleteUrl =
                window.scheduleDeleteUrl.replace(
                    '__ID__',
                    scheduleId
                );


            const response =
                await fetch(deleteUrl, {

                    method: 'DELETE',

                    headers: {

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute(
                                    'content'
                                )

                    }

                });


            const data =
                await response.json()
                    .catch(() => ({}));


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Unable to delete the schedule.'
                );

            }


            // ==============================================
            // REMOVE FROM CALENDAR
            // ==============================================

            event.remove();


            // ==============================================
            // SUCCESS
            // ==============================================

            Swal.fire({

                icon: 'success',

                title:
                    'Schedule Deleted',

                text:
                    data.message ||
                    'Schedule deleted successfully.',

                timer: 1800,

                showConfirmButton:
                    false

            });


        } catch (error) {

            console.error(
                'Delete schedule error:',
                error
            );


            Swal.fire({

                icon: 'error',

                title:
                    'Delete Failed',

                text:
                    error.message ||
                    'Unable to delete the schedule.'

            });

        }

    });

}



function openUnDispatchAlert(event) {

    const ext = event.extendedProps || {};

    const scheduleId =
        ext.schedule_id || event.id;

    const aircraftId =
        ext.aircraft_id ||
        event.getResources()?.[0]?.id ||
        '';

    const dispatchNo =
        ext.dispatch_no || 'N/A';


    Swal.fire({

        icon: 'warning',

        title: 'Un-Dispatch Aircraft?',

        html: `
            <div class="text-start">

                <p>
                    Are you sure you want to un-dispatch
                    <strong>${event.title}</strong>?
                </p>

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    This will return the flight schedule to
                    <strong>Scheduled</strong> status.

                </div>

                <hr>

                <div>
                    <strong>Aircraft:</strong>
                    ${event.title}
                </div>

                <div>
                    <strong>Dispatch No:</strong>
                    ${dispatchNo}
                </div>

            </div>
        `,

        showCancelButton: true,

        confirmButtonText:
            '<i class="bi bi-arrow-counterclockwise me-2"></i>Un-Dispatch',

        cancelButtonText: 'Cancel',

        confirmButtonColor: '#dc3545',

        reverseButtons: true,

        showLoaderOnConfirm: true,

        preConfirm: async () => {

            try {

                const response = await fetch(
                    window.unDispatchUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    ?.getAttribute('content')
                        },

                        body: JSON.stringify({

                            schedule_id: scheduleId,

                            aircraft_id: aircraftId

                        })
                    }
                );


                const data =
                    await response.json();


                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to un-dispatch aircraft.'
                    );

                }


                return data;


            } catch (error) {

                Swal.showValidationMessage(
                    error.message
                );

            }

        }

    }).then(result => {

        if (!result.isConfirmed) {
            return;
        }


        Swal.fire({

            icon: 'success',

            title: 'Un-Dispatched',

            text:
                result.value?.message ||
                'Aircraft has been un-dispatched successfully.',

            confirmButtonColor: '#4886a3'

        }).then(() => {

            // Refresh calendar
            if (typeof calendar !== 'undefined') {
                calendar.refetchEvents();
            } else {
                window.location.reload();
            }

        });

    });

}


    function renderTemporarySelection() {

        // Remove previous temporary events
        calendar
            .getEvents()
            .filter(event =>
                event.extendedProps?.temporarySelection
            )
            .forEach(event => event.remove());

        if (
            !selectedResourceId ||
            selectedSlots.length === 0
        ) {
            updateScheduleButton();
            return;
        }

        selectedSlots.forEach(slot => {

            calendar.addEvent({

                id:
                    `temporary-${selectedResourceId}-${slot.start.getTime()}`,

                resourceId:
                    selectedResourceId,

                start:
                    slot.start,

                end:
                    slot.end,

                title:
                    'Selected',

                display:
                    'background',

                backgroundColor:
                    '#198754',

                extendedProps: {
                    temporarySelection: true
                }
            });
        });

        updateScheduleButton();
    }
    function clearTemporarySelection() {

        calendar.getEvents()
            .filter(event =>
                event.extendedProps?.temporarySelection
            )
            .forEach(event => event.remove());

        selectedSlots = [];

        selectedResourceId = null;

        updateScheduleButton();
    }
    function showScheduleConfirmation(
        flightId,
        aircraftTitle
    ) {

        if (selectedSlots.length === 0) {
            return;
        }

        /*
         * Make sure slots are chronological
         */
        selectedSlots.sort(
            (a, b) => a.start - b.start
        );

        /*
         * Check that selected slots are continuous.
         *
         * Example:
         *
         * 10:00 → 10:30
         * 10:30 → 11:00
         * 11:00 → 11:30
         *
         * is valid.
         */

        for (let i = 1; i < selectedSlots.length; i++) {

            const previous = selectedSlots[i - 1];
            const current = selectedSlots[i];

            if (
                current.start.getTime() !==
                previous.end.getTime()
            ) {

                Swal.fire(
                    'Invalid Selection',
                    'Please select continuous 30-minute slots. There cannot be a gap between selected slots.',
                    'warning'
                );

                return;
            }
        }

        const firstSlot = selectedSlots[0];

        const lastSlot =
            selectedSlots[selectedSlots.length - 1];

        const start = firstSlot.start;

        const end = lastSlot.end;

        const totalMinutes =
            selectedSlots.length * 30;

        const hours =
            Math.floor(totalMinutes / 60);

        const minutes =
            totalMinutes % 60;

        let durationText = '';

        if (hours > 0) {

            durationText +=
                `${hours} hr${hours > 1 ? 's' : ''}`;
        }

        if (minutes > 0) {

            durationText +=
                ` ${minutes} min`;
        }

        if (!durationText) {
            durationText = '30 minutes';
        }

        Swal.fire({

            title: 'Schedule Aircraft',

            html: `
            <div class="text-start">

                <p class="mb-2">
                    <strong>Aircraft:</strong>
                    ${aircraftTitle}
                </p>

                <p class="mb-2">
                    <strong>Start:</strong>
                    ${format(start)}
                </p>

                <p class="mb-2">
                    <strong>End:</strong>
                    ${format(end)}
                </p>

                <p class="mb-2">
                    <strong>Duration:</strong>
                    ${durationText}
                </p>

                <p class="mb-0 text-muted">
                    <small>
                        ${selectedSlots.length}
                        × 30-minute slot${selectedSlots.length > 1 ? 's' : ''}
                    </small>
                </p>

            </div>
        `,

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Schedule',

            cancelButtonText: 'Cancel',

            confirmButtonColor: '#198754'

        }).then(result => {

            if (result.isConfirmed) {

                createSchedule(
                    flightId,
                    start,
                    end
                );

                clearTemporarySelection();

            }

            /*
             * Don't clear the selection if the user
             * simply closes the confirmation.
             *
             * This allows them to continue editing
             * their selected slots.
             */
        });
    }

    function updateScheduleButton() {

        const button =
            calendarEl.querySelector(
                '.fc-scheduleSelected-button'
            );

        if (!button) {
            return;
        }

        const count =
            selectedSlots.length;

        if (count === 0) {

            button.disabled = true;

            button.classList.add('disabled');

            button.innerHTML =
                '<i class="bi bi-calendar-plus me-1"></i> Schedule';

            return;
        }

        button.disabled = false;

        button.classList.remove('disabled');

        button.innerHTML =
            `<i class="bi bi-calendar-check me-1"></i>
         Schedule (${count})`;
    }

    function hasConflict(resourceId, start, end) {

        return calendar.getEvents().some(event => {

            // Ignore temporary selection blocks
            if (
                event.extendedProps?.temporarySelection
            ) {
                return false;
            }

            if (!event.start || !event.end) {
                return false;
            }

            const resources = event.getResources();

            if (!resources || resources.length === 0) {
                return false;
            }

            const sameAircraft = resources.some(
                resource =>
                    resource &&
                    String(resource.id) === String(resourceId)
            );

            if (!sameAircraft) {
                return false;
            }

            return (
                start < event.end &&
                end > event.start
            );
        });
    }

    function createSchedule(flightId, start, end) {

        fetch('/calendar/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            },
            body: JSON.stringify({
                flight_id: flightId,
                start: start.toISOString(),
                end: end.toISOString()
            })
        })
            .then(r => r.json())
            .then(d => {

                if (d.success) {

                    calendar.refetchEvents();

                    Swal.fire(
                        'Saved',
                        'Flight scheduled successfully',
                        'success'
                    );

                } else {

                    Swal.fire(
                        'Conflict',
                        d.message || 'Unable to save',
                        'error'
                    );
                }
            })
            .catch(() => {

                Swal.fire(
                    'Server Error',
                    'Please try again',
                    'error'
                );
            });
    }

    function updateSchedule(info) {

        fetch(`/calendar/update/${info.event.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                start: info.event.start.toISOString(),
                end: info.event.end.toISOString()
            })
        })
            .then(r => r.json())
            .then(d => {
                if (!d.success) {
                    info.revert();
                    Swal.fire('Error', d.message || 'Update failed', 'error');
                }
            })
            .catch(() => {
                info.revert();
                Swal.fire('Server Error', 'Please try again', 'error');
            });
    }

});

window.openAircraftCalendar = function (flightId, title) {

    document.getElementById('aircraftTitle').innerText = title + ' Calendar';

    const modalEl = document.getElementById('aircraftCalendarModal');

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    modal.show();

    modalEl.addEventListener('hidden.bs.modal', function () {

        if (aircraftCalendar) {
            aircraftCalendar.destroy();
            aircraftCalendar = null;
        }

        // Force cleanup
        document.body.classList.remove('modal-open');

        document.querySelectorAll('.modal-backdrop')
            .forEach(el => el.remove());

        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });

    // Wait for modal to fully render (IMPORTANT FIX)
    setTimeout(() => {

        if (aircraftCalendar) {
            aircraftCalendar.destroy();
        }

        aircraftCalendar = new Calendar(
            document.getElementById('aircraftCalendar'),
            {
                plugins: [interactionPlugin, timeGridPlugin],
                timeZone: 'UTC',
                initialView: 'timeGridWeek',
                selectable: true,
                // editable: true,
                height: 'auto',

                // ✅ Better: function-based events (auto refresh safe)
                events: function (fetchInfo, successCallback, failureCallback) {
                    fetch(`/calendar/schedules?flight_id=${flightId}`)
                        .then(res => res.json())
                        .then(data => successCallback(data))
                        .catch(failureCallback);
                },

                // ================= CREATE =================
                select(info) {

                    const start = info.start;
                    const end = info.end;

                    // Optional: prevent past booking
                    if (start < new Date()) {
                        Swal.fire('Invalid', 'Cannot schedule in the past', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Schedule Aircraft',
                        html: `
                                <div style="text-align:left">
                                    <b>Start:</b> ${start.toLocaleString()}<br>
                                    <b>End:</b> ${end.toLocaleString()}
                                </div>
                            `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Save'
                    }).then(async (result) => {

                        if (!result.isConfirmed) return;

                        try {
                            const response = await fetch('/calendar/create', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    flight_id: flightId,
                                    start: start.toISOString(),
                                    end: end.toISOString()
                                })
                            });

                            const data = await response.json();

                            if (!data.success) {
                                Swal.fire('Error', data.message || 'Unable to save', 'error');
                                return;
                            }

                            if (aircraftCalendar) {
                                aircraftCalendar.refetchEvents();
                            }

                            if (calendar) {
                                calendar.refetchEvents();
                            }

                            Swal.fire({
                                title: 'Saved',
                                text: 'Flight scheduled successfully',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });

                        } catch (err) {

                            console.error(err);

                            Swal.fire(
                                'Error',
                                err.message || 'Something went wrong',
                                'error'
                            );
                        }
                    });
                },

                // ================= UX =================
                eventClick(info) {

                    const format = (date) => {

                        if (!date) return 'N/A';

                        return new Date(date).toLocaleString('en-US', {
                            timeZone: 'UTC', // or America/Chicago
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    };

                    Swal.fire({
                        title: `✈ ${info.event.title}`,
                        html: `
                            <div style="text-align:left">
                                <p><b>Aircraft:</b> ${info.event.title}</p>
                                <p><b>Start:</b> ${format(info.event.start)}</p>
                                <p><b>End:</b> ${format(info.event.end)}</p>
                                <p><b>Status:</b> ${info.event.extendedProps.status || 'scheduled'}</p>
                                <p><b>User:</b> ${info.event.extendedProps.user || 'system'}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#0d6efd'
                    });
                },
            }
        );

        aircraftCalendar.render();

    }, 300); // small delay fixes hidden modal rendering issue
}
