import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
import timeGridPlugin from '@fullcalendar/timegrid';
import luxon3Plugin from '@fullcalendar/luxon3';


/* ==========================================================================
   CONSTANTS
   ========================================================================== */

const Swal = window.Swal;

const SLOT_MINUTES = 30;
const SLOT_MS = SLOT_MINUTES * 60 * 1000;

const TIMEZONE_STORAGE_PREFIX = 'flight_calendar_timezone';
const DEFAULT_TIMEZONE = 'America/Denver';

const TIMEZONE_OPTIONS = [
    { value: 'America/Denver', label: 'Denver / El Paso (MT)' },
    { value: 'UTC', label: 'UTC' },
    { value: 'Africa/Nairobi', label: 'Nairobi (EAT)' },
    { value: 'Africa/Johannesburg', label: 'Johannesburg (SAST)' },
    { value: 'Europe/London', label: 'London' },
    { value: 'Europe/Paris', label: 'Paris' },
    { value: 'America/New_York', label: 'New York (ET)' },
    { value: 'America/Chicago', label: 'Chicago (CT)' },
    { value: 'America/Los_Angeles', label: 'Los Angeles (PT)' },
    { value: 'Asia/Dubai', label: 'Dubai (GST)' },
    { value: 'Asia/Kolkata', label: 'Mumbai / Delhi (IST)' },
    { value: 'Asia/Singapore', label: 'Singapore' },
    { value: 'Australia/Sydney', label: 'Sydney' }
];

const GRADIENT_BUTTON_STYLE =
    'background: linear-gradient(135deg, #4886a3, #2f6078); border: none; border-radius: 50px; font-weight: 600;';


/* ==========================================================================
   GLOBAL STATE
   ========================================================================== */

let calendar = null;
let aircraftCalendar = null;

let selectedSlots = [];
let selectedResourceId = null;


/* ==========================================================================
   SMALL HELPERS
   ========================================================================== */

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    }[character]));
}

function safeNumber(value) {
    const number = parseFloat(value);
    return Number.isNaN(number) ? 0 : number;
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

/**
 * JSON request helper. Throws an Error (with .status and .data) when the
 * response is not OK, using the server's `message` when there is one.
 */
async function apiRequest(url, { method = 'GET', body } = {}) {

    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken()
    };

    if (body !== undefined) {
        headers['Content-Type'] = 'application/json';
    }

    const response = await fetch(url, {
        method,
        headers,
        body: body !== undefined ? JSON.stringify(body) : undefined
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(data.message || `Request failed (${response.status}).`);
        error.status = response.status;
        error.data = data;
        throw error;
    }

    return data;
}

function showModal(element) {
    window.bootstrap?.Modal.getOrCreateInstance(element).show();
}

function refetchAllCalendars() {
    calendar?.refetchEvents();
    aircraftCalendar?.refetchEvents();
}

/**
 * Common identifiers for an event. `getResources` only exists when the
 * resource plugin is active, so it is called defensively.
 */
function getEventIds(event) {

    const ext = event.extendedProps || {};

    const aircraftId =
        ext.aircraft_id ||
        event.getResources?.()?.[0]?.id ||
        '';

    return {
        ext,
        scheduleId: ext.schedule_id || event.id,
        aircraftId,
        flightId: ext.flight_id || aircraftId,
        pilotId: ext.pilot_id || ''
    };
}


/* ==========================================================================
   TIMEZONE: RESOLUTION + PERSISTENCE
   Priority: server preference -> this browser (per user) -> device -> default
   ========================================================================== */

/* Namespaced by Laravel user so two users on one browser don't share a choice */
function getTimezoneStorageKey() {
    return `${TIMEZONE_STORAGE_PREFIX}:user:${window.flightCalendarUserId ?? 'guest'}`;
}

function isValidTimeZone(timeZone) {

    if (!timeZone || typeof timeZone !== 'string') {
        return false;
    }

    try {
        new Intl.DateTimeFormat('en-US', { timeZone });
        return true;
    } catch {
        return false;
    }
}

function getDeviceTimeZone() {
    try {
        return Intl.DateTimeFormat().resolvedOptions().timeZone || null;
    } catch {
        return null;
    }
}

/* Valid zones missing from TIMEZONE_OPTIONS are added so the dropdown can show them */
function ensureTimeZoneOption(timeZone, label = null) {

    if (!isValidTimeZone(timeZone)) {
        return false;
    }

    if (!TIMEZONE_OPTIONS.some(zone => zone.value === timeZone)) {
        TIMEZONE_OPTIONS.unshift({
            value: timeZone,
            label: label || timeZone.replace(/_/g, ' ')
        });
    }

    return true;
}

function getInitialTimeZone() {

    // 1. Preference stored on the user's account
    if (ensureTimeZoneOption(window.flightCalendarUserTimeZone)) {
        return window.flightCalendarUserTimeZone;
    }

    // 2. Preference stored in this browser for this user
    let saved = null;

    try {
        saved = localStorage.getItem(getTimezoneStorageKey());
    } catch { /* storage unavailable */ }

    if (ensureTimeZoneOption(saved)) {
        return saved;
    }

    // 3. First visit: the device's timezone
    const device = getDeviceTimeZone();

    if (ensureTimeZoneOption(device, `${device?.replace(/_/g, ' ')} (Device)`)) {
        return device;
    }

    // 4. Fallback
    return DEFAULT_TIMEZONE;
}

function saveTimeZonePreference(timeZone) {

    try {
        localStorage.setItem(getTimezoneStorageKey(), timeZone);
    } catch { /* storage unavailable */ }

    if (!window.userTimezoneUrl) {
        return;
    }

    apiRequest(window.userTimezoneUrl, {
        method: 'POST',
        body: { timezone: timeZone }
    }).catch(error => console.warn('Could not save timezone preference:', error));
}

let selectedTimeZone = getInitialTimeZone();

function getTimeZoneLabel() {
    const zone = TIMEZONE_OPTIONS.find(item => item.value === selectedTimeZone);
    return zone ? zone.label : selectedTimeZone;
}

/* e.g. "UTC+03:00" */
function getUtcOffsetLabel(timeZone = selectedTimeZone) {

    try {
        const name = new Intl.DateTimeFormat('en-US', {
            timeZone,
            timeZoneName: 'longOffset'
        })
            .formatToParts(new Date())
            .find(part => part.type === 'timeZoneName')?.value;

        if (!name) {
            return '';
        }

        return name === 'GMT' ? 'UTC+00:00' : name.replace('GMT', 'UTC');

    } catch {
        return '';
    }
}


/* ==========================================================================
   TIMEZONE: FORMATTING
   ========================================================================== */

function formatDate(date) {

    if (!date) {
        return 'N/A';
    }

    return new Intl.DateTimeFormat('en-GB', {
        timeZone: selectedTimeZone,
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hourCycle: 'h23'
    }).format(new Date(date));
}

/* Calendar date (YYYY-MM-DD) in the selected timezone, e.g. for logbook flight_date */
function formatDateOnly(date) {

    if (!date) {
        return '';
    }

    const values = {};

    new Intl.DateTimeFormat('en-CA', {
        timeZone: selectedTimeZone,
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    })
        .formatToParts(new Date(date))
        .forEach(part => {
            if (part.type !== 'literal') {
                values[part.type] = part.value;
            }
        });

    return `${values.year}-${values.month}-${values.day}`;
}

/* FullCalendar custom formatter: 0930 style, in the calendar's timezone */
function formatHHmm(arg) {
    const hours = String(arg.date.hour).padStart(2, '0');
    const minutes = String(arg.date.minute).padStart(2, '0');
    return `${hours}${minutes}`;
}

function formatDuration(totalMinutes) {

    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    const parts = [];

    if (hours > 0) {
        parts.push(`${hours} hr${hours > 1 ? 's' : ''}`);
    }

    if (minutes > 0) {
        parts.push(`${minutes} min`);
    }

    return parts.join(' ') || `${SLOT_MINUTES} minutes`;
}

/**
 * Safety net: an event date without "Z" or an offset would be read by
 * FullCalendar in the *display* timezone. The backend stores UTC, so treat
 * such strings as UTC. Strings that already carry a zone are untouched.
 */
const NAIVE_DATETIME = /^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}(:\d{2}(\.\d+)?)?$/;

