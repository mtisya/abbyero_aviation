@extends('layout')

@section('content')
    <div class="container">


        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mt-3">
            <h3 class="mb-3 mb-md-0">✈ Logbooks</h3>


            <div class="d-flex flex-wrap gap-2">

                @auth
                    @if(in_array(Auth::user()->role, ['admin', 'instructor', 'student']))

                        {{-- Create Logbook Button --}}
                        <a href="#" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#createLogbookModal"
                            style="background: linear-gradient(#4886a3);">

                            <i class="bi bi-plus-circle me-1"></i>
                            Logbook Entry
                        </a>
                    @endif
                @endauth
                {{-- Dispatch --}}
                <a href="{{ route('dispatches.index') }}" class="btn btn-sm btn-success d-flex align-items-center">
                    <i class="bi bi-send-check me-1"></i>
                    Dispatch Details
                </a>
                @auth

                    @if (auth()->user()->role === 'admin')

                        <a href="{{ route('admin.block-time-requests.index') }}" class="btn btn-info shadow-sm">
                            <i class="bi bi-clock-history me-1"></i>
                            Block Time Requests
                        </a>

                    @endif

                @endauth
                <a href="{{ route('admin.tach-history') }}" class="btn btn-warning shadow-sm">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Tach History
                </a>
                <a href="{{ route('calendar') }}" class="btn btn-primary btn-sm px-3 py-2 shadow-sm"> <i
                        class="bi bi-calendar2-week me-1"></i>
                    Schedules
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-primary w-30 w-md-auto">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <form method="GET" class="row g-3 align-items-end mb-4">

            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Aircraft</label>
                <select name="aircraft" class="form-select">
                    <option value="">All Aircraft</option>

                    @foreach($aircraftList as $ac)
                        <option value="{{ $ac }}" {{ request('aircraft') == $ac ? 'selected' : '' }}>
                            {{ $ac }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>
            </div>

        </form>


        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>SN</th>
                        <th>Date</th>
                        <th>Aircraft</th>
                        <th>Route</th>
                        <th>Type</th>
                        <th>HOBBS</th>
                        <th>TACH</th>
                        <th>FlightTime</th>
                        <th>BlockTime</th>
                        <th>Cost (USD)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logbooks as $log)
                        @php
                            $flownMinutes = $log->hours * 60;
                        @endphp

                        <tr>
                            {{-- SN --}}
                            <td>{{ $loop->iteration + ($logbooks->currentPage() - 1) * $logbooks->perPage() }}</td>

                            {{-- Date --}}
                            <td>{{ $log->flight_date }}</td>

                            {{-- Aircraft --}}
                            <td>{{ $log->aircraft }}</td>

                            {{-- Route --}}
                            <td>{{ $log->route }}</td>

                            {{-- Type --}}
                            <td>{{ ucfirst($log->type) }}</td>

                            {{-- HOBBS --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary">
                                        {{ number_format($log->hobbs_start, 1) }} → {{ number_format($log->hobbs_end, 1) }}
                                    </span>
                                    <small class="text-muted">
                                        Total: {{ number_format($log->hobbs_time, 1) }} hrs
                                    </small>
                                </div>
                            </td>

                            {{-- TACH --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-warning">
                                        {{ number_format($log->tach_start, 1) }} → {{ number_format($log->tach_end, 1) }}
                                    </span>
                                    <small class="text-muted">
                                        Engine: {{ number_format($log->tach_time, 1) }} hrs
                                    </small>
                                </div>
                            </td>

                            {{-- Flight Time --}}
                            <td>{{ $log->hours * 60 }} min</td>

                            {{-- Remaining --}}
                            <td>
                                {{ intdiv($log->remaining_block_minutes, 60) }}h
                                {{ $log->remaining_block_minutes % 60 }} min
                            </td>

                            {{-- Cost --}}
                            <td>{{ number_format($log->flight_cost, 2) }}</td>

                            {{-- Status --}}
                            <td>
                                @if($log->approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>

                                    @if(
                                            Auth::user()->role === 'instructor' ||
                                            (
                                                in_array(Auth::user()->role, ['admin', 'instructor'])
                                            )
                                        )
                                        <form method="POST" action="{{ route('logbook.approve', $log->id) }}" class="mt-1">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    {{-- VIEW --}}
                                    <button type="button" class="btn btn-sm btn-outline-info action-btn"
                                        onclick="openLogbook({{ $log->id }})" title="View Logbook">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    {{-- EDIT --}}
                                    @php
                                        $canEdit =
                                            (
                                                Auth::user()->role === 'student' &&
                                                !$log->approved &&
                                                Auth::user()->student?->id === $log->student_id
                                            ) ||
                                            (
                                                in_array(Auth::user()->role, ['admin', 'instructor'])
                                            );
                                    @endphp

                                    @if($canEdit)
                                        <button type="button" class="btn btn-sm btn-outline-warning action-btn"
                                            onclick="openEditModal({{ $log->id }})" title="Edit Logbook">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    @endif

                                    {{-- DELETE --}}
                                    @if(
                                            Auth::user()->role === 'admin' ||
                                            (
                                                Auth::user()->role === 'student' &&
                                                !$log->approved &&
                                                Auth::user()->student?->id === $log->student_id
                                            )
                                        )

                                        <form method="POST" action="{{ route('logbooks.destroy', $log) }}" class="m-0">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-outline-danger action-btn delete-btn"
                                                title="Delete Logbook">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{ $logbooks->withQueryString()->links() }}
    </div>
    </div>
    <div class="modal fade" id="logbookModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content shadow-lg">

                <div class="modal-header bg-dark text-white" style="background: linear-gradient(#4886a3);">
                    <h5 class="modal-title">
                        ✈ Logbook Details
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="logbookModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading logbook...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="editLogbookModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content shadow">

                <div class="modal-header bg-light">

                    <h5 class="modal-title">

                        ✏ Edit Logbook

                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <form id="editLogbookForm" action="javascript:void(0);">

                    @csrf

                    @method('PUT')

                    <div class="modal-body">

                        <input type="hidden" id="editLogbookId" name="id">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">

                                    Route

                                </label>

                                <input type="text" class="form-control" id="editRoute" name="route">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">

                                    Flight Date

                                </label>

                                <input type="date" class="form-control" id="editFlightDate" name="flight_date">

                            </div>

                            <div class="col-md-3">

                                <label>Hobbs Start</label>

                                <input type="number" step="0.1" class="form-control" id="editHobbsStart" name="hobbs_start">

                            </div>

                            <div class="col-md-3">

                                <label>Hobbs End</label>

                                <input type="number" step="0.1" class="form-control" id="editHobbsEnd" name="hobbs_end">

                            </div>

                            <div class="col-md-3">

                                <label>Tach Start</label>

                                <input type="number" step="0.1" class="form-control" id="editTachStart" name="tach_start">

                            </div>

                            <div class="col-md-3">

                                <label>Tach End</label>

                                <input type="number" step="0.1" class="form-control" id="editTachEnd" name="tach_end">

                            </div>

                            <div class="col-md-12">

                                <label>Remarks</label>

                                <textarea class="form-control" rows="3" id="editRemarks" name="remarks"></textarea>

                            </div>

                            <div class="col-md-12 d-none" id="overrideReasonWrapper">

                                <label>

                                    Override Reason

                                </label>

                                <textarea class="form-control" name="override_reason"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Close

                        </button>

                        <button type="submit" class="btn btn-success">

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="modal fade" id="createLogbookModal" tabindex="-1" aria-labelledby="createLogbookLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content shadow">

                <div class="modal-header text-dark" style="background: linear-gradient(#4886a3);">

                    <h5 class="modal-title" id="createLogbookLabel">
                        <i class="bi bi-journal-plus me-2"></i>
                        Logbook Entry
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <form id="logbookForm" method="POST" action="{{ route('logbook.store') }}">

                    @csrf


                    <input type="hidden" name="schedule_id" id="schedule_id">
                    <input type="hidden" name="flight_id" id="flight_id">


                    <div class="modal-body">

                        @php
                            $latestLogs = $latestLogPerAircraft ?? [];
                        @endphp

                        {{-- Flight Information --}}
                        <div class="border rounded p-3 mb-4">

                            <h6 class="fw-bold mb-3">
                                Flight Information
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Schedule / Aircraft
                                    </label>

                                    <select name="aircraft" id="aircraftSelect" class="form-select" required>
                                        <option value="">Loading schedules...</option>
                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Flight Date
                                    </label>

                                    <input type="date" name="flight_date" class="form-control"
                                        value="{{ now()->toDateString() }}" required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Flight Type
                                    </label>

                                    <select name="type" class="form-select" required>

                                        <option value="dual">
                                            Dual
                                        </option>

                                        <option value="solo">
                                            Solo
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-12">

                                    <label class="form-label">
                                        Route
                                    </label>

                                    <input type="text" name="route" class="form-control" placeholder="e.g. Nairobi – Wilson"
                                        required>

                                </div>

                            </div>

                        </div>

                        {{-- Hobbs & Tach --}}
                        <div class="border rounded p-3 mb-4">

                            <h6 class="fw-bold mb-3">
                                Aircraft Times
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Hobbs Start
                                    </label>

                                    <input type="number" step="0.1" name="hobbs_start" class="form-control" readonly
                                        required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Hobbs End
                                    </label>

                                    <input type="number" step="0.1" name="hobbs_end" class="form-control" required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Tach Start
                                    </label>

                                    <input type="number" step="0.1" name="tach_start" class="form-control" readonly
                                        required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Tach End
                                    </label>

                                    <input type="number" step="0.1" name="tach_end" class="form-control" required>

                                </div>
                                @php
                                    $hasInitialBlockTime =
                                        (float) ($userBlockTime?->initial_block_time ?? 0) > 0;
                                @endphp

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Block Flight Time
                                    </label>

                                    <div class="input-group">

                                        <input type="number" step="0.1" min="0.1" name="block_time" id="blockTimeInput"
                                            class="form-control" value="{{ old(
        'block_time',
        $hasInitialBlockTime
        ? $userBlockTime->initial_block_time
        : ''
    ) }}" placeholder="Enter initial block hours"
                                            @disabled($hasInitialBlockTime)>

                                        <button type="button" class="btn btn-success" id="addBlockTimeButton"
                                            title="Request Additional Block Time">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>

                                    </div>

                                    @if ($hasInitialBlockTime)

                                        <small class="text-muted">
                                            <i class="bi bi-lock-fill"></i>
                                            Initial block time has already been allocated.
                                        </small>

                                    @else

                                        <small class="text-primary">
                                            <i class="bi bi-info-circle"></i>
                                            Enter the initial block time for this user.
                                        </small>

                                    @endif

                                    <div class="mt-2">

                                        <small class="text-muted d-block">
                                            Initial Block:

                                            <strong>
                                                {{ number_format(
        $userBlockTime?->initial_block_time ?? 0,
        1
    ) }}
                                            </strong>

                                            hrs
                                        </small>

                                        <small class="text-muted d-block">
                                            Approved Additional:

                                            <strong>
                                                {{ number_format(
        $userBlockTime?->approved_additional_block_time ?? 0,
        1
    ) }}
                                            </strong>

                                            hrs
                                        </small>

                                        <small class="text-muted d-block">
                                            Approved Flown:

                                            <strong>
                                                {{ number_format(
        $userBlockTime?->approved_flown_time ?? 0,
        2
    ) }}
                                            </strong>

                                            hrs
                                        </small>

                                        <small class="text-success fw-bold d-block">

                                            Available:

                                            <strong id="remainingBlockTime">
                                                {{ number_format(
        $userBlockTime?->remaining_block_time ?? 0,
        1
    ) }}
                                            </strong>

                                            hrs

                                        </small>

                                    </div>

                                    <div id="blockTimeRequestStatus" class="small text-warning mt-1"></div>

                                </div>


                            </div>

                        </div>
                        {{-- Fuel & Oil --}}
                        <div class="border rounded p-3 mb-4">

                            <h6 class="fw-bold mb-3">
                                Fuel & Oil
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Fuel Added (Gallons)
                                    </label>

                                    <input type="number" step="0.1" min="0" name="fuel_added" class="form-control"
                                        placeholder="0">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Oil Added (Quarts)
                                    </label>

                                    <input type="number" step="0.1" min="0" name="oil_added" class="form-control"
                                        placeholder="0">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Oil Leak / Consumption
                                    </label>

                                    <select name="oil_condition" class="form-select">

                                        <option value="">
                                            Select...
                                        </option>

                                        <option value="normal">
                                            Normal
                                        </option>

                                        <option value="low">
                                            Low
                                        </option>

                                        <option value="high_consumption">
                                            High Consumption
                                        </option>

                                        <option value="oil_leak">
                                            Oil Leak Observed
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        {{-- Remarks --}}
                        {{-- Additional Information --}}
                        <div class="border rounded p-3">

                            <h6 class="fw-bold mb-3">
                                Flight Remarks
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Remarks
                                    </label>

                                    <textarea name="remarks" rows="3" class="form-control"
                                        placeholder="General remarks..."></textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Aircraft Defects / Snags
                                    </label>

                                    <textarea name="defects" rows="3" class="form-control"
                                        placeholder="Record any defects noticed during flight..."></textarea>

                                </div>

                                @if(in_array(Auth::user()->role, ['admin', 'instructor']))

                                    <div class="col-md-12">

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" id="approveImmediately"
                                                name="approved" value="1">

                                            <label class="form-check-label" for="approveImmediately">

                                                Approve immediately

                                            </label>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button type="submit" class="btn btn-success">

                                <i class="bi bi-save me-1"></i>
                                Save Logbook

                            </button>

                        </div>

                </form>

            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.delete-btn').forEach(btn => {

                btn.addEventListener('click', function (e) {

                    const form = this.closest('form');

                    Swal.fire({
                        title: "Delete Logbook?",
                        text: "This will move the record to archive (can be restored).",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it"
                    }).then((result) => {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });

        });

        function openLogbook(id) {

            const modal = new bootstrap.Modal(document.getElementById('logbookModal'));
            modal.show();

            const body = document.getElementById('logbookModalBody');

            body.innerHTML = `
                                            <div class="text-center py-5">
                                                <div class="spinner-border text-primary"></div>
                                                <p class="mt-2">Loading logbook...</p>
                                            </div>
                                        `;

            fetch(`/logbooks/${id}`)
                .then(res => res.text())
                .then(html => {
                    body.innerHTML = html;
                })
                .catch(() => {
                    body.innerHTML = `
                                                    <div class="alert alert-danger">
                                                        Failed to load logbook data
                                                    </div>
                                                `;
                });
        }

        function openEditModal(id) {
            fetch(`/logbooks/${id}/edit`)
                .then(res => res.json())
                .then(log => {

                    console.log("EDIT DATA:", log);

                    document.getElementById('editLogbookId').value = log.id || '';
                    document.getElementById('editRoute').value = log.route || '';
                    document.getElementById('editFlightDate').value = log.flight_date || '';
                    document.getElementById('editHobbsStart').value = log.hobbs_end || '';
                    document.getElementById('editHobbsEnd').value = log.hobbs_end || '';
                    document.getElementById('editTachStart').value = log.tach_start || '';
                    document.getElementById('editTachEnd').value = log.tach_end || '';
                    document.getElementById('editRemarks').value = log.remarks || '';

                    const wrapper = document.getElementById('overrideReasonWrapper');

                    if (log.approved && ['admin', 'instructor'].includes(log.user_role)) {
                        wrapper.classList.remove('d-none');
                    } else {
                        wrapper.classList.add('d-none');
                    }

                    new bootstrap.Modal(document.getElementById('editLogbookModal')).show();
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Failed to load logbook", "error");
                });
        }
        $('#editLogbookForm').submit(function (e) {
            e.preventDefault();

            const id = $('#editLogbookId').val();

            $.ajax({
                url: `/logbooks/${id}`,
                type: 'POST',
                data: $(this).serialize(),

                success: function (res) {
                    Swal.fire('Success', res.message, 'success')
                        .then(() => location.reload());
                },

                error: function (xhr) {
                    console.log(xhr.responseText);

                    Swal.fire('Error',
                        xhr.responseJSON?.message ?? 'Update failed',
                        'error'
                    );
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modal = document.getElementById('createLogbookModal');
            const select = document.getElementById('aircraftSelect');
            const form = document.getElementById('logbookForm');

            if (!modal || !select || !form) {
                console.error('Logbook modal elements not found.');
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOAD SCHEDULES WHEN MODAL OPENS
            |--------------------------------------------------------------------------
            */

            modal.addEventListener('shown.bs.modal', async function () {

                console.log('🔥 Logbook modal opened');

                select.innerHTML =
                    '<option value="">Loading schedules...</option>';

                try {

                    const res = await fetch(
                        '/staff-logbook/modal-data',
                        {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    console.log(
                        'Modal Data HTTP Status:',
                        res.status
                    );

                    /*
                     * Do not attempt JSON if Laravel returned an error page.
                     */

                    if (!res.ok) {

                        const errorText = await res.text();

                        console.error(
                            'Modal Data Error:',
                            res.status,
                            errorText
                        );

                        throw new Error(
                            `Server returned ${res.status}`
                        );
                    }

                    const data = await res.json();

                    console.log(
                        '🔥 Modal schedules response:',
                        data
                    );

                    select.innerHTML =
                        '<option value="">Select Aircraft / Flight</option>';

                    if (
                        !data.schedules ||
                        !Array.isArray(data.schedules) ||
                        data.schedules.length === 0
                    ) {

                        select.innerHTML =
                            '<option value="">No dispatched schedules found</option>';

                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ADD SCHEDULES
                    |--------------------------------------------------------------------------
                    */

                    data.schedules.forEach(function (s) {

                        const opt =
                            document.createElement('option');

                        /*
                         * Aircraft remains the submitted value.
                         */
                        opt.value = s.aircraft || '';

                        /*
                         * Admin can see who the schedule belongs to.
                         */
                        opt.textContent =
                            `${s.aircraft || 'No Aircraft'} - `
                            + `${s.user_name || 'Unknown User'} - `
                            + `${s.start_time || ''}`;

                        /*
                         * Store IDs in data attributes.
                         */
                        opt.dataset.scheduleId =
                            s.schedule_id || '';

                        opt.dataset.flightId =
                            s.flight_id || '';

                        opt.dataset.userId =
                            s.user_id || '';

                        opt.dataset.userName =
                            s.user_name || '';

                        opt.dataset.hobbs =
                            s.hobbs ?? 0;

                        opt.dataset.tach =
                            s.tach ?? 0;

                        select.appendChild(opt);
                    });

                    /*
                    |--------------------------------------------------------------------------
                    | SELECT FIRST SCHEDULE
                    |--------------------------------------------------------------------------
                    */

                    if (select.options.length > 1) {

                        select.selectedIndex = 1;

                        select.dispatchEvent(
                            new Event('change')
                        );
                    }

                } catch (err) {

                    console.error(
                        '❌ LOAD SCHEDULES ERROR:',
                        err
                    );

                    select.innerHTML =
                        '<option value="">Failed to load schedules</option>';

                    if (typeof Swal !== 'undefined') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to Load Schedules',
                            text: 'The dispatched flight schedules could not be loaded.'
                        });
                    }
                }

            });


            /*
            |--------------------------------------------------------------------------
            | WHEN AIRCRAFT / SCHEDULE IS SELECTED
            |--------------------------------------------------------------------------
            */

            select.addEventListener('change', function () {

                const opt = this.selectedOptions[0];

                if (
                    !opt ||
                    !opt.dataset.scheduleId
                ) {
                    return;
                }

                console.log(
                    'Selected schedule:',
                    opt.dataset
                );

                /*
                |--------------------------------------------------------------------------
                | SET HIDDEN IDS
                |--------------------------------------------------------------------------
                */

                const scheduleInput =
                    document.getElementById('schedule_id');

                const flightInput =
                    document.getElementById('flight_id');

                if (scheduleInput) {

                    scheduleInput.value =
                        opt.dataset.scheduleId || '';
                }

                if (flightInput) {

                    flightInput.value =
                        opt.dataset.flightId || '';
                }


                /*
                |--------------------------------------------------------------------------
                | HOBBS / TACH START
                |--------------------------------------------------------------------------
                */

                const hobbsStart =
                    modal.querySelector(
                        '[name="hobbs_start"]'
                    );

                const tachStart =
                    modal.querySelector(
                        '[name="tach_start"]'
                    );

                const hobbs =
                    Number(opt.dataset.hobbs);

                const tach =
                    Number(opt.dataset.tach);


                if (
                    hobbsStart &&
                    !isNaN(hobbs)
                ) {

                    hobbsStart.value =
                        hobbs.toFixed(1);
                }


                if (
                    tachStart &&
                    !isNaN(tach)
                ) {

                    tachStart.value =
                        tach.toFixed(1);
                }

            });


            /*
            |--------------------------------------------------------------------------
            | FORM SUBMIT
            |--------------------------------------------------------------------------
            */

            form.addEventListener('submit', function (e) {

                const selected =
                    select.selectedOptions[0];

                if (
                    !selected ||
                    !selected.dataset.scheduleId
                ) {

                    e.preventDefault();

                    return Swal.fire(
                        'Error',
                        'Please select a dispatched schedule.',
                        'error'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | SET IDS AGAIN BEFORE SUBMIT
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'schedule_id'
                ).value =
                    selected.dataset.scheduleId;

                document.getElementById(
                    'flight_id'
                ).value =
                    selected.dataset.flightId;


                /*
                |--------------------------------------------------------------------------
                | GET HOBBS / TACH
                |--------------------------------------------------------------------------
                */

                const hs =
                    parseFloat(
                        form.querySelector(
                            '[name="hobbs_start"]'
                        ).value || 0
                    );

                const he =
                    parseFloat(
                        form.querySelector(
                            '[name="hobbs_end"]'
                        ).value || 0
                    );

                const ts =
                    parseFloat(
                        form.querySelector(
                            '[name="tach_start"]'
                        ).value || 0
                    );

                const te =
                    parseFloat(
                        form.querySelector(
                            '[name="tach_end"]'
                        ).value || 0
                    );


                const hobbs = he - hs;

                const tach = te - ts;


                /*
                |--------------------------------------------------------------------------
                | NEGATIVE VALUES
                |--------------------------------------------------------------------------
                */

                if (
                    hobbs < 0 ||
                    tach < 0
                ) {

                    e.preventDefault();

                    return Swal.fire(
                        'Invalid Entry',
                        'End must be greater than start.',
                        'error'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | TACH / HOBBS DIFFERENCE
                |--------------------------------------------------------------------------
                */

                const differenceMinutes =
                    (hobbs - tach) * 60;


                if (differenceMinutes < 5) {

                    e.preventDefault();

                    return Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Tach/Hobbs Entry',
                        html: `
                        Hobbs: ${hobbs.toFixed(2)}<br>
                        Tach: ${tach.toFixed(2)}<br><br>

                        <strong>
                            Tach must be at least 5 minutes
                            less than Hobbs.
                        </strong>
                    `
                    });
                }


                /*
                |--------------------------------------------------------------------------
                | MAXIMUM DIFFERENCE
                |--------------------------------------------------------------------------
                */

                if ((hobbs - tach) > 2) {

                    e.preventDefault();

                    return Swal.fire({
                        icon: 'warning',
                        title: 'Large Tach/Hobbs Difference',
                        html: `
                        Hobbs: ${hobbs.toFixed(2)}<br>
                        Tach: ${tach.toFixed(2)}<br><br>

                        <strong>
                            The difference between Hobbs
                            and Tach cannot exceed 2 hours.
                        </strong>
                    `
                    });
                }

            });

        });
    </script>

    {{-- =========================================================
    CHECK-IN AUTO OPEN
    IMPORTANT: ONLY mode=checkin OPENS THE MODAL
    ========================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const params =
                new URLSearchParams(
                    window.location.search
                );


            const scheduleId =
                params.get('schedule_id');

            const flightId =
                params.get('flight_id');

            const aircraftId =
                params.get('aircraft_id');

            const mode =
                params.get('mode');


            console.log('Logbook page parameters:', {
                scheduleId,
                flightId,
                aircraftId,
                mode
            });


            /*
             * ========================================================
             * CRITICAL
             *
             * Only Check-In should open createLogbookModal.
             *
             * Logbooks:
             * /logbooks?...&mode=logbook
             *
             * must NOT open the modal.
             * ========================================================
             */

            if (
                mode !== 'checkin' ||
                !scheduleId
            ) {

                console.log(
                    'Not Check-In mode. Modal will remain closed.'
                );

                return;
            }


            /* ========================================================
               FIND MODAL
            ======================================================== */

            const modalElement =
                document.getElementById(
                    'createLogbookModal'
                );


            if (!modalElement) {

                console.error(
                    'createLogbookModal was not found on this page.'
                );

                return;
            }


            /* ========================================================
               FIND FORM FIELDS
            ======================================================== */

            const scheduleInput =
                document.getElementById(
                    'schedule_id'
                );

            const flightInput =
                document.getElementById(
                    'flight_id'
                );

            const aircraftSelect =
                document.getElementById(
                    'aircraftSelect'
                );


            /* ========================================================
               SET SCHEDULE
            ======================================================== */

            if (scheduleInput) {

                scheduleInput.value =
                    scheduleId;
            }


            /* ========================================================
               SET FLIGHT
            ======================================================== */

            if (
                flightInput &&
                flightId
            ) {

                flightInput.value =
                    flightId;
            }


            /* ========================================================
               SET AIRCRAFT
            ======================================================== */

            if (
                aircraftSelect &&
                aircraftId
            ) {

                aircraftSelect.value =
                    aircraftId;


                /*
                 * Because aircraft options are loaded dynamically,
                 * the option may not exist yet.
                 */

                if (
                    aircraftSelect.value !==
                    aircraftId
                ) {

                    const option =
                        Array
                            .from(
                                aircraftSelect.options
                            )
                            .find(function (option) {

                                return (
                                    option.value ==
                                    aircraftId ||

                                    option.dataset.aircraftId ==
                                    aircraftId
                                );

                            });


                    if (option) {

                        option.selected =
                            true;
                    }
                }
            }


            /* ========================================================
               OPEN MODAL
            ======================================================== */

            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            console.log(
                '✅ Check-In mode detected. Opening Logbook Entry form.'
            );


            modal.show();

        });


    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @if(session('success'))

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonColor: '#4886a3'
                });

            @endif


            @if(session('error'))

                Swal.fire({
                    icon: 'warning',
                    title: 'Logbook Already Exists',
                    text: @json(session('error')),
                    confirmButtonColor: '#4886a3'
                });

            @endif

            });
        document
            .getElementById('addBlockTimeButton')
            ?.addEventListener('click', () => {

                Swal.fire({
                    title: 'Request Additional Block Time',

                    input: 'number',

                    inputLabel: 'Additional Block Time (Hours)',

                    inputPlaceholder: 'Type hours, e.g. 5.0',

                    inputValue: '',

                    inputAttributes: {
                        min: '5',
                        step: '5',
                        inputmode: 'decimal'
                    },

                    showCancelButton: true,

                    confirmButtonText: 'Submit Request',

                    confirmButtonColor: '#198754',

                    cancelButtonText: 'Cancel',

                    focusConfirm: false,

                    inputValidator: (value) => {

                        if (value === '' || value === null) {
                            return 'Please enter the block time.';
                        }

                        const hours = parseFloat(value);

                        if (isNaN(hours) || hours <= 0) {
                            return 'Enter a valid block time greater than 0.';
                        }

                        return undefined;
                    }

                }).then((result) => {

                    if (!result.isConfirmed) {
                        return;
                    }

                    const hours = parseFloat(result.value);

                    if (isNaN(hours) || hours <= 0) {
                        return;
                    }

                    submitBlockTimeRequest(hours);
                });
            });

        /*
        |--------------------------------------------------------------------------
        | SUBMIT ADDITIONAL BLOCK TIME REQUEST
        |--------------------------------------------------------------------------
        */

        function submitBlockTimeRequest(hours) {

            const scheduleId =
                document.getElementById('schedule_id')?.value;

            if (!scheduleId) {

                Swal.fire({
                    icon: 'error',
                    title: 'Schedule Missing',
                    text: 'Unable to identify the flight schedule.'
                });

                return;
            }

            const csrfToken =
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

            if (!csrfToken) {

                Swal.fire({
                    icon: 'error',
                    title: 'Security Token Missing',
                    text: 'Unable to submit the block-time request.'
                });

                return;
            }

            fetch(
                `/flights/schedule/${scheduleId}/block-time/request`,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': csrfToken,

                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({
                        hours: hours
                    })
                }
            )
                .then(async (response) => {

                    const data = await response.json();

                    if (!response.ok || !data.success) {

                        throw new Error(
                            data.message ||
                            'Unable to submit request.'
                        );
                    }

                    return data;
                })

                .then((data) => {

                    Swal.fire({
                        icon: 'success',
                        title: 'Request Submitted',
                        text: data.message
                    });

                    const status =
                        document.getElementById(
                            'blockTimeRequestStatus'
                        );

                    if (status) {

                        status.textContent =
                            'Pending admin approval';
                    }
                })

                .catch((error) => {

                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: error.message
                    });
                });
        }
    </script>

@endpush