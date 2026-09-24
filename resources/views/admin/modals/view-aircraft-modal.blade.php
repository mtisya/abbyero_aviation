<div class="modal fade" id="aircraftSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <!-- Header -->
            <div class="modal-header border-bottom bg-light d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold">Aircraft Summary</h4>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        Export
                    </button>

                    <button class="btn btn-primary btn-sm">
                        Print
                    </button>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body p-0">

                <div id="aircraftSummaryContent" class="table-responsive" style="max-height:600px; overflow-y:auto;">

                    <!-- Your AJAX-loaded content goes here -->

                    <div class="text-center p-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-3 mb-0">
                            Loading aircraft details...
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
<script>
    function openAircraftSummary(id) {

        const modal = new bootstrap.Modal(
            document.getElementById('aircraftSummaryModal')
        );

        modal.show();

        document.getElementById('aircraftSummaryContent').innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3 mb-0">Loading aircraft details...</p>
        </div>
    `;

        fetch(`/aircraft/${id}/summary`)
            .then(response => response.json())
            .then(data => {

                let rows = '';

                if (data.reminders.length) {

                    data.reminders.forEach(item => {

                        const interval = parseFloat(item.interval ?? 0);
                        const remaining = parseFloat(item.remaining ?? 0);

                        // Calculate progress
                        let percentage = interval > 0
                            ? (remaining / interval) * 100
                            : 100;

                        percentage = Math.max(0, Math.min(100, percentage));

                        // Progress color
                        let progressClass = 'bg-success';

                        if (item.status === 'Overdue') {
                            progressClass = 'bg-danger';
                        } else if (item.status === 'Due Soon') {
                            progressClass = 'bg-warning';
                        }

                        rows += `

                        <tr>

                            <td>${item.tail_no}</td>

                            <td>${item.aircraft}</td>

                            <td>
                                <strong>${item.reminder}</strong>
                            </td>

                            <td>
                                ${item.interval
                                ? item.interval + ' hrs'
                                : '-'}
                            </td>

                            <td style="min-width:240px">

                                <div class="d-flex justify-content-between mb-1">

                                    <small class="fw-semibold">
                                        ${remaining.toFixed(1)} hrs
                                    </small>

                                    <small class="text-muted">
                                        ${percentage.toFixed(0)}%
                                    </small>

                                </div>

                                <div class="progress" style="height:10px">

                                    <div
                                        class="progress-bar ${progressClass}"
                                        role="progressbar"
                                        style="width:${percentage}%"
                                        aria-valuenow="${percentage}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">

                                    </div>

                                </div>

                            </td>

                            <td>${item.due ?? '-'}</td>

                            <td>${item.hours}</td>

                            <td>
                                ${item.days !== null
                                ? item.days + ' days'
                                : '-'}
                            </td>

                        </tr>

                    `;

                    });

                } else {

                    rows = `

                    <tr>

                        <td colspan="8" class="text-center py-5">

                            <i class="bi bi-check-circle-fill text-success fs-2"></i>

                            <p class="mt-3 mb-0">
                                No maintenance reminders found.
                            </p>

                        </td>

                    </tr>

                `;

                }

                document.getElementById('aircraftSummaryContent').innerHTML = `

                <div class="p-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h4 class="fw-bold mb-1">
                                ${data.aircraft_model}
                            </h4>

                            <div class="text-muted">
                                Tail No: ${data.registration_number}
                            </div>

                        </div>

                        <span class="badge bg-primary fs-6 px-3 py-2">
                            Current Tach: ${data.current_tach}
                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Tail No</th>

                                    <th>Aircraft</th>

                                    <th>Reminder</th>

                                    <th>Interval</th>

                                    <th style="width:260px">
                                        Remaining
                                    </th>

                                    <th>Due Tach</th>

                                    <th>Current Tach</th>

                                    <th>Days</th>

                                </tr>

                            </thead>

                            <tbody>

                                ${rows}

                            </tbody>

                        </table>

                    </div>

                </div>

            `;

            })
            .catch(() => {

                document.getElementById('aircraftSummaryContent').innerHTML = `

                <div class="alert alert-danger m-3">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Failed to load aircraft details.

                </div>

            `;

            });

    }
</script>