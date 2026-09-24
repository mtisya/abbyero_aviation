import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
import timeGridPlugin from '@fullcalendar/timegrid';


/*
|--------------------------------------------------------------------------
| GLOBAL CALENDAR STATE
|--------------------------------------------------------------------------
*/

let aircraftCalendar = null;
let calendar = null;

let selectedSlots = [];
let selectedResourceId = null;
let selectedCalendarEvent = null;
let handlingTemporarySelection = false;


/*
|--------------------------------------------------------------------------
| TIMEZONE CONFIGURATION
|--------------------------------------------------------------------------
|
| DATABASE:
|   Always store UTC.
|
| DISPLAY:
|   Use the user's selected timezone.
|
| INITIAL TIMEZONE:
|   1. Previously selected timezone
|   2. Browser's detected timezone
|   3. Application fallback
|
|--------------------------------------------------------------------------
*/

const TIMEZONE_STORAGE_KEY =
    'flight_calendar_timezone';

const DEFAULT_TIMEZONE =
    'America/Denver';


const TIMEZONE_OPTIONS = [

    {
        value: 'America/Denver',
        label: 'El Paso / Denver (MT)'
    },

    {
        value: 'UTC',
        label: 'UTC'
    },

    {
        value: 'Africa/Nairobi',
        label: 'Nairobi (EAT)'
    },

    {
        value: 'Africa/Johannesburg',
        label: 'Johannesburg (SAST)'
    },

    {
        value: 'Europe/London',
        label: 'London'
    },

    {
        value: 'Europe/Paris',
        label: 'Paris'
    },

    {
        value: 'America/New_York',
        label: 'New York (ET)'
    },

    {
        value: 'America/Chicago',
        label: 'Chicago (CT)'
    },

    {
        value: 'America/Los_Angeles',
        label: 'Los Angeles (PT)'
    },

    {
        value: 'Asia/Dubai',
        label: 'Dubai (GST)'
    },

    {
        value: 'Asia/Kolkata',
        label: 'Mumbai / Delhi (IST)'
    },

    {
        value: 'Asia/Singapore',
        label: 'Singapore'
    },

    {
        value: 'Australia/Sydney',
        label: 'Sydney'
    }

];


/*
|--------------------------------------------------------------------------
| DETECT BROWSER TIMEZONE
|--------------------------------------------------------------------------
*/

function getBrowserTimeZone() {

    try {

        const browserTimeZone =
            Intl.DateTimeFormat()
                .resolvedOptions()
                .timeZone;

        if (
            browserTimeZone &&
            TIMEZONE_OPTIONS.some(
                zone =>
                    zone.value === browserTimeZone
            )
        ) {

            return browserTimeZone;

        }

    } catch (error) {

        console.warn(
            'Unable to detect browser timezone:',
            error
        );

    }

    return null;
}


/*
|--------------------------------------------------------------------------
| GET INITIAL TIMEZONE
|--------------------------------------------------------------------------
*/

function getInitialTimeZone() {

    /*
     * 1. Previously selected timezone.
     */
    const savedTimeZone =
        localStorage.getItem(
            TIMEZONE_STORAGE_KEY
        );


    if (
        savedTimeZone &&
        TIMEZONE_OPTIONS.some(
            zone =>
                zone.value === savedTimeZone
        )
    ) {

        return savedTimeZone;

    }


    /*
     * 2. Detect the user's browser timezone.
     */
    const browserTimeZone =
        getBrowserTimeZone();


    if (browserTimeZone) {

        return browserTimeZone;

    }


    /*
     * 3. Final fallback.
     */
    return DEFAULT_TIMEZONE;

}


let selectedTimeZone =
    getInitialTimeZone();

    /*
|--------------------------------------------------------------------------
| GET TIMEZONE DISPLAY NAME
|--------------------------------------------------------------------------
*/

function getTimeZoneDisplayName(timeZone) {

    const zone =
        TIMEZONE_OPTIONS.find(
            item =>
                item.value === timeZone
        );

    if (zone) {
        return zone.label;
    }

    return timeZone;
}

/*
|--------------------------------------------------------------------------
| TIMEZONE SELECTOR
|--------------------------------------------------------------------------
|
| This creates the dropdown automatically if one does not already exist.
|
|--------------------------------------------------------------------------
*/

