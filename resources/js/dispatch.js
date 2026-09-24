window.currentDispatchEvent = null;

window.openDispatchModal = function (event) {

    window.currentDispatchEvent = event;

    const p = event.extendedProps || {};
    console.log(p);

    const scheduleInput =
        document.getElementById('scheduleId');

    if (scheduleInput) {
        scheduleInput.value = p.schedule_id || '';
    }

    const aircraftInput =
        document.getElementById('aircraftId');

    if (aircraftInput) {
        aircraftInput.value = p.aircraft_id || '';
    }

    const pilotInput =
        document.getElementById('pilotId');

    if (pilotInput) {
        pilotInput.value = p.pilot_id || '';
    }

    // =========================================
    // HELPERS
    // =========================================
    const formatDate = (date) => {

        if (!date) return 'N/A';

        return date.toLocaleString('en-US', {
            timeZone: 'UTC',
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    };

    const safeNum = (value) => {

        const n = parseFloat(value);

        return isNaN(n) ? 0 : n;
    };

    const set = (id, value) => {

        const el = document.getElementById(id);

        if (el) {
            el.textContent = value ?? 'N/A';
        }
    };

    // =========================================
    // AIRCRAFT CURRENT METERS
    // =========================================

    const currentHobbs = safeNum(
        p.current_hobbs
    );

    const currentTach = safeNum(
        p.current_tach
    );
    // Save aircraft meter snapshot for dispatch submission
    window.currentDispatchEvent.currentHobbs = currentHobbs;
    window.currentDispatchEvent.currentTach = currentTach;


    const hoursRemaining = safeNum(
        p.hours_remaining
    );
    // =========================================
    // CURRENT FLIGHT VALUES
    // =========================================
    const hobbsStart = safeNum(p.hobbs_start);
    const hobbsEnd = safeNum(p.hobbs_end);

    const tachStart = safeNum(p.tach_start);
    const tachEnd = safeNum(p.tach_end);

    // =========================================
    // INSPECTION LOGIC
    // =========================================
    const grounded =
        p.aircraft_status === 'grounded';

    const dueSoon =
        p.aircraft_status === 'due_soon';

    // =========================================
    // WARNING BANNER
    // =========================================
    const banner = document.getElementById(
        'dispatchWarningBanner'
    );

    if (banner) {

        if (grounded) {

            banner.classList.remove('d-none');

            banner.innerHTML = `
                🚨 AIRCRAFT GROUNDED
                <div class="small mt-1">
                    Maintenance overdue
                </div>
            `;

        } else if (dueSoon) {

            banner.classList.remove('d-none');

            banner.innerHTML = `
                ⚠ MAINTENANCE DUE SOON
                <div class="small mt-1">
                    ${hoursRemaining.toFixed(1)} hrs remaining
                </div>
            `;

        } else {

            banner.classList.add('d-none');

        }
    }

    // =========================================
    // BASIC INFO
    // =========================================
    set('dispatchAircraft', event.title);

    set(
        'dispatchStatus',
        p.status || 'Scheduled'
    );

    set('dispatchDeparture', p.departure);
    set('dispatchDestination', p.destination);

    set(
        'dispatchPilotName',
        p.pilot || 'N/A'
    );

    set('dispatchInstructorName', p.instructor || 'N/A');

    set(
        'dispatchPilotMedical',
        p.medical || 'N/A'
    );

    set(
        'dispatchCopilot',
        p.copilot || 'N/A'
    );

    set(
        'dispatchNo',
        p.dispatch_no || 'N/A'
    );

    set(
        'dispatchUser',
        p.user || 'System'
    );

    set(
        'dispatchStart',
        formatDate(event.start)
    );

    set(
        'dispatchEnd',
        formatDate(event.end)
    );

    set(
        'dispatchRemarks',
        p.remarks || 'No remarks'
    );

    // =========================================
    // CURRENT FLIGHT HOBBS
    // =========================================
    set(
        'dispatchHobbsStart',
        hobbsStart.toFixed(1)
    );

    set(
        'dispatchHobbsEnd',
        hobbsEnd.toFixed(1)
    );

    // =========================================
    // CURRENT FLIGHT TACH
    // =========================================
    set(
        'dispatchTachStart',
        tachStart.toFixed(1)
    );

    set(
        'dispatchTachEnd',
        tachEnd.toFixed(1)
    );

    // =========================================
    // TOTAL AIRCRAFT HOURS
    // =========================================
    set(
        'dispatchTotalHobbs',
        currentHobbs.toFixed(1)
    );


    set(
        'dispatchTotalTach',
        currentTach.toFixed(1)
    );

    // =========================================
    // MAINTENANCE STATUS
    // =========================================
    const maintenanceEl = document.getElementById(
        'dispatchMaintenance'
    );

    if (maintenanceEl) {

        let status = 'ACTIVE';
        let cls = 'bg-success';

        if (grounded) {

            status = 'GROUND';
            cls = 'bg-danger';

        }
        else if (dueSoon) {

            status = 'INSPECTION SOON';
            cls = 'bg-warning text-dark';

        }
        else {

            status = 'SERVICEABLE';
            cls = 'bg-success';

        }

        maintenanceEl.textContent = status;

        maintenanceEl.className =
            `badge px-3 py-2 ${cls}`;
    }

    // =========================================
    // SERVICE TRACKER
    // =========================================
    const serviceEl = document.getElementById(
        'dispatchServiceTracker'
    );

    if (serviceEl) {

        let status =
            `${hoursRemaining.toFixed(1)} hrs remaining`;

        let cls = 'bg-success';


        if (grounded) {

            status = 'Inspection Required';
            cls = 'bg-danger';

        } else if (dueSoon) {

            cls = 'bg-warning text-dark';

        }


        serviceEl.textContent = status;

        serviceEl.className =
            `badge px-3 py-2 ${cls}`;
    }

    // =========================================
    // OPTIONAL AUTO BLOCK
    // =========================================
    const dispatchBtn = document.getElementById(
        'confirmDispatchBtn'
    );

    if (dispatchBtn) {

        dispatchBtn.disabled = grounded;

        dispatchBtn.innerHTML = grounded
            ? 'Aircraft Grounded'
            : 'Dispatch Aircraft';

        dispatchBtn.classList.toggle(
            'btn-danger',
            grounded
        );

        dispatchBtn.classList.toggle(
            'btn-primary',
            !grounded
        );
    }
};


document
    .getElementById('confirmDispatchBtn')
    .addEventListener('click', async function () {

        const btn = this;

        try {

            btn.disabled = true;

            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"
                      role="status"
                      aria-hidden="true"></span>
                Dispatching...
            `;

            const event = window.currentDispatchEvent;

            if (!event) {
                throw new Error('No dispatch event selected.');
            }

            const p = event.extendedProps || {};

            /*
            |--------------------------------------------------------------------------
            | SEND REQUEST
            |--------------------------------------------------------------------------
            */

            const response = await fetch('/dispatch/store', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content || ''
                },

                body: JSON.stringify({

                    schedule_id: p.schedule_id,
                    aircraft_id: p.aircraft_id,
                    pilot_id: p.pilot_id,
                    instructor_id: p.instructor_id,
                    dispatch_no: p.dispatch_no,

                    hobbs_out: event.currentHobbs,
                    tach_out: event.currentTach,

                    departure: p.departure,
                    destination: p.destination,
                    remarks: p.remarks,

                    dispatched_at: new Date().toISOString()
                })
            });


            /*
            |--------------------------------------------------------------------------
            | READ SERVER RESPONSE
            |--------------------------------------------------------------------------
            */

            const data = await response.json();


            /*
            |--------------------------------------------------------------------------
            | AIRCRAFT ALREADY ACTIVE
            |--------------------------------------------------------------------------
            */

            if (response.status === 409) {

                await Swal.fire({

                    icon: 'warning',

                    title: 'Previous Dispatched Aircraft Active',

                    html: `
                        <div class="text-start">

                            <div class="alert alert-warning">
                                <strong>
                                    ⚠ Aircraft unavailable
                                </strong>
                            </div>

                            <p>
                                ${data.message ||
                                'This aircraft already has an active dispatch.'}
                            </p>

                            <div class="border rounded p-3 bg-light">

                                <div class="row">

                                    <div class="col-6">
                                        <small class="text-muted">
                                            Dispatch No.
                                        </small>

                                        <div class="fw-bold">
                                            ${data.dispatch_no || 'N/A'}
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <small class="text-muted">
                                            Status
                                        </small>

                                        <div>
                                            <span class="badge bg-warning text-dark">
                                                ${data.status || 'Active'}
                                            </span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    `,

                    confirmButtonText: 'Understood',
                    confirmButtonColor: '#f0ad4e',
                    width: 500
                });

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATION ERROR
            |--------------------------------------------------------------------------
            */

            if (response.status === 422) {

                let message =
                    data.message ||
                    'Please check the dispatch information.';

                if (data.errors) {

                    const errors = Object.values(data.errors)
                        .flat()
                        .map(error => `<li>${error}</li>`)
                        .join('');

                    message += `
                        <ul class="text-start mt-3">
                            ${errors}
                        </ul>
                    `;
                }

                await Swal.fire({

                    icon: 'warning',

                    title: 'Invalid Dispatch Information',

                    html: message,

                    confirmButtonText: 'Review',
                    confirmButtonColor: '#f0ad4e'
                });

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | OTHER SERVER ERRORS
            |--------------------------------------------------------------------------
            */

            if (!response.ok) {

                throw new Error(
                    data.message ||
                    `Server returned error ${response.status}.`
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CHECK APPLICATION SUCCESS
            |--------------------------------------------------------------------------
            */

            if (!data.success) {

                throw new Error(
                    data.message ||
                    'The dispatch could not be completed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SERVER CONFIRMED SUCCESS
            |--------------------------------------------------------------------------
            */

            // At this point the dispatch has definitely been saved.

            console.log(
                'Dispatch saved successfully:',
                data
            );


            /*
            |--------------------------------------------------------------------------
            | CLOSE BOOTSTRAP MODAL FIRST
            |--------------------------------------------------------------------------
            */

            const dispatchModal =
                document.getElementById('dispatchModal');

            if (dispatchModal) {

                const modal =
                    bootstrap.Modal.getInstance(dispatchModal);

                if (modal) {
                    modal.hide();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | SUCCESS MESSAGE
            |--------------------------------------------------------------------------
            */

            await Swal.fire({

                icon: 'success',

                title: 'Aircraft Dispatched',

                html: `
                    <p class="mb-2">
                        Aircraft dispatch was saved successfully.
                    </p>

                    ${
                        data.dispatch_id
                            ? `
                                <small class="text-muted">
                                    Dispatch ID:
                                    <strong>
                                        ${data.dispatch_id}
                                    </strong>
                                </small>
                            `
                            : ''
                    }
                `,

                confirmButtonText: 'Done',
                confirmButtonColor: '#198754',

                timer: 2500,
                timerProgressBar: true
            });


            /*
            |--------------------------------------------------------------------------
            | REFRESH CALENDAR
            |--------------------------------------------------------------------------
            */

            if (
                typeof calendar !== 'undefined' &&
                calendar &&
                typeof calendar.refetchEvents === 'function'
            ) {
                calendar.refetchEvents();
            }


        } catch (error) {

            console.error(
                'Dispatch Request Error:',
                error
            );

            await Swal.fire({

                icon: 'error',

                title: 'Unable to Dispatch',

                html: `
                    <p class="mb-0">
                        ${error.message ||
                        'An unexpected error occurred.'}
                    </p>
                `,

                confirmButtonText: 'Close',
                confirmButtonColor: '#dc3545',

                width: 450
            });


        } finally {

            btn.disabled = false;

            btn.innerHTML = 'Dispatch Aircraft';
        }

    });