function toUtcIso(value) {

    if (typeof value === 'string' && NAIVE_DATETIME.test(value.trim())) {
        return `${value.trim().replace(' ', 'T')}Z`;
    }

    return value;
}

function normalizeEventDates(eventData) {

    const transformed = { ...eventData };

    if (transformed.start !== undefined) {
        transformed.start = toUtcIso(transformed.start);
    }

    if (transformed.end !== undefined) {
        transformed.end = toUtcIso(transformed.end);
    }

    return transformed;
}


/* ==========================================================================
   CLOCKS
   The UTC button is always UTC. The world clock follows the selected
   timezone when a #worldClockTimezone element exists, otherwise it shows UTC.
   ========================================================================== */

function formatClockTime(now, timeZone) {
    return new Intl.DateTimeFormat('en-GB', {
        timeZone,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hourCycle: 'h23'
    }).format(now);
}

function formatClockDate(now, timeZone) {
    return new Intl.DateTimeFormat('en-GB', {
        timeZone,
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }).format(now);
}

function updateClocks() {

    const now = new Date();

    const utcButton = document.getElementById('utcButtonTime');

    if (utcButton) {
        utcButton.textContent = `UTC ${formatClockTime(now, 'UTC')}`;
    }

    const timeElement = document.getElementById('worldClockTime');
    const dateElement = document.getElementById('worldClockDate');
    const zoneElement = document.getElementById('worldClockTimezone');

    const clockZone = zoneElement ? selectedTimeZone : 'UTC';

    if (timeElement) {
        timeElement.textContent = formatClockTime(now, clockZone);
    }

    if (dateElement) {
        dateElement.textContent = formatClockDate(now, clockZone);
    }

    if (zoneElement) {
        zoneElement.textContent = `🌍 ${getTimeZoneLabel()}`;
    }
}

updateClocks();
setInterval(updateClocks, 1000);


/* ==========================================================================
   TIMEZONE SELECTOR
   ========================================================================== */

/* Adds any missing options (works for an empty or a Blade-rendered select) */
function populateTimeZoneSelect(select) {

    const existing = new Set([...select.options].map(option => option.value));

    TIMEZONE_OPTIONS.forEach(zone => {

        if (existing.has(zone.value)) {
            return;
        }

        const option = document.createElement('option');
        option.value = zone.value;
        option.textContent = zone.label;
        select.appendChild(option);
    });

    select.value = selectedTimeZone;
}

function updateTimeZoneOffsetBadge() {

    const badge = document.getElementById('calendarTimeZoneOffset');

    if (badge) {
        badge.textContent = getUtcOffsetLabel();
    }
}