function updateUTCButtonTime() {
    const now = new Date();

    const utcTime = now.toLocaleTimeString('en-GB', {
        timeZone: 'UTC',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    const element = document.getElementById('utcButtonTime');

    if (element) {
        element.textContent = `UTC ${utcTime}`;
    }
}

updateUTCButtonTime();
setInterval(updateUTCButtonTime, 1000);

function updateUTCClock() {
    const now = new Date();

    // UTC Time
    const time = now.toLocaleTimeString('en-GB', {
        timeZone: 'UTC',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    // UTC Date
    const date = now.toLocaleDateString('en-GB', {
        timeZone: 'UTC',
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    const timeElement = document.getElementById('worldClockTime');
    const dateElement = document.getElementById('worldClockDate');

    if (timeElement) {
        timeElement.textContent = time;
    }

    if (dateElement) {
        dateElement.textContent = date;
    }
}

// Update immediately
updateUTCClock();

// Update every second
setInterval(updateUTCClock, 1000);

function createTimeZoneSelector() {

    let select =
        document.getElementById(
            'calendarTimeZone'
        );


    if (!select) {

        const calendarElement =
            document.getElementById(
                'calendar'
            );

        if (!calendarElement) {
            return null;
        }


        const wrapper =
            document.createElement('div');

        wrapper.className =
            'd-flex align-items-center gap-2 mb-3 flex-wrap';


        wrapper.innerHTML = `

            <label
                for="calendarTimeZone"
                class="fw-semibold mb-0"
            >
                Time Zone:
            </label>

            <select
                id="calendarTimeZone"
                class="form-select form-select-sm"
                style="min-width: 220px; width: auto;"
            >

                ${TIMEZONE_OPTIONS.map(zone => `

                    <option
                        value="${zone.value}"
                        ${zone.value === selectedTimeZone
                            ? 'selected'
                            : ''
                        }
                    >
                        ${zone.label}
                    </option>

                `).join('')}

            </select>

            <small
                id="timezoneDetectedMessage"
                class="text-muted"
            ></small>

        `;


        calendarElement.parentNode.insertBefore(
            wrapper,
            calendarElement
        );


        select =
            document.getElementById(
                'calendarTimeZone'
            );


    } else {

        /*
         * Ensure options exist.
         */
        if (select.options.length === 0) {

            TIMEZONE_OPTIONS.forEach(
                zone => {

                    const option =
                        document.createElement(
                            'option'
                        );

                    option.value =
                        zone.value;

                    option.textContent =
                        zone.label;

                    select.appendChild(
                        option
                    );

                }
            );

        }


        select.value =
            selectedTimeZone;

    }


    /*
     |--------------------------------------------------------------------------
     | CHANGE HANDLER
     |--------------------------------------------------------------------------
     */

    select.addEventListener(
        'change',
        function () {

            changeCalendarTimeZone(
                this.value
            );

        }
    );


    /*
     |--------------------------------------------------------------------------
     | SHOW CURRENT SOURCE
     |--------------------------------------------------------------------------
     */

    const browserTimeZone =
        getBrowserTimeZone();

    const messageElement =
        document.getElementById(
            'timezoneDetectedMessage'
        );


    if (
        messageElement &&
        browserTimeZone
    ) {

        if (
            browserTimeZone ===
            selectedTimeZone
        ) {

            messageElement.textContent =
                'Detected automatically';

        } else {

            messageElement.textContent =
                'Using your selected timezone';

        }

    }


    return select;

}

/*
|--------------------------------------------------------------------------
| WORLD CLOCK
|--------------------------------------------------------------------------
*/

function updateWorldClock() {
    const timezoneElement = document.getElementById('worldClockTimezone');
    const timeElement = document.getElementById('worldClockTime');
    const dateElement = document.getElementById('worldClockDate');

    if (!timezoneElement || !timeElement || !dateElement) {
        return;
    }

    const now = new Date();

    const timezoneLabel = getTimeZoneLabel();

    const time = new Intl.DateTimeFormat('en-GB', {
        timeZone: selectedTimeZone,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    }).format(now);

    const date = new Intl.DateTimeFormat('en-GB', {
        timeZone: selectedTimeZone,
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(now);

    timezoneElement.querySelector('span').textContent = timezoneLabel;
    timeElement.textContent = time;
    dateElement.textContent = date;
}


/*
|--------------------------------------------------------------------------
| CHANGE TIMEZONE
|--------------------------------------------------------------------------
*/

function changeCalendarTimeZone(timeZone) {

    /*
     |--------------------------------------------------------------------------
     | VALIDATE TIMEZONE
     |--------------------------------------------------------------------------
     */

    const isValid =
        TIMEZONE_OPTIONS.some(
            zone =>
                zone.value === timeZone
        );


    if (!isValid) {

        timeZone =
            DEFAULT_TIMEZONE;

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE STATE
     |--------------------------------------------------------------------------
     */

    selectedTimeZone =
        timeZone;


    /*
     |--------------------------------------------------------------------------
     | SAVE USER'S CHOICE
     |--------------------------------------------------------------------------
     */

    localStorage.setItem(
        TIMEZONE_STORAGE_KEY,
        selectedTimeZone
    );


    /*
     |--------------------------------------------------------------------------
     | UPDATE MAIN CALENDAR
     |--------------------------------------------------------------------------
     */

    if (calendar) {

        calendar.setOption(
            'timeZone',
            selectedTimeZone
        );

        calendar.refetchEvents();

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE AIRCRAFT CALENDAR
     |--------------------------------------------------------------------------
     */

    if (aircraftCalendar) {

        aircraftCalendar.setOption(
            'timeZone',
            selectedTimeZone
        );

        aircraftCalendar.refetchEvents();

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE SELECT
     |--------------------------------------------------------------------------
     */

    const select =
        document.getElementById(
            'calendarTimeZone'
        );


    if (
        select &&
        select.value !== selectedTimeZone
    ) {

        select.value =
            selectedTimeZone;

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE DETECTION MESSAGE
     |--------------------------------------------------------------------------
     */

    const browserTimeZone =
        getBrowserTimeZone();

    const messageElement =
        document.getElementById(
            'timezoneDetectedMessage'
        );


    if (messageElement) {

        messageElement.textContent =
            browserTimeZone === selectedTimeZone
                ? 'Detected automatically'
                : 'Using your selected timezone';

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE TEMPORARY SELECTIONS
     |--------------------------------------------------------------------------
     */

    renderTemporarySelection();


    /*
     |--------------------------------------------------------------------------
     | UPDATE WORLD CLOCK
     |--------------------------------------------------------------------------
     */

    updateWorldClock();

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE IN SELECTED TIMEZONE
|--------------------------------------------------------------------------
*/

function formatDate(date) {

    if (!date) {
        return 'N/A';
    }


    return new Intl.DateTimeFormat(
        'en-US',
        {
            timeZone: selectedTimeZone,

            year: 'numeric',

            month: 'short',

            day: '2-digit',

            hour: '2-digit',

            minute: '2-digit'
        }
    ).format(new Date(date));

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE ONLY IN SELECTED TIMEZONE
|--------------------------------------------------------------------------
|
| Used by the Logbook flight_date field.
|
| Example:
|
| UTC:
| 2026-09-02 22:00
|
| Nairobi:
| 2026-09-03
|
|--------------------------------------------------------------------------
*/

function formatDateOnly(date) {

    if (!date) {
        return '';
    }


    const parts =
        new Intl.DateTimeFormat(
            'en-CA',
            {
                timeZone: selectedTimeZone,

                year: 'numeric',

                month: '2-digit',

                day: '2-digit'
            }
        ).formatToParts(
            new Date(date)
        );


    const values = {};

    parts.forEach(part => {

        if (part.type !== 'literal') {

            values[part.type] =
                part.value;

        }

    });


    return `${values.year}-${values.month}-${values.day}`;
}


/*
|--------------------------------------------------------------------------
| SAFE NUMBER
|--------------------------------------------------------------------------
*/

function safeNumber(value) {

    const number =
        parseFloat(value);

    return Number.isNaN(number)
        ? 0
        : number;
}


/*
|--------------------------------------------------------------------------
| DOM READY
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    () => {

        const calendarEl =
            document.getElementById('calendar');


        if (!calendarEl) {

            console.error(
                'Calendar element #calendar was not found.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE TIMEZONE DROPDOWN
        |--------------------------------------------------------------------------
        */

        createTimeZoneSelector();


        /*
        |--------------------------------------------------------------------------
        | MAIN CALENDAR
        |--------------------------------------------------------------------------
        */

        calendar = new Calendar(calendarEl, {
            plugins: [
                interactionPlugin,
                resourceTimelinePlugin
            ],

            timeZone: selectedTimeZone,

            initialView: 'resourceTimelineDay',

            selectable: true,
            selectMirror: true,
            selectMinDistance: 5,

            editable: true,
            eventResourceEditable: true,
            eventResizableFromStart: true,

            slotDuration: '00:30:00',
            snapDuration: '00:30:00',

            eventDisplay: 'block',
            eventOverlap: false,
            nowIndicator: true,
            height: 'auto',

            /*
            |--------------------------------------------------------------------------
            | ZULU TIME FORMAT
            |--------------------------------------------------------------------------
            */

            slotLabelFormat: function (date) {

                const hours = String(
                    date.date.hour
                ).padStart(2, '0');

                const minutes = String(
                    date.date.minute
                ).padStart(2, '0');

                return `${hours}${minutes}`;
            },

            eventTimeFormat: function (date) {

                const hours = String(
                    date.date.hour
                ).padStart(2, '0');

                const minutes = String(
                    date.date.minute
                ).padStart(2, '0');

                return `${hours}${minutes}`;
            },


            headerToolbar:
                window.innerWidth < 576
                    ? {
                        left: 'prev,next',
                        center: 'title',
                        right: 'scheduleSelected',
                    }
                    : window.innerWidth < 768
                        ? {
                            left: 'prev,next',
                            center: 'title',
                            right: 'today scheduleSelected resourceTimelineDay',
                        }
                        : {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'scheduleSelected resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth',
                        },

            /*
            |--------------------------------------------------------------------------
            | CUSTOM BUTTON
            |--------------------------------------------------------------------------
            */

            customButtons: {

                scheduleSelected: {

                    text:
                        'Schedule(s)',


                    click: function () {

                        if (
                            !selectedResourceId ||
                            selectedSlots.length === 0
                        ) {

                            Swal.fire(
                                'No Slots Selected',
                                'Please select one or more 30-minute slots.',
                                'info'
                            );

                            return;
                        }


                        const resource =
                            calendar.getResourceById(
                                selectedResourceId
                            );


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


            /*
            |--------------------------------------------------------------------------
            | DATA SOURCES
            |--------------------------------------------------------------------------
            */

            resources:
                '/calendar/aircraft',


            events:
                '/calendar/schedules',


            /*
            |--------------------------------------------------------------------------
            | RESOURCE LABEL CLICK
            |--------------------------------------------------------------------------
            */

            resourceLabelDidMount:
                function (info) {

                    info.el.style.cursor =
                        'pointer';


                    info.el.onclick =
                        function () {

                            window.openAircraftCalendar(
                                info.resource.id,
                                info.resource.title
                            );

                        };

                },


            /*
            |--------------------------------------------------------------------------
            | EVENT CLICK
            |--------------------------------------------------------------------------
            */

            eventClick: function (info) {

                const event = info.event;
                const ext = event.extendedProps || {};

                /*
                |--------------------------------------------------------------------------
                | IGNORE TEMPORARY SELECTION EVENTS
                |--------------------------------------------------------------------------
                */

                if (
                    ext.temporarySelection === true ||
                    ext.isTemporarySelection === true
                ) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | OPEN REAL FLIGHT EVENT
                |--------------------------------------------------------------------------
                */

                openFlightEventAlert(event);
            },


            /*
            |--------------------------------------------------------------------------
            | DATE CLICK
            |--------------------------------------------------------------------------
            */

            dateClick:
                function (info) {

                    handleDateClick(
                        info
                    );

                },


            /*
            |--------------------------------------------------------------------------
            | DRAG / RANGE SELECTION
            |--------------------------------------------------------------------------
            */

            


            select:
                function (info) {

                    handleRangeSelection(
                        info
                    );

                },


            /*
            |--------------------------------------------------------------------------
            | TOOLTIP
            |--------------------------------------------------------------------------
            */

            eventMouseEnter:
                function (info) {

                    info.el.style.cursor =
                        'pointer';


                    const ext =
                        info.event.extendedProps ||
                        {};


                    info.el.title =
                        `Aircraft: ${info.event.title}
                                Start: ${formatDate(info.event.start)}
                                End: ${formatDate(info.event.end)}
                                Status: ${ext.status || 'scheduled'}
                                User: ${ext.user || 'system'}`;

                },


            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            eventDrop:
                updateSchedule,


            eventResize:
                updateSchedule,


            /*
            |--------------------------------------------------------------------------
            | LOADING
            |--------------------------------------------------------------------------
            */

            loading:
                function (isLoading) {

                    if (isLoading) {

                        calendarEl.classList.add(
                            'opacity-50'
                        );

                    } else {

                        calendarEl.classList.remove(
                            'opacity-50'
                        );

                    }

                }

        }
        );


        /*
        |--------------------------------------------------------------------------
        | RENDER MAIN CALENDAR
        |--------------------------------------------------------------------------
        */

        calendar.render();


        /*
        |--------------------------------------------------------------------------
        | RESOURCE CLICK FALLBACK
        |--------------------------------------------------------------------------
        */

        calendarEl.addEventListener(
            'click',
            function (e) {

                const cell =
                    e.target.closest(
                        '.fc-datagrid-cell'
                    );


                if (!cell) {
                    return;
                }


                const resourceId =
                    cell.getAttribute(
                        'data-resource-id'
                    );


                if (!resourceId) {
                    return;
                }


                const resource =
                    calendar.getResourceById(
                        resourceId
                    );


                if (!resource) {
                    return;
                }


                window.openAircraftCalendar1(
                    resource.id,
                    resource.title
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | OPEN LOGBOOK MODAL
        |--------------------------------------------------------------------------
        */

        window.openLogbookModal =
            openLogbookModal;

    }
);


/*
|--------------------------------------------------------------------------
| HANDLE DATE CLICK
|--------------------------------------------------------------------------
*/

function handleDateClick(info) {

    handlingTemporarySelection = true;

    const flightId =
        info.resource
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


    /*
     * info.date is already the Date object
     * corresponding to the selected FullCalendar
     * timezone instant.
     */
    const start =
        new Date(info.date);


    const end =
        new Date(
            start.getTime() +
            30 * 60 * 1000
        );


    /*
     * If user changes aircraft,
     * clear previous temporary selections.
     */
    if (
        selectedResourceId !== null &&
        String(selectedResourceId) !== String(flightId)
    ) {

        clearTemporarySelection();

    }


    selectedResourceId =
        flightId;


    /*
     * Clicking an already-selected slot
     * removes it.
     */
    const existingIndex =
        selectedSlots.findIndex(
            slot =>
                slot.start.getTime() ===
                start.getTime()
        );

    if (existingIndex !== -1) {

        selectedSlots.splice(existingIndex, 1);

        renderTemporarySelection();

        updateScheduleButton();

        // Prevent FullCalendar from treating this click
        // as an event interaction.
        calendar.unselect();

        return;
    }


    /*
     * Check against existing schedules.
     */
    if (
        hasConflict(
            flightId,
            start,
            end
        )
    ) {

        Swal.fire(
            'Conflict',
            'This 30-minute slot is already occupied.',
            'error'
        );

        return;
    }


    /*
     * Add slot.
     */
    selectedSlots.push({
        start: start,
        end: end
    });

    selectedSlots.sort(
        (a, b) =>
            a.start.getTime() -
            b.start.getTime()
    );

    renderTemporarySelection();
    updateScheduleButton();

    handlingTemporarySelection = false;

}


/*
|--------------------------------------------------------------------------
| HANDLE DRAGGED RANGE SELECTION
|--------------------------------------------------------------------------
*/

function handleRangeSelection(info) {

    const flightId =
        info.resource
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
        String(selectedResourceId) !== String(flightId)
    ) {

        clearTemporarySelection();

    }


    selectedResourceId =
        flightId;


    /*
     * Break the selected range into
     * 30-minute slots.
     */
    let current =
        new Date(info.start);


    const end =
        new Date(info.end);


    while (
        current < end
    ) {

        const slotStart =
            new Date(current);


        const slotEnd =
            new Date(
                current.getTime() +
                30 * 60 * 1000
            );


        /*
         * Temporary selection check.
         */
        const alreadySelected =
            selectedSlots.some(
                slot =>
                    slot.start.getTime() ===
                    slotStart.getTime()
            );


        /*
         * Existing schedule conflict.
         */
        const conflict =
            hasConflict(
                flightId,
                slotStart,
                slotEnd
            );


        /*
         * Add only available slots.
         */
        if (
            !alreadySelected &&
            !conflict
        ) {

            selectedSlots.push({

                start:
                    slotStart,

                end:
                    slotEnd

            });

        }


        current =
            slotEnd;

    }


    /*
     * Sort chronologically.
     */
    selectedSlots.sort(
        (a, b) =>
            a.start.getTime() -
            b.start.getTime()
    );


    renderTemporarySelection();

    updateScheduleButton();


    /*
     * Remove FullCalendar's native selection.
     */
    calendar.unselect();

}


/*
|--------------------------------------------------------------------------
| OPEN FLIGHT EVENT ALERT
|--------------------------------------------------------------------------
*/
function openFlightEventAlert(event) {

    const ext =
        event.extendedProps || {};

    const scheduleId =
        ext.schedule_id ||
        event.id;

    const aircraftId =
        ext.aircraft_id ||
        event.getResources()?.[0]?.id ||
        '';

    const flightId =
        ext.flight_id ||
        '';

    const status =
        ext.status ||
        'scheduled';

    const normalizedStatus =
        String(status).toLowerCase();

    const isCompleted =
        normalizedStatus === 'completed';

    const isDispatched =
        normalizedStatus === 'dispatched';

    selectedCalendarEvent =
        event;


    /*
    |--------------------------------------------------------------------------
    | ACTION BUTTONS
    |--------------------------------------------------------------------------
    */

    let actionButtons = '';


    /*
    |--------------------------------------------------------------------------
    | COMPLETED
    |--------------------------------------------------------------------------
    */

    if (isCompleted) {

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

        /*
        |--------------------------------------------------------------------------
        | DISPATCH / UN-DISPATCH
        |--------------------------------------------------------------------------
        */

        actionButtons += isDispatched
            ? `
                <button
                    type="button"
                    class="btn btn-danger py-2"
                    id="unDispatchEventButton"
                >
                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                    Un-Dispatch
                </button>
            `
            : `
                <button
                    type="button"
                    class="btn btn-success py-2"
                    id="dispatchEventButton"
                >
                    <i class="bi bi-send-fill me-2"></i>
                    Dispatch
                </button>
            `;


        /*
        |--------------------------------------------------------------------------
        | CHECK-IN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

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


    /*
    |--------------------------------------------------------------------------
    | SWEETALERT
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        title:
            `✈ ${event.title}`,

        width:
            'min(500px, calc(100vw - 24px))',

        padding:
            '1.25rem',

        customClass: {
            popup:
                'flight-action-popup'
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

                    <span class="
                        badge
                        ${isCompleted
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


                <div class="row">

                    <div class="col-6 mb-3">
                        <strong>Start:</strong>

                        <div class="text-muted small mt-1">
                            ${event.start
                ? formatDate(event.start)
                : '-'
            }
                        </div>
                    </div>


                    <div class="col-6 mb-3">
                        <strong>End:</strong>

                        <div class="text-muted small mt-1">
                            ${event.end
                ? formatDate(event.end)
                : '-'
            }
                        </div>
                    </div>

                </div>


                <div class="mb-3">
                    <strong>Time Zone:</strong>

                    <span class="badge bg-secondary ms-2">
                        ${getTimeZoneLabel()}
                    </span>
                </div>


                <hr>


                <div class="d-grid gap-2">
                    ${actionButtons}
                </div>

            </div>
        `,

        showConfirmButton:
            false,

        showCancelButton:
            true,

        cancelButtonText:
            'Close',


        /*
        |--------------------------------------------------------------------------
        | BUTTON HANDLERS
        |--------------------------------------------------------------------------
        */

        didOpen: () => {


            /*
            |--------------------------------------------------------------------------
            | DISPATCH
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'dispatchEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        Swal.close();

                        openDispatchAlert(
                            event
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | UN-DISPATCH
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'unDispatchEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        Swal.close();

                        openUnDispatchAlert(
                            event
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | EDIT
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'editEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        Swal.close();

                        const editUrl =
                            window.scheduleEditUrl.replace(
                                '__ID__',
                                scheduleId
                            );

                        window.location.href =
                            editUrl;
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'deleteEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        Swal.close();

                        openDeleteScheduleAlert(
                            event
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | CHECK-IN
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'checkInEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        handleCheckIn(
                            event
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | LOGBOOK
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'logbookEventButton'
                )
                ?.addEventListener(
                    'click',
                    () => {

                        openLogbooksPage(
                            scheduleId,
                            aircraftId,
                            flightId,
                            'logbook'
                        );
                    }
                );
        }
    });
}


/*
|--------------------------------------------------------------------------
| TIMEZONE LABEL
|--------------------------------------------------------------------------
*/

function getTimeZoneLabel() {

    const zone =
        TIMEZONE_OPTIONS.find(
            item =>
                item.value === selectedTimeZone
        );


    return zone
        ? zone.label
        : selectedTimeZone;
}


/*
|--------------------------------------------------------------------------
| CHECK-IN
|--------------------------------------------------------------------------
*/

async function handleCheckIn(event) {

    const button =
        document.getElementById(
            'checkInEventButton'
        );


    if (button) {
        button.disabled = true;
    }


    try {

        const ext =
            event.extendedProps || {};


        /*
        |--------------------------------------------------------------------------
        | IDENTIFIERS
        |--------------------------------------------------------------------------
        */

        const scheduleId =
            ext.schedule_id ||
            event.id;


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


        /*
        |--------------------------------------------------------------------------
        | CURRENT AIRCRAFT METERS
        |--------------------------------------------------------------------------
        */

        const hobbsOut =
            safeNumber(
                ext.current_hobbs
            );


        const tachOut =
            safeNumber(
                ext.current_tach
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | CURRENT STATUS
        |--------------------------------------------------------------------------
        */

        const currentStatus =
            String(
                ext.status ||
                'scheduled'
            ).toLowerCase();


        /*
        |--------------------------------------------------------------------------
        | ALREADY DISPATCHED
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | ASK TO DISPATCH
        |--------------------------------------------------------------------------
        */

        const confirm =
            await Swal.fire({

                icon:
                    'info',

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

                showCancelButton:
                    true,

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


        if (
            !confirm.isConfirmed
        ) {

            if (button) {
                button.disabled = false;
            }

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | DISPATCHING
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title:
                'Dispatching Flight...',

            html:
                'Please wait while the flight is being dispatched.',

            allowOutsideClick:
                false,

            allowEscapeKey:
                false,

            didOpen:
                () => {

                    Swal.showLoading();

                }

        });


        /*
        |--------------------------------------------------------------------------
        | DISPATCH REQUEST
        |--------------------------------------------------------------------------
        */

        const dispatchResponse =
            await fetch(
                window.dispatchStoreUrl,
                {

                    method:
                        'POST',

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


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        const dispatchData =
            await dispatchResponse.json();


        /*
        |--------------------------------------------------------------------------
        | FAILURE
        |--------------------------------------------------------------------------
        */

        if (
            !dispatchResponse.ok ||
            !dispatchData.success
        ) {

            throw new Error(
                dispatchData.message ||
                'Unable to dispatch the flight.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        await Swal.fire({

            icon:
                'success',

            title:
                'Flight Dispatched',

            html: `

                Dispatch No:

                <strong>
                    ${dispatchData.dispatch_no ||
                '-'
                }
                </strong>

                <br><br>

                Continuing to Check-In...

            `,

            timer:
                1500,

            showConfirmButton:
                false,

            allowOutsideClick:
                false

        });


        /*
        |--------------------------------------------------------------------------
        | CONTINUE TO CHECK-IN
        |--------------------------------------------------------------------------
        */

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

            icon:
                'error',

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


/*
|--------------------------------------------------------------------------
| OPEN CHECK-IN PAGE
|--------------------------------------------------------------------------
*/

function openCheckInPage(
    scheduleId,
    aircraftId,
    flightId
) {

    openLogbooksPage(
        scheduleId,
        aircraftId,
        flightId,
        'checkin'
    );

}


/*
|--------------------------------------------------------------------------
| OPEN LOGBOOKS PAGE
|--------------------------------------------------------------------------
*/

function openLogbooksPage(
    scheduleId,
    aircraftId,
    flightId,
    mode
) {

    const url =
        new URL(
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
        mode
    );


    /*
     * Pass the selected timezone to the Logbooks page.
     *
     * This allows the Logbook page to use the same
     * timezone if required.
     */
    url.searchParams.set(
        'timezone',
        selectedTimeZone
    );


    window.location.href =
        url.toString();

}


/*
|--------------------------------------------------------------------------
| OPEN LOGBOOK MODAL
|--------------------------------------------------------------------------
*/

function openLogbookModal(
    event,
    mode = 'checkin'
) {

    /*
     * Only prepare the modal for Check-In.
     */
    if (
        mode !== 'checkin'
    ) {
        return;
    }


    const modalElement =
        document.getElementById(
            'createLogbookModal'
        );


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


    /*
    |--------------------------------------------------------------------------
    | EVENT INFORMATION
    |--------------------------------------------------------------------------
    */

    const ext =
        event.extendedProps ||
        {};


    const scheduleId =
        ext.schedule_id ||
        event.id;


    const aircraftId =
        ext.aircraft_id ||
        event.getResources()?.[0]?.id ||
        '';


    const flightId =
        ext.flight_id ||
        aircraftId;


    /*
    |--------------------------------------------------------------------------
    | HIDDEN FIELDS
    |--------------------------------------------------------------------------
    */

    const scheduleInput =
        document.getElementById(
            'schedule_id'
        );


    const flightInput =
        document.getElementById(
            'flight_id'
        );


    if (scheduleInput) {

        scheduleInput.value =
            scheduleId;

    }


    if (flightInput) {

        flightInput.value =
            flightId;

    }


    /*
    |--------------------------------------------------------------------------
    | AIRCRAFT SELECT
    |--------------------------------------------------------------------------
    */

    const aircraftSelect =
        document.getElementById(
            'aircraftSelect'
        );


    if (
        aircraftSelect &&
        aircraftId
    ) {

        const option =
            [
                ...aircraftSelect.options
            ].find(
                opt =>
                    String(opt.value) ===
                    String(aircraftId)
            );


        if (option) {

            aircraftSelect.value =
                aircraftId;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FLIGHT DATE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT use getUTCFullYear(), getUTCMonth()
    | or getUTCDate() here.
    |
    | We want the calendar date in the user's
    | selected timezone.
    |
    */

    const flightDateInput =
        modalElement.querySelector(
            'input[name="flight_date"]'
        );


    if (
        flightDateInput &&
        event.start
    ) {

        flightDateInput.value =
            formatDateOnly(
                event.start
            );

    }


    /*
    |--------------------------------------------------------------------------
    | TIMEZONE FIELD
    |--------------------------------------------------------------------------
    |
    | If a timezone input exists in the modal,
    | populate it automatically.
    |
    */

    const timezoneInput =
        modalElement.querySelector(
            '[name="timezone"], #timezone'
        );


    if (timezoneInput) {

        timezoneInput.value =
            selectedTimeZone;

    }


    /*
    |--------------------------------------------------------------------------
    | OPEN MODAL
    |--------------------------------------------------------------------------
    */

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    modal.show();

}


/*
|--------------------------------------------------------------------------
| DISPATCH ALERT
|--------------------------------------------------------------------------
*/

function openDispatchAlert(event) {

    const start =
        formatDate(
            event.start
        );


    const end =
        formatDate(
            event.end
        );


    const ext =
        event.extendedProps ||
        {};


    const currentHobbs =
        safeNumber(
            ext.current_hobbs
        );


    const currentTach =
        safeNumber(
            ext.current_tach
        );


    Swal.fire({

        title:
            `✈ ${event.title}`,

        width:
            750,

        heightAuto:
            false,

        customClass: {

            popup:
                'dispatch-swal-popup',

            htmlContainer:
                'dispatch-swal-body'

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
                            value="${ext.departure || ''}"
                        >

                    </div>


                    <div class="col-6 mb-2">

                        <label class="form-label">
                            Destination
                        </label>

                        <input
                            id="swalDestination"
                            class="form-control"
                            placeholder="To"
                            value="${ext.destination || ''}"
                        >

                    </div>


                    <div class="col-6 mb-2">

                        <label class="form-label">
                            Pilot
                        </label>

                        <input
                            id="swalPilot"
                            class="form-control"
                            value="${ext.pilot || ''}"
                        >

                    </div>


                    <div class="col-6 mb-2">

                        <label class="form-label">
                            Co-Pilot
                        </label>

                        <input
                            id="swalCoPilot"
                            class="form-control"
                            value="${ext.copilot || ''}"
                        >

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


                    <div class="col-12 mb-2">

                        <b>Time Zone:</b><br>

                        <span class="badge bg-secondary">
                            ${getTimeZoneLabel()}
                        </span>

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

        showCancelButton:
            true,

        confirmButtonText:
            'Dispatch',

        cancelButtonText:
            'Close',

        confirmButtonColor:
            '#198754'

    }).then(
        result => {

            if (
                !result.isConfirmed
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | GET FORM VALUES
            |--------------------------------------------------------------------------
            */

            const departure =
                document.getElementById(
                    'swalDeparture'
                )?.value || '';


            const destination =
                document.getElementById(
                    'swalDestination'
                )?.value || '';


            const pilot =
                document.getElementById(
                    'swalPilot'
                )?.value || '';


            const copilot =
                document.getElementById(
                    'swalCoPilot'
                )?.value || '';


            const remarks =
                document.getElementById(
                    'swalRemarks'
                )?.value || '';


            /*
            |--------------------------------------------------------------------------
            | UPDATE EVENT
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

            openDispatchModal(
                event
            );


            const modalElement =
                document.getElementById(
                    'dispatchModal'
                );


            if (modalElement) {

                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );


                modal.show();

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| DELETE SCHEDULE
|--------------------------------------------------------------------------
*/

function openDeleteScheduleAlert(event) {

    const ext =
        event.extendedProps ||
        {};


    const scheduleId =
        ext.schedule_id ||
        event.id;


    Swal.fire({

        icon:
            'warning',

        title:
            'Delete Schedule?',

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

        showCancelButton:
            true,

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

    }).then(
        async result => {

            if (
                !result.isConfirmed
            ) {
                return;
            }


            Swal.fire({

                title:
                    'Deleting Schedule...',

                html:
                    'Please wait while the schedule is deleted.',

                allowOutsideClick:
                    false,

                allowEscapeKey:
                    false,

                didOpen:
                    () => {

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
                    await fetch(
                        deleteUrl,
                        {

                            method:
                                'DELETE',

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

                        }
                    );


                const data =
                    await response
                        .json()
                        .catch(
                            () => ({})
                        );


                if (
                    !response.ok
                ) {

                    throw new Error(
                        data.message ||
                        'Unable to delete the schedule.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | REMOVE EVENT
                |--------------------------------------------------------------------------
                */

                event.remove();


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Schedule Deleted',

                    text:
                        data.message ||
                        'Schedule deleted successfully.',

                    timer:
                        1800,

                    showConfirmButton:
                        false

                });


            } catch (error) {

                console.error(
                    'Delete schedule error:',
                    error
                );


                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Delete Failed',

                    text:
                        error.message ||
                        'Unable to delete the schedule.'

                });

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| UN-DISPATCH
|--------------------------------------------------------------------------
*/

function openUnDispatchAlert(event) {

    const ext =
        event.extendedProps ||
        {};


    const scheduleId =
        ext.schedule_id ||
        event.id;


    const aircraftId =
        ext.aircraft_id ||
        event.getResources()?.[0]?.id ||
        '';


    const dispatchNo =
        ext.dispatch_no ||
        'N/A';


    Swal.fire({

        icon:
            'warning',

        title:
            'Un-Dispatch Aircraft?',

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

        showCancelButton:
            true,

        confirmButtonText:
            '<i class="bi bi-arrow-counterclockwise me-2"></i>Un-Dispatch',

        cancelButtonText:
            'Cancel',

        confirmButtonColor:
            '#dc3545',

        reverseButtons:
            true,

        showLoaderOnConfirm:
            true,


        preConfirm:
            async () => {

                try {

                    const response =
                        await fetch(
                            window.unDispatchUrl,
                            {

                                method:
                                    'POST',

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
                                            ?.getAttribute(
                                                'content'
                                            )

                                },

                                body:
                                    JSON.stringify({

                                        schedule_id:
                                            scheduleId,

                                        aircraft_id:
                                            aircraftId

                                    })

                            }
                        );


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

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

    }).then(
        result => {

            if (
                !result.isConfirmed
            ) {
                return;
            }


            Swal.fire({

                icon:
                    'success',

                title:
                    'Un-Dispatched',

                text:
                    result.value?.message ||
                    'Aircraft has been un-dispatched successfully.',

                confirmButtonColor:
                    '#4886a3'

            }).then(
                () => {

                    /*
                    |--------------------------------------------------------------------------
                    | REFRESH CALENDAR
                    |--------------------------------------------------------------------------
                    */

                    if (
                        calendar
                    ) {

                        calendar.refetchEvents();

                    } else {

                        window.location.reload();

                    }

                }
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| RENDER TEMPORARY SELECTION
|--------------------------------------------------------------------------
*/

function renderTemporarySelection() {

    if (!calendar) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE PREVIOUS TEMPORARY EVENTS
    |--------------------------------------------------------------------------
    */

    calendar
        .getEvents()
        .filter(event =>
            event.extendedProps?.temporarySelection === true
        )
        .forEach(event => event.remove());

    /*
    |--------------------------------------------------------------------------
    | NO SELECTION
    |--------------------------------------------------------------------------
    */

    if (
        !selectedResourceId ||
        selectedSlots.length === 0
    ) {
        updateScheduleButton();
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TEMPORARY BACKGROUND SELECTIONS
    |--------------------------------------------------------------------------
    */

    selectedSlots.forEach(slot => {

        calendar.addEvent({

            id:
                `temporary-${selectedResourceId}-${slot.start.getTime()}`,

            resourceId: selectedResourceId,

            start: slot.start,
            end: slot.end,

            title: '',

            display: 'background',

            backgroundColor: '#198754',

            classNames: [
                'temporary-selection'
            ],

            editable: false,

            extendedProps: {
                temporarySelection: true,
                isTemporarySelection: true
            }
        });

    });

    updateScheduleButton();
}


/*
|--------------------------------------------------------------------------
| CLEAR TEMPORARY SELECTION
|--------------------------------------------------------------------------
*/

function clearTemporarySelection() {

    if (calendar) {

        calendar
            .getEvents()
            .filter(
                event =>
                    event.extendedProps
                        ?.temporarySelection
            )
            .forEach(
                event =>
                    event.remove()
            );

    }


    selectedSlots = [];

    selectedResourceId = null;


    updateScheduleButton();

}


/*
|--------------------------------------------------------------------------
| SHOW SCHEDULE CONFIRMATION
|--------------------------------------------------------------------------
*/

function showScheduleConfirmation(
    flightId,
    aircraftTitle
) {

    if (
        selectedSlots.length === 0
    ) {
        return;
    }


    /*
     * Ensure chronological order.
     */
    selectedSlots.sort(
        (a, b) =>
            a.start.getTime() -
            b.start.getTime()
    );


    /*
     * Check continuity.
     */
    for (
        let i = 1;
        i < selectedSlots.length;
        i++
    ) {

        const previous =
            selectedSlots[i - 1];


        const current =
            selectedSlots[i];


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


    const firstSlot =
        selectedSlots[0];


    const lastSlot =
        selectedSlots[
        selectedSlots.length - 1
        ];


    const start =
        firstSlot.start;


    const end =
        lastSlot.end;


    const totalMinutes =
        selectedSlots.length * 30;


    const hours =
        Math.floor(
            totalMinutes / 60
        );


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

        durationText =
            '30 minutes';

    }


    Swal.fire({

        title:
            'Schedule Aircraft',

        html: `

            <div class="text-start">

                <p class="mb-2">

                    <strong>Aircraft:</strong>
                    ${aircraftTitle}

                </p>


                <p class="mb-2">

                    <strong>Start:</strong>
                    ${formatDate(start)}

                </p>


                <p class="mb-2">

                    <strong>End:</strong>
                    ${formatDate(end)}

                </p>


                <p class="mb-2">

                    <strong>Duration:</strong>
                    ${durationText}

                </p>


                <p class="mb-2">

                    <strong>Time Zone:</strong>

                    <span class="badge bg-secondary">
                        ${getTimeZoneLabel()}
                    </span>

                </p>


                <p class="mb-0 text-muted">

                    <small>

                        ${selectedSlots.length}
                        × 30-minute slot${selectedSlots.length > 1 ? 's' : ''}

                    </small>

                </p>

            </div>

        `,

        icon:
            'question',

        showCancelButton:
            true,

        confirmButtonText:
            'Schedule',

        cancelButtonText:
            'Cancel',

        confirmButtonColor:
            '#198754'

    }).then(
        result => {

            if (
                result.isConfirmed
            ) {

                /*
                 * start/end are absolute Date objects.
                 * createSchedule() converts them to ISO/UTC.
                 */
                createSchedule(
                    flightId,
                    start,
                    end
                );


                clearTemporarySelection();

            }

            /*
             * Keep selection if cancelled.
             */

        }
    );

}


/*
|--------------------------------------------------------------------------
| UPDATE SCHEDULE BUTTON
|--------------------------------------------------------------------------
*/

function updateScheduleButton() {

    if (!calendar) {
        return;
    }


    const button =
        calendarElQuery(
            '.fc-scheduleSelected-button'
        );


    if (!button) {
        return;
    }


    const count =
        selectedSlots.length;


    if (
        count === 0
    ) {

        button.disabled =
            true;


        button.classList.add(
            'disabled'
        );


        button.innerHTML =
            '<i class="bi bi-calendar-plus me-1"></i> Schedule';


        return;
    }


    button.disabled =
        false;


    button.classList.remove(
        'disabled'
    );


    button.innerHTML =
        `<i class="bi bi-calendar-check me-1"></i>
         Schedule (${count})`;

}


/*
|--------------------------------------------------------------------------
| CALENDAR ELEMENT QUERY
|--------------------------------------------------------------------------
*/

function calendarElQuery(selector) {

    const calendarElement =
        document.getElementById(
            'calendar'
        );


    if (!calendarElement) {
        return null;
    }


    return calendarElement.querySelector(
        selector
    );

}


/*
|--------------------------------------------------------------------------
| CHECK FOR CONFLICT
|--------------------------------------------------------------------------
*/

function hasConflict(
    resourceId,
    start,
    end
) {

    if (!calendar) {
        return false;
    }


    return calendar
        .getEvents()
        .some(
            event => {

                /*
                 * Ignore temporary selections.
                 */
                if (
                    event.extendedProps
                        ?.temporarySelection
                ) {

                    return false;

                }


                if (
                    !event.start ||
                    !event.end
                ) {

                    return false;

                }


                const resources =
                    event.getResources();


                if (
                    !resources ||
                    resources.length === 0
                ) {

                    return false;

                }


                const sameAircraft =
                    resources.some(
                        resource =>
                            resource &&
                            String(resource.id) ===
                            String(resourceId)
                    );


                if (!sameAircraft) {
                    return false;
                }


                /*
                 * Date comparisons use absolute instants.
                 * This is independent of display timezone.
                 */
                return (
                    start < event.end &&
                    end > event.start
                );

            }
        );

}


/*
|--------------------------------------------------------------------------
| CREATE SCHEDULE
|--------------------------------------------------------------------------
|
| IMPORTANT:
| start/end are Date objects representing the selected
| timezone's wall-clock selection.
|
| toISOString() converts the instant to UTC.
|
|--------------------------------------------------------------------------
*/

function createSchedule(
    flightId,
    start,
    end
) {

    fetch(
        '/calendar/create',
        {

            method:
                'POST',

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
                        .content

            },

            body:
                JSON.stringify({

                    flight_id:
                        flightId,

                    /*
                     * Always send UTC to backend.
                     */
                    start:
                        start.toISOString(),

                    end:
                        end.toISOString(),

                    /*
                     * Optional informational value.
                     * Backend can ignore this if not needed.
                     */
                    timezone:
                        selectedTimeZone

                })

        }
    )
        .then(
            response => {

                if (!response.ok) {

                    return response
                        .json()
                        .then(
                            data => {

                                throw new Error(
                                    data.message ||
                                    'Unable to save schedule.'
                                );

                            }
                        )
                        .catch(
                            () => {

                                throw new Error(
                                    'Unable to save schedule.'
                                );

                            }
                        );

                }


                return response.json();

            }
        )
        .then(
            data => {

                if (data.success) {

                    if (calendar) {

                        calendar.refetchEvents();

                    }


                    if (aircraftCalendar) {

                        aircraftCalendar.refetchEvents();

                    }


                    Swal.fire({

                        title:
                            'Saved',

                        text:
                            'Flight scheduled successfully',

                        icon:
                            'success',

                        timer:
                            1200,

                        showConfirmButton:
                            false

                    });

                } else {

                    Swal.fire(
                        'Conflict',
                        data.message ||
                        'Unable to save',
                        'error'
                    );

                }

            }
        )
        .catch(
            error => {

                console.error(
                    'Create schedule error:',
                    error
                );


                Swal.fire(
                    'Server Error',
                    error.message ||
                    'Please try again',
                    'error'
                );

            }
        );

}


/*
|--------------------------------------------------------------------------
| UPDATE SCHEDULE
|--------------------------------------------------------------------------
*/

function updateSchedule(info) {

    if (
        !info.event.start ||
        !info.event.end
    ) {

        info.revert();

        Swal.fire(
            'Error',
            'Invalid schedule time.',
            'error'
        );

        return;
    }


    /*
     * FullCalendar Date objects represent the
     * actual instant. ISO conversion keeps the
     * backend storage in UTC.
     */
    const start =
        info.event.start.toISOString();


    const end =
        info.event.end.toISOString();


    fetch(
        `/calendar/update/${info.event.id}`,
        {

            method:
                'POST',

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
                        .content

            },

            body:
                JSON.stringify({

                    start:
                        start,

                    end:
                        end,

                    timezone:
                        selectedTimeZone

                })

        }
    )
        .then(
            response =>
                response.json()
        )
        .then(
            data => {

                if (
                    !data.success
                ) {

                    info.revert();


                    Swal.fire(
                        'Error',
                        data.message ||
                        'Update failed',
                        'error'
                    );

                }

            }
        )
        .catch(
            error => {

                console.error(
                    'Update schedule error:',
                    error
                );


                info.revert();


                Swal.fire(
                    'Server Error',
                    'Update failed. Please try again.',
                    'error'
                );

            }
        );

}


/*
|--------------------------------------------------------------------------
| OPEN AIRCRAFT CALENDAR
|--------------------------------------------------------------------------
*/

window.openAircraftCalendar =
    function (
        flightId,
        title
    ) {

        const aircraftTitle =
            document.getElementById(
                'aircraftTitle'
            );


        if (aircraftTitle) {

            aircraftTitle.innerText =
                title +
                ' Calendar';

        }


        const modalEl =
            document.getElementById(
                'aircraftCalendarModal'
            );


        if (!modalEl) {

            console.error(
                '#aircraftCalendarModal was not found.'
            );

            return;

        }


        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalEl
            );


        modal.show();


        /*
        |--------------------------------------------------------------------------
        | CLEANUP WHEN MODAL CLOSES
        |--------------------------------------------------------------------------
        */

        modalEl.addEventListener(
            'hidden.bs.modal',
            function () {

                if (
                    aircraftCalendar
                ) {

                    aircraftCalendar.destroy();

                    aircraftCalendar =
                        null;

                }


                document.body.classList.remove(
                    'modal-open'
                );


                document
                    .querySelectorAll(
                        '.modal-backdrop'
                    )
                    .forEach(
                        el =>
                            el.remove()
                    );


                document.body.style.overflow =
                    '';


                document.body.style.paddingRight =
                    '';

            },
            {
                once:
                    true
            }
        );


        /*
        |--------------------------------------------------------------------------
        | WAIT FOR MODAL TO RENDER
        |--------------------------------------------------------------------------
        */

        setTimeout(
            () => {

                if (
                    aircraftCalendar
                ) {

                    aircraftCalendar.destroy();

                }


                const aircraftCalendarEl =
                    document.getElementById(
                        'aircraftCalendar'
                    );


                if (!aircraftCalendarEl) {

                    console.error(
                        '#aircraftCalendar was not found.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | AIRCRAFT CALENDAR
                |--------------------------------------------------------------------------
                */

                aircraftCalendar =
                    new Calendar(
                        aircraftCalendarEl,
                        {

                            plugins: [
                                interactionPlugin,
                                timeGridPlugin
                            ],


                            /*
                            |--------------------------------------------------------------------------
                            | SAME SELECTED TIMEZONE
                            |--------------------------------------------------------------------------
                            */

                            timeZone:
                                selectedTimeZone,


                            initialView:
                                'timeGridWeek',


                            /*
            |--------------------------------------------------------------------------
            | 24-HOUR TIME FORMAT
            |--------------------------------------------------------------------------
            */
                            locale:
                                'en-GB',

                            slotLabelFormat: {
                                hour:
                                    '2-digit',
                                minute:
                                    '2-digit',
                                hour12:
                                    false
                            },

                            eventTimeFormat: {
                                hour:
                                    '2-digit',
                                minute:
                                    '2-digit',
                                hour12:
                                    false
                            },

                            /*
                            |--------------------------------------------------------------------------
                            | SELECTION
                            |--------------------------------------------------------------------------
                            */
                            selectable:
                                true,

                            selectMirror:
                                true,

                            /*
                            |--------------------------------------------------------------------------
                            | CALENDAR HEIGHT
                            |--------------------------------------------------------------------------
                            */
                            height:
                                'auto',

                            /*
                            |--------------------------------------------------------------------------
                            | EVENTS
                            |--------------------------------------------------------------------------
                            */
                            events:
                                function (
                                    fetchInfo,
                                    successCallback,
                                    failureCallback
                                ) {
                                    fetch(
                                        `/calendar/schedules?flight_id=${encodeURIComponent(flightId)}`
                                    )
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(
                                                    'Unable to load schedules.'
                                                );
                                            }

                                            return response.json();
                                        })
                                        .then(data => {
                                            successCallback(data);
                                        })
                                        .catch(error => {
                                            console.error(
                                                'Aircraft calendar events error:',
                                                error
                                            );

                                            failureCallback(error);
                                        });
                                },

                            /*
                            |--------------------------------------------------------------------------
                            | CREATE FROM AIRCRAFT CALENDAR
                            |--------------------------------------------------------------------------
                            */
                            select:
                                function (info) {

                                    const start =
                                        new Date(info.start);

                                    const end =
                                        new Date(info.end);
                                    /*
                                    |--------------------------------------------------------------------------
                                    | OPEN SCHEDULE MODAL
                                    |--------------------------------------------------------------------------
                                    */
                                    Swal.fire({
                                        title:
                                            'Schedule Aircraft',

                                        html: `
                                            <div class="text-start">

                                                <p class="mb-2">
                                                    <strong>Start:</strong>
                                                    ${formatDate(start)}
                                                </p>

                                                <p class="mb-2">
                                                    <strong>End:</strong>
                                                    ${formatDate(end)}
                                                </p>

                                                <p class="mb-0">
                                                    <strong>Time Zone:</strong>
                                                    <span class="badge bg-secondary">
                                                        ${getTimeZoneLabel()}
                                                    </span>
                                                </p>

                                            </div>
                                        `,

                                        icon:
                                            'question',

                                        showCancelButton:
                                            true,

                                        confirmButtonText:
                                            'Save',

                                        cancelButtonText:
                                            'Cancel',

                                        confirmButtonColor:
                                            '#198754'

                                    }).then(
                                        async result => {

                                            /*
                                            |--------------------------------------------------------------------------
                                            | ALWAYS CLEAR SELECTION
                                            |--------------------------------------------------------------------------
                                            */
                                            aircraftCalendar.unselect();

                                            if (!result.isConfirmed) {
                                                return;
                                            }

                                            try {

                                                /*
                                                |--------------------------------------------------------------------------
                                                | SAVE IN UTC
                                                |--------------------------------------------------------------------------
                                                */
                                                const response =
                                                    await fetch(
                                                        '/calendar/create',
                                                        {
                                                            method:
                                                                'POST',

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
                                                                        .content
                                                            },

                                                            body:
                                                                JSON.stringify({
                                                                    flight_id:
                                                                        flightId,

                                                                    start:
                                                                        start.toISOString(),

                                                                    end:
                                                                        end.toISOString(),

                                                                    timezone:
                                                                        selectedTimeZone
                                                                })
                                                        }
                                                    );

                                                const data =
                                                    await response.json();

                                                if (
                                                    !response.ok ||
                                                    !data.success
                                                ) {

                                                    Swal.fire(
                                                        'Error',
                                                        data.message ||
                                                        'Unable to save',
                                                        'error'
                                                    );

                                                    return;
                                                }

                                                /*
                                                |--------------------------------------------------------------------------
                                                | REFRESH CALENDARS
                                                |--------------------------------------------------------------------------
                                                */
                                                if (
                                                    aircraftCalendar
                                                ) {
                                                    aircraftCalendar.refetchEvents();
                                                }

                                                if (
                                                    calendar
                                                ) {
                                                    calendar.refetchEvents();
                                                }

                                                Swal.fire({
                                                    title:
                                                        'Saved',

                                                    text:
                                                        'Flight scheduled successfully',

                                                    icon:
                                                        'success',

                                                    timer:
                                                        1200,

                                                    showConfirmButton:
                                                        false
                                                });

                                            } catch (error) {

                                                console.error(
                                                    'Aircraft schedule error:',
                                                    error
                                                );

                                                Swal.fire(
                                                    'Error',
                                                    error.message ||
                                                    'Something went wrong',
                                                    'error'
                                                );
                                            }
                                        }
                                    );
                                },

                            /*
                            |--------------------------------------------------------------------------
                            | EVENT CLICK
                            |--------------------------------------------------------------------------
                            */
                            eventClick:
                                function (info) {
                                    openFlightEventAlert(
                                        info.event
                                    );
                                }
                        }
                    );

                aircraftCalendar.render();

            },
            300
        );

    };


/*
|--------------------------------------------------------------------------
| EXPOSE TIMEZONE CHANGE FUNCTION
|--------------------------------------------------------------------------
|
| Useful if another part of your Blade page needs to change
| the calendar timezone programmatically.
|
|--------------------------------------------------------------------------
*/

window.changeFlightCalendarTimeZone =
    changeCalendarTimeZone;


/*
|--------------------------------------------------------------------------
| EXPOSE CURRENT TIMEZONE
|--------------------------------------------------------------------------
*/

window.getFlightCalendarTimeZone =
    function () {

        return selectedTimeZone;

    };