function createTimeZoneSelector() {

    let select = document.getElementById('calendarTimeZone');

    if (!select) {

        const calendarElement = document.getElementById('calendar');

        if (!calendarElement) {
            return null;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex align-items-center gap-2 mb-3 flex-wrap';

        wrapper.innerHTML = `
            <label for="calendarTimeZone" class="fw-semibold mb-0">Time Zone:</label>
            <select
                id="calendarTimeZone"
                class="form-select form-select-sm"
                style="min-width: 220px; width: auto;"
            ></select>
            <span id="calendarTimeZoneOffset" class="badge bg-secondary"></span>
        `;

        calendarElement.parentNode.insertBefore(wrapper, calendarElement);

        select = wrapper.querySelector('select');
    }

    populateTimeZoneSelect(select);
    updateTimeZoneOffsetBadge();

    select.addEventListener('change', () => changeCalendarTimeZone(select.value));

    return select;
}

function changeCalendarTimeZone(timeZone) {

    if (!isValidTimeZone(timeZone)) {
        timeZone = DEFAULT_TIMEZONE;
    }

    ensureTimeZoneOption(timeZone);

    selectedTimeZone = timeZone;

    saveTimeZonePreference(selectedTimeZone);

    [calendar, aircraftCalendar].forEach(instance => {

        if (!instance) {
            return;
        }

        instance.setOption('timeZone', selectedTimeZone);
        instance.refetchEvents();
    });

    const select = document.getElementById('calendarTimeZone');

    if (select) {
        populateTimeZoneSelect(select);
    }

    updateTimeZoneOffsetBadge();
    updateClocks();

    /* Slots are absolute instants, so they re-render in the new zone */
    renderTemporarySelection();
}


/* ==========================================================================
   MAIN CALENDAR
   ========================================================================== */

function getToolbarBreakpoint() {
    const width = window.innerWidth;
    return width < 576 ? 'xs' : width < 768 ? 'sm' : 'md';
}

function getHeaderToolbar() {

    switch (getToolbarBreakpoint()) {

        case 'xs':
            return {
                left: 'prev,next',
                center: 'title',
                right: 'scheduleSelected'
            };

        case 'sm':
            return {
                left: 'prev,next',
                center: 'title',
                right: 'today scheduleSelected resourceTimelineDay'
            };

        default:
            return {
                left: 'prev,next today',
                center: 'title',
                right: 'scheduleSelected resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
            };
    }
}

function initCalendar() {

    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        console.error('Calendar element #calendar was not found.');
        return;
    }

    createTimeZoneSelector();

    let toolbarBreakpoint = getToolbarBreakpoint();

    calendar = new Calendar(calendarEl, {

        plugins: [
            luxon3Plugin,
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

        slotLabelFormat: formatHHmm,
        eventTimeFormat: formatHHmm,

        headerToolbar: getHeaderToolbar(),

        customButtons: {
            scheduleSelected: {
                text: 'Schedule(s)',
                click: handleScheduleButtonClick
            }
        },

        resources: '/calendar/aircraft',
        events: '/calendar/schedules',
        eventDataTransform: normalizeEventDates,

        /* The toolbar can be re-rendered by FullCalendar, so restore the button */
        datesSet: () => updateScheduleButton(),

        windowResize: () => {

            const next = getToolbarBreakpoint();

            if (next === toolbarBreakpoint) {
                return;
            }

            toolbarBreakpoint = next;
            calendar.setOption('headerToolbar', getHeaderToolbar());
            requestAnimationFrame(updateScheduleButton);
        },

        resourceLabelDidMount: info => {
            info.el.style.cursor = 'pointer';
            info.el.onclick = () => window.openAircraftCalendar(info.resource.id, info.resource.title);
        },

        eventClick: info => {

            const ext = info.event.extendedProps || {};

            if (ext.temporarySelection === true || ext.isTemporarySelection === true) {
                return;
            }

            openFlightEventAlert(info.event);
        },

        dateClick: handleDateClick,
        select: handleRangeSelection,

        eventMouseEnter: info => {

            const ext = info.event.extendedProps || {};

            info.el.style.cursor = 'pointer';

            info.el.title = [
                `Aircraft: ${info.event.title}`,
                `Start: ${formatDate(info.event.start)}`,
                `End: ${formatDate(info.event.end)}`,
                `Time zone: ${getTimeZoneLabel()}`,
                `Status: ${ext.status || 'scheduled'}`,
                `User: ${ext.user || 'system'}`
            ].join('\n');
        },

        eventDrop: updateSchedule,
        eventResize: updateSchedule,

        loading: isLoading => calendarEl.classList.toggle('opacity-50', isLoading)
    });

    calendar.render();

    updateScheduleButton();
}

function handleScheduleButtonClick() {

    if (!selectedResourceId || selectedSlots.length === 0) {
        Swal.fire('No Slots Selected', 'Please select one or more 30-minute slots.', 'info');
        return;
    }

    const resource = calendar.getResourceById(selectedResourceId);

    if (!resource) {
        Swal.fire('Error', 'Aircraft could not be found.', 'error');
        return;
    }

    showScheduleConfirmation(selectedResourceId, resource.title);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCalendar);
} else {
    initCalendar();
}


/* ==========================================================================
   SLOT SELECTION
   ========================================================================== */

/* Changing aircraft discards the previous aircraft's temporary selection */
function ensureSelectionAircraft(resourceId) {

    if (
        selectedResourceId !== null &&
        String(selectedResourceId) !== String(resourceId)
    ) {
        clearTemporarySelection();
    }

    selectedResourceId = resourceId;
}

function sortSelectedSlots() {
    selectedSlots.sort((a, b) => a.start.getTime() - b.start.getTime());
}

function handleDateClick(info) {

    const resourceId = info.resource ? info.resource.id : null;

    if (!resourceId) {
        Swal.fire('Error', 'No aircraft selected', 'error');
        return;
    }

    /* info.date is the real instant of the clicked slot */
    const start = new Date(info.date);
    const end = new Date(start.getTime() + SLOT_MS);

    ensureSelectionAircraft(resourceId);

    /* Clicking an already-selected slot removes it */
    const existingIndex = selectedSlots.findIndex(
        slot => slot.start.getTime() === start.getTime()
    );

    if (existingIndex !== -1) {

        selectedSlots.splice(existingIndex, 1);

        renderTemporarySelection();
        calendar.unselect();

        return;
    }

    if (hasConflict(resourceId, start, end)) {
        Swal.fire('Conflict', 'This 30-minute slot is already occupied.', 'error');
        return;
    }

    selectedSlots.push({ start, end });

    sortSelectedSlots();
    renderTemporarySelection();
}

function handleRangeSelection(info) {

    const resourceId = info.resource ? info.resource.id : null;

    if (!resourceId) {
        Swal.fire('Error', 'No aircraft selected', 'error');
        calendar.unselect();
        return;
    }

    ensureSelectionAircraft(resourceId);

    /* Break the dragged range into 30-minute slots, skipping unavailable ones */
    const rangeEnd = new Date(info.end);

    for (
        let cursor = new Date(info.start);
        cursor < rangeEnd;
        cursor = new Date(cursor.getTime() + SLOT_MS)
    ) {

        const slotStart = new Date(cursor);
        const slotEnd = new Date(cursor.getTime() + SLOT_MS);

        const alreadySelected = selectedSlots.some(
            slot => slot.start.getTime() === slotStart.getTime()
        );

        if (!alreadySelected && !hasConflict(resourceId, slotStart, slotEnd)) {
            selectedSlots.push({ start: slotStart, end: slotEnd });
        }
    }

    sortSelectedSlots();
    renderTemporarySelection();

    /* Remove FullCalendar's native selection highlight */
    calendar.unselect();
}

function renderTemporarySelection() {

    if (!calendar) {
        return;
    }

    calendar.batchRendering(() => {

        calendar
            .getEvents()
            .filter(event => event.extendedProps?.temporarySelection === true)
            .forEach(event => event.remove());

        if (!selectedResourceId) {
            return;
        }

        selectedSlots.forEach(slot => {

            calendar.addEvent({
                id: `temporary-${selectedResourceId}-${slot.start.getTime()}`,
                resourceId: selectedResourceId,
                start: slot.start,
                end: slot.end,
                title: '',
                display: 'background',
                backgroundColor: '#198754',
                classNames: ['temporary-selection'],
                editable: false,
                extendedProps: {
                    temporarySelection: true,
                    isTemporarySelection: true
                }
            });
        });
    });

    updateScheduleButton();
}

function clearTemporarySelection() {

    selectedSlots = [];
    selectedResourceId = null;

    renderTemporarySelection();
}

function updateScheduleButton() {

    const button = document.querySelector('#calendar .fc-scheduleSelected-button');

    if (!button) {
        return;
    }

    const count = selectedSlots.length;

    button.disabled = count === 0;
    button.classList.toggle('disabled', count === 0);

    button.innerHTML = count === 0
        ? '<i class="bi bi-calendar-plus me-1"></i> Schedule'
        : `<i class="bi bi-calendar-check me-1"></i> Schedule (${count})`;
}

/* Comparisons use absolute instants, so they are independent of display timezone */
function hasConflict(resourceId, start, end) {

    if (!calendar) {
        return false;
    }

    return calendar.getEvents().some(event => {

        if (event.extendedProps?.temporarySelection) {
            return false;
        }

        if (!event.start || !event.end) {
            return false;
        }

        const sameAircraft = (event.getResources() || []).some(
            resource => resource && String(resource.id) === String(resourceId)
        );

        return sameAircraft && start < event.end && end > event.start;
    });
}


/* ==========================================================================
   SCHEDULE CONFIRMATION + CREATE / UPDATE
   ========================================================================== */

async function showScheduleConfirmation(flightId, aircraftTitle) {

    if (selectedSlots.length === 0) {
        return;
    }

    sortSelectedSlots();

    /* Slots must be continuous */
    for (let i = 1; i < selectedSlots.length; i++) {

        if (selectedSlots[i].start.getTime() !== selectedSlots[i - 1].end.getTime()) {

            Swal.fire(
                'Invalid Selection',
                'Please select continuous 30-minute slots. There cannot be a gap between selected slots.',
                'warning'
            );

            return;
        }
    }

    const start = selectedSlots[0].start;
    const end = selectedSlots[selectedSlots.length - 1].end;
    const count = selectedSlots.length;

    const result = await Swal.fire({
        title: 'Schedule Aircraft',
        icon: 'question',
        html: `
            <div class="text-start">
                <p class="mb-2"><strong>Aircraft:</strong> ${escapeHtml(aircraftTitle)}</p>
                <p class="mb-2"><strong>Start:</strong> ${formatDate(start)}</p>
                <p class="mb-2"><strong>End:</strong> ${formatDate(end)}</p>
                <p class="mb-2"><strong>Duration:</strong> ${formatDuration(count * SLOT_MINUTES)}</p>
                <p class="mb-2">
                    <strong>Time Zone:</strong>
                    <span class="badge bg-secondary">${escapeHtml(getTimeZoneLabel())}</span>
                </p>
                <p class="mb-0 text-muted">
                    <small>${count} × ${SLOT_MINUTES}-minute slot${count > 1 ? 's' : ''}</small>
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Schedule',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#198754'
    });

    /* Cancelled: keep the selection so the user can adjust it */
    if (!result.isConfirmed) {
        return;
    }

    /* Only clear the selection once it was actually saved */
    const saved = await createSchedule(flightId, start, end);

    if (saved) {
        clearTemporarySelection();
    }
}

/**
 * start/end are real instants; toISOString() sends them as UTC.
 * Resolves to true when the schedule was saved.
 */
async function createSchedule(flightId, start, end) {

    try {

        const data = await apiRequest('/calendar/create', {
            method: 'POST',
            body: {
                flight_id: flightId,
                start: start.toISOString(),
                end: end.toISOString(),
                timezone: selectedTimeZone   // informational only
            }
        });

        if (!data.success) {
            Swal.fire('Conflict', data.message || 'Unable to save', 'error');
            return false;
        }

        refetchAllCalendars();

        Swal.fire({
            title: 'Saved',
            text: 'Flight scheduled successfully',
            icon: 'success',
            timer: 1200,
            showConfirmButton: false
        });

        return true;

    } catch (error) {

        console.error('Create schedule error:', error);

        const isConflict = [409, 422].includes(error.status);

        Swal.fire(
            isConflict ? 'Conflict' : 'Server Error',
            error.message || 'Please try again',
            'error'
        );

        return false;
    }
}

async function updateSchedule(info) {

    const { event } = info;

    if (!event.start || !event.end) {
        info.revert();
        Swal.fire('Error', 'Invalid schedule time.', 'error');
        return;
    }

    /* If the event was dragged to another aircraft, tell the backend too */
    const resourceId = event.getResources?.()?.[0]?.id;

    try {

        const data = await apiRequest(`/calendar/update/${encodeURIComponent(event.id)}`, {
            method: 'POST',
            body: {
                start: event.start.toISOString(),
                end: event.end.toISOString(),
                flight_id: resourceId,        // omitted from JSON when undefined
                timezone: selectedTimeZone
            }
        });

        if (!data.success) {
            info.revert();
            Swal.fire('Error', data.message || 'Update failed', 'error');
        }

    } catch (error) {

        console.error('Update schedule error:', error);

        info.revert();

        Swal.fire('Server Error', error.message || 'Update failed. Please try again.', 'error');
    }
}


/* ==========================================================================
   FLIGHT EVENT ACTIONS (click on an event)
   ========================================================================== */

function renderActionButton({ id, className, icon, label, gradient = false }) {
    return `
        <button
            type="button"
            class="btn ${className} py-2"
            id="${id}"
            ${gradient ? `style="${GRADIENT_BUTTON_STYLE}"` : ''}
        >
            <i class="bi ${icon} me-2"></i>
            ${label}
        </button>
    `;
}

function buildActionButtons({ isCompleted, isDispatched }) {

    const buttons = [];

    if (!isCompleted) {

        buttons.push(
            isDispatched
                ? { id: 'unDispatchEventButton', className: 'btn-danger', icon: 'bi-arrow-counterclockwise', label: 'Un-Dispatch' }
                : { id: 'dispatchEventButton', className: 'btn-success', icon: 'bi-send-fill', label: 'Dispatch' }
        );

        buttons.push({
            id: 'checkInEventButton',
            className: 'btn-primary',
            icon: 'bi-airplane-engines-fill',
            label: 'Check-In',
            gradient: true
        });
    }

    buttons.push(
        { id: 'logbookEventButton', className: 'btn-primary', icon: 'bi-journal-text', label: 'Logbooks', gradient: true },
        { id: 'editEventButton', className: 'btn-warning', icon: 'bi-pencil-square', label: 'Edit Schedule' },
        { id: 'deleteEventButton', className: 'btn-danger', icon: 'bi-trash', label: 'Delete Schedule' }
    );

    return buttons.map(renderActionButton).join('');
}

function openFlightEventAlert(event) {

    const { ext, scheduleId, aircraftId, flightId } = getEventIds(event);

    const status = ext.status || 'scheduled';
    const normalizedStatus = String(status).toLowerCase();

    const isCompleted = normalizedStatus === 'completed';
    const isDispatched = normalizedStatus === 'dispatched';

    const statusClass = isCompleted || isDispatched ? 'bg-success' : 'bg-primary';

    Swal.fire({

        title: `✈ ${escapeHtml(event.title)}`,
        width: 'min(500px, calc(100vw - 24px))',
        padding: '1.25rem',

        customClass: {
            popup: 'flight-action-popup'
        },

        html: `
            <div class="text-start">

                <div class="mb-3">
                    <strong>Aircraft:</strong>
                    <div class="mt-1">
                        <span class="badge bg-dark px-3 py-2">✈ ${escapeHtml(event.title)}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="badge ${statusClass} ms-2">${escapeHtml(status)}</span>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <strong>Start:</strong>
                        <div class="text-muted small mt-1">${event.start ? formatDate(event.start) : '-'}</div>
                    </div>

                    <div class="col-6 mb-3">
                        <strong>End:</strong>
                        <div class="text-muted small mt-1">${event.end ? formatDate(event.end) : '-'}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Time Zone:</strong>
                    <span class="badge bg-secondary ms-2">${escapeHtml(getTimeZoneLabel())}</span>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    ${buildActionButtons({ isCompleted, isDispatched })}
                </div>

            </div>
        `,

        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Close',

        didOpen: () => {

            const bind = (id, handler) =>
                document.getElementById(id)?.addEventListener('click', handler);

            bind('dispatchEventButton', () => {
                Swal.close();
                openDispatchAlert(event);
            });

            bind('unDispatchEventButton', () => {
                Swal.close();
                openUnDispatchAlert(event);
            });

            bind('editEventButton', () => {
                Swal.close();
                window.location.href = window.scheduleEditUrl.replace(
                    '__ID__',
                    encodeURIComponent(scheduleId)
                );
            });

            bind('deleteEventButton', () => {
                Swal.close();
                openDeleteScheduleAlert(event);
            });

            bind('checkInEventButton', () => handleCheckIn(event));

            bind('logbookEventButton', () =>
                openLogbooksPage(scheduleId, aircraftId, flightId, 'logbook')
            );
        }
    });
}


/* ==========================================================================
   CHECK-IN
   ========================================================================== */

async function handleCheckIn(event) {

    const button = document.getElementById('checkInEventButton');

    if (button) {
        button.disabled = true;
    }

    try {

        const { ext, scheduleId, aircraftId, flightId, pilotId } = getEventIds(event);

        if (!scheduleId) {
            throw new Error('No flight schedule was selected.');
        }

        if (!aircraftId) {
            throw new Error('No aircraft was selected.');
        }

        /* Already dispatched: go straight to Check-In */
        if (String(ext.status || 'scheduled').toLowerCase() === 'dispatched') {
            openCheckInPage(scheduleId, aircraftId, flightId);
            return;
        }

        const confirmation = await Swal.fire({
            icon: 'info',
            title: 'Flight Not Dispatched',
            html: `
                This flight has not been dispatched yet.
                <br><br>
                <strong>Dispatch the flight before Check-In?</strong>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send-fill me-1"></i> Dispatch & Check-In',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#4886a3',
            cancelButtonColor: '#6c757d',
            allowOutsideClick: false
        });

        if (!confirmation.isConfirmed) {

            if (button) {
                button.disabled = false;
            }

            return;
        }

        Swal.fire({
            title: 'Dispatching Flight...',
            html: 'Please wait while the flight is being dispatched.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        const dispatchData = await apiRequest(window.dispatchStoreUrl, {
            method: 'POST',
            body: {
                schedule_id: scheduleId,
                aircraft_id: aircraftId,
                pilot_id: pilotId,
                hobbs_out: safeNumber(ext.current_hobbs),
                tach_out: safeNumber(ext.current_tach),
                dispatch_no: ext.dispatch_no || '',
                remarks: 'Automatically dispatched during Check-In.'
            }
        });

        if (!dispatchData.success) {
            throw new Error(dispatchData.message || 'Unable to dispatch the flight.');
        }

        await Swal.fire({
            icon: 'success',
            title: 'Flight Dispatched',
            html: `
                Dispatch No: <strong>${escapeHtml(dispatchData.dispatch_no || '-')}</strong>
                <br><br>
                Continuing to Check-In...
            `,
            timer: 1500,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        openCheckInPage(scheduleId, aircraftId, flightId);

    } catch (error) {

        console.error('Check-In / Dispatch error:', error);

        Swal.close();

        await Swal.fire({
            icon: 'error',
            title: 'Check-In Failed',
            text: error.message || 'Unable to prepare the flight for Check-In.'
        });

        if (button) {
            button.disabled = false;
        }
    }
}

function openCheckInPage(scheduleId, aircraftId, flightId) {
    openLogbooksPage(scheduleId, aircraftId, flightId, 'checkin');
}

function openLogbooksPage(scheduleId, aircraftId, flightId, mode) {

    const url = new URL(window.logbooksIndexUrl, window.location.origin);

    url.searchParams.set('schedule_id', scheduleId);
    url.searchParams.set('flight_id', flightId);
    url.searchParams.set('aircraft_id', aircraftId);
    url.searchParams.set('mode', mode);

    /* Lets the Logbooks page use the same timezone */
    url.searchParams.set('timezone', selectedTimeZone);

    window.location.href = url.toString();
}

function openLogbookModal(event, mode = 'checkin') {

    /* Only prepare the modal for Check-In */
    if (mode !== 'checkin') {
        return;
    }

    const modalElement = document.getElementById('createLogbookModal');

    if (!modalElement) {
        console.error('Modal #createLogbookModal was not found.');
        Swal.fire('Error', 'Logbook form could not be found.', 'error');
        return;
    }

    const { scheduleId, aircraftId, flightId } = getEventIds(event);

    const scheduleInput = document.getElementById('schedule_id');
    const flightInput = document.getElementById('flight_id');

    if (scheduleInput) {
        scheduleInput.value = scheduleId;
    }

    if (flightInput) {
        flightInput.value = flightId;
    }

    const aircraftSelect = document.getElementById('aircraftSelect');

    if (aircraftSelect && aircraftId) {

        const match = [...aircraftSelect.options].find(
            option => String(option.value) === String(aircraftId)
        );

        if (match) {
            aircraftSelect.value = aircraftId;
        }
    }

    /* Calendar date in the user's selected timezone (not UTC) */
    const flightDateInput = modalElement.querySelector('input[name="flight_date"]');

    if (flightDateInput && event.start) {
        flightDateInput.value = formatDateOnly(event.start);
    }

    const timezoneInput = modalElement.querySelector('[name="timezone"], #timezone');

    if (timezoneInput) {
        timezoneInput.value = selectedTimeZone;
    }

    showModal(modalElement);
}


/* ==========================================================================
   DISPATCH / UN-DISPATCH / DELETE
   ========================================================================== */

async function openDispatchAlert(event) {

    const ext = event.extendedProps || {};

    const currentHobbs = safeNumber(ext.current_hobbs);
    const currentTach = safeNumber(ext.current_tach);

    const result = await Swal.fire({

        title: `✈ ${escapeHtml(event.title)}`,
        width: 750,
        heightAuto: false,

        customClass: {
            popup: 'dispatch-swal-popup',
            htmlContainer: 'dispatch-swal-body'
        },

        html: `
            <div class="text-start">

                <h5 class="mb-3 border-bottom pb-2">Dispatch Details</h5>

                <div class="row">

                    <div class="col-6 mb-2">
                        <b>Aircraft:</b><br>
                        ${escapeHtml(event.title)}
                    </div>

                    <div class="col-6 mb-2">
                        <b>Status:</b><br>
                        ${escapeHtml(ext.status || 'Scheduled')}
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">Departure</label>
                        <input
                            id="swalDeparture"
                            class="form-control"
                            placeholder="From"
                            value="${escapeHtml(ext.departure)}"
                        >
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">Destination</label>
                        <input
                            id="swalDestination"
                            class="form-control"
                            placeholder="To"
                            value="${escapeHtml(ext.destination)}"
                        >
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">Pilot</label>
                        <input
                            id="swalPilot"
                            class="form-control"
                            value="${escapeHtml(ext.pilot)}"
                        >
                    </div>

                    <div class="col-6 mb-2">
                        <label class="form-label">Co-Pilot</label>
                        <input
                            id="swalCoPilot"
                            class="form-control"
                            value="${escapeHtml(ext.copilot)}"
                        >
                    </div>

                    <div class="col-6 mb-2">
                        <b>Dispatch No:</b><br>
                        ${escapeHtml(ext.dispatch_no || 'N/A')}
                    </div>

                    <div class="col-6 mb-2">
                        <b>User:</b><br>
                        ${escapeHtml(ext.user || 'System')}
                    </div>

                    <div class="col-6 mb-2">
                        <b>Start:</b><br>
                        ${formatDate(event.start)}
                    </div>

                    <div class="col-6 mb-2">
                        <b>End:</b><br>
                        ${formatDate(event.end)}
                    </div>

                    <div class="col-12 mb-2">
                        <b>Time Zone:</b><br>
                        <span class="badge bg-secondary">${escapeHtml(getTimeZoneLabel())}</span>
                    </div>

                </div>

                <hr>

                <h6 class="mb-2 text-primary">📊 Aircraft Utilization</h6>

                <div class="row">

                    <div class="col-4 mb-2">
                        <b>Hobbs Out:</b><br>
                        <span class="badge bg-info px-3">${currentHobbs.toFixed(1)} hrs</span>
                    </div>

                    <div class="col-4 mb-2">
                        <b>Tach Out:</b><br>
                        <span class="badge bg-info px-3">${currentTach.toFixed(1)} hrs</span>
                    </div>

                    <div class="col-4 mb-2">
                        <b>Aircraft Status:</b><br>
                        <span class="badge bg-success px-3 mb-2">ACTIVE</span>
                    </div>

                </div>

                <hr>

                <div class="col-12 mb-2">
                    <label class="form-label">Remarks</label>
                    <textarea id="swalRemarks" class="form-control" rows="3">${escapeHtml(ext.remarks)}</textarea>
                </div>

            </div>
        `,

        showCancelButton: true,
        confirmButtonText: 'Dispatch',
        cancelButtonText: 'Close',
        confirmButtonColor: '#198754',

        /* Read the fields while the popup is still open */
        preConfirm: () => ({
            departure: document.getElementById('swalDeparture')?.value || '',
            destination: document.getElementById('swalDestination')?.value || '',
            pilot: document.getElementById('swalPilot')?.value || '',
            copilot: document.getElementById('swalCoPilot')?.value || '',
            remarks: document.getElementById('swalRemarks')?.value || ''
        })
    });

    if (!result.isConfirmed) {
        return;
    }

    Object.entries(result.value).forEach(([key, value]) => {
        event.setExtendedProp(key, value);
    });

    /* Defined elsewhere in the page (Blade) */
    if (typeof openDispatchModal === 'function') {
        openDispatchModal(event);
    } else {
        console.warn('openDispatchModal() is not defined.');
    }

    const modalElement = document.getElementById('dispatchModal');

    if (modalElement) {
        showModal(modalElement);
    }
}

async function openDeleteScheduleAlert(event) {

    const { scheduleId } = getEventIds(event);

    const result = await Swal.fire({
        icon: 'warning',
        title: 'Delete Schedule?',
        html: `
            <p class="mb-2">Are you sure you want to delete this flight schedule?</p>
            <strong>✈ ${escapeHtml(event.title)}</strong>
            <br><br>
            <span class="text-danger">This action cannot be undone.</span>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        allowOutsideClick: false
    });

    if (!result.isConfirmed) {
        return;
    }

    Swal.fire({
        title: 'Deleting Schedule...',
        html: 'Please wait while the schedule is deleted.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });

    try {

        const deleteUrl = window.scheduleDeleteUrl.replace(
            '__ID__',
            encodeURIComponent(scheduleId)
        );

        const data = await apiRequest(deleteUrl, { method: 'DELETE' });

        event.remove();
        refetchAllCalendars();

        Swal.fire({
            icon: 'success',
            title: 'Schedule Deleted',
            text: data.message || 'Schedule deleted successfully.',
            timer: 1800,
            showConfirmButton: false
        });

    } catch (error) {

        console.error('Delete schedule error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Delete Failed',
            text: error.message || 'Unable to delete the schedule.'
        });
    }
}

async function openUnDispatchAlert(event) {

    const { ext, scheduleId, aircraftId } = getEventIds(event);

    const result = await Swal.fire({
        icon: 'warning',
        title: 'Un-Dispatch Aircraft?',
        html: `
            <div class="text-start">

                <p>
                    Are you sure you want to un-dispatch
                    <strong>${escapeHtml(event.title)}</strong>?
                </p>

                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    This will return the flight schedule to <strong>Scheduled</strong> status.
                </div>

                <hr>

                <div><strong>Aircraft:</strong> ${escapeHtml(event.title)}</div>
                <div><strong>Dispatch No:</strong> ${escapeHtml(ext.dispatch_no || 'N/A')}</div>

            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-arrow-counterclockwise me-2"></i>Un-Dispatch',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        reverseButtons: true,
        showLoaderOnConfirm: true,

        preConfirm: async () => {

            try {

                const data = await apiRequest(window.unDispatchUrl, {
                    method: 'POST',
                    body: {
                        schedule_id: scheduleId,
                        aircraft_id: aircraftId
                    }
                });

                if (!data.success) {
                    throw new Error(data.message || 'Unable to un-dispatch aircraft.');
                }

                return data;

            } catch (error) {
                Swal.showValidationMessage(error.message);
            }
        }
    });

    if (!result.isConfirmed) {
        return;
    }

    await Swal.fire({
        icon: 'success',
        title: 'Un-Dispatched',
        text: result.value?.message || 'Aircraft has been un-dispatched successfully.',
        confirmButtonColor: '#4886a3'
    });

    refetchAllCalendars();
}


/* ==========================================================================
   PER-AIRCRAFT CALENDAR (modal)
   ========================================================================== */

function teardownAircraftCalendar() {

    if (aircraftCalendar) {
        aircraftCalendar.destroy();
        aircraftCalendar = null;
    }

    /* Clean up leftovers only if no other modal is still open */
    if (!document.querySelector('.modal.show')) {

        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(element => element.remove());

        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
}

function buildAircraftCalendar(flightId) {

    const element = document.getElementById('aircraftCalendar');

    if (!element) {
        console.error('#aircraftCalendar was not found.');
        return;
    }

    if (aircraftCalendar) {
        aircraftCalendar.destroy();
    }

    aircraftCalendar = new Calendar(element, {

        plugins: [
            luxon3Plugin,
            interactionPlugin,
            timeGridPlugin
        ],

        timeZone: selectedTimeZone,

        initialView: 'timeGridWeek',

        /* 24-hour time */
        locale: 'en-GB',

        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

        selectable: true,
        selectMirror: true,
        selectOverlap: false,

        height: 'auto',

        eventDataTransform: normalizeEventDates,

        events: () =>
            apiRequest(`/calendar/schedules?flight_id=${encodeURIComponent(flightId)}`)
                .catch(error => {
                    console.error('Aircraft calendar events error:', error);
                    throw error;
                }),

        select: async info => {

            const start = new Date(info.start);
            const end = new Date(info.end);

            const result = await Swal.fire({
                title: 'Schedule Aircraft',
                icon: 'question',
                html: `
                    <div class="text-start">
                        <p class="mb-2"><strong>Start:</strong> ${formatDate(start)}</p>
                        <p class="mb-2"><strong>End:</strong> ${formatDate(end)}</p>
                        <p class="mb-0">
                            <strong>Time Zone:</strong>
                            <span class="badge bg-secondary">${escapeHtml(getTimeZoneLabel())}</span>
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#198754'
            });

            aircraftCalendar?.unselect();

            if (!result.isConfirmed) {
                return;
            }

            await createSchedule(flightId, start, end);
        },

        eventClick: info => openFlightEventAlert(info.event)
    });

    aircraftCalendar.render();
}

window.openAircraftCalendar = function (flightId, title) {

    const titleElement = document.getElementById('aircraftTitle');

    if (titleElement) {
        titleElement.textContent = `${title} Calendar`;
    }

    const modalElement = document.getElementById('aircraftCalendarModal');

    if (!modalElement) {
        console.error('#aircraftCalendarModal was not found.');
        return;
    }

    /* Already open (e.g. clicked another aircraft): just rebuild */
    if (modalElement.classList.contains('show')) {
        buildAircraftCalendar(flightId);
        return;
    }

    /* Build once the modal is fully visible so FullCalendar can measure it */
    modalElement.addEventListener('shown.bs.modal', () => buildAircraftCalendar(flightId), { once: true });
    modalElement.addEventListener('hidden.bs.modal', teardownAircraftCalendar, { once: true });

    showModal(modalElement);
};


/* ==========================================================================
   PUBLIC API
   ========================================================================== */

window.openLogbookModal = openLogbookModal;

window.changeFlightCalendarTimeZone = changeCalendarTimeZone;

window.getFlightCalendarTimeZone = () => selectedTimeZone;