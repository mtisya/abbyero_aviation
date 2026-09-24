@extends('layout')

@section('content')
    <div class="container-fluid container-lg mt-4 px-3">

        {{-- HEADER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">

            <div>
                <h3 class="mb-1">
                    Maintenance Schedules
                </h3>

                <small class="text-muted">
                    Assign maintenance types to aircraft and track due maintenance cycles.
                </small>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">

                <button class="btn btn-primary flex-fill" data-bs-toggle="modal" data-bs-target="#createSchedule">

                    <i class="bi bi-plus-circle"></i>
                    Add Schedule

                </button>

                <a href="{{ route('aircraftmaintenance.dashboard') }}" class="btn btn-outline-primary flex-fill">

                    <i class="bi bi-arrow-left"></i>
                    Dashboard

                </a>

            </div>

        </div>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
        </script>
        @endif

        {{-- CARD WRAPPER --}}
        <div class="card shadow-sm border-0">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-nowrap">

                <thead class="table-light">
                    <tr>
                        <th>Aircraft</th>
                        <th>Maintenance Type</th>
                        <th>Last Done</th>
                        <th>Last Tach</th>
                        <th>Next Due</th>
                        <th>Next Due Tach</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($schedules as $schedule)

                        @php
                            $status = strtolower($schedule->status ?? 'pending');
                        @endphp

                        <tr>

                            {{-- Aircraft --}}
                            <td>
                                <strong>
                                    {{ $schedule->aircraft->registration_number ?? 'N/A' }}
                                </strong>
                            </td>

                            {{-- Maintenance Type --}}
                            <td>
                                @if($schedule->maintenanceType)
                                    {{ $schedule->maintenanceType->name }}
                                @else
                                    <span class="badge bg-secondary">
                                        Not Assigned
                                    </span>
                                @endif
                            </td>

                            {{-- Last Done Date --}}
                            <td>
                                {{ $schedule->last_done_date
                                    ? \Carbon\Carbon::parse($schedule->last_done_date)->format('d M Y')
                                    : '—' }}
                            </td>

                            {{-- Last Tach --}}
                            <td>
                                {{ $schedule->last_done_tach !== null
                                    ? number_format($schedule->last_done_tach, 1)
                                    : '—' }}
                            </td>

                            {{-- Next Due Date --}}
                            <td>
                                {{ $schedule->next_due_date
                                    ? \Carbon\Carbon::parse($schedule->next_due_date)->format('d M Y')
                                    : 'Tach Based' }}
                            </td>

                            {{-- Next Due Tach --}}
                            <td>
                                {{ $schedule->next_due_tach !== null
                                    ? number_format($schedule->next_due_tach, 1)
                                    : 'Date Based' }}
                            </td>

                            {{-- Status --}}
                            <td>

                                @switch($status)

                                    @case('completed')
                                        <span class="badge bg-success px-2 py-2">
                                            Completed
                                        </span>
                                        @break

                                    @case('overdue')
                                        <span class="badge bg-danger px-2 py-2">
                                            Overdue
                                        </span>
                                        @break

                                    @case('due_soon')
                                        <span class="badge bg-warning text-dark px-2 py-2">
                                            Due Soon
                                        </span>
                                        @break

                                    @default
                                        <span class="badge bg-secondary px-2 py-2">
                                            Pending
                                        </span>

                                @endswitch

                            </td>

                            {{-- Actions --}}
                            <td class="text-center">

                                <div class="d-flex flex-wrap justify-content-center gap-2">

                                    @if($status === 'completed')

                                        <button type="button"
                                                class="btn btn-sm btn-outline-success"
                                                disabled
                                                style="cursor:not-allowed; opacity:1;">
                                            ✔ Performed
                                        </button>

                                    @else

                                        <button
                                            class="btn btn-success btn-sm"
                                            onclick="loadMaintenance({{ $schedule->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#performMaintenanceModal">

                                            <i class="bi bi-tools me-1"></i>
                                            Perform

                                        </button>

                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('maintenance-schedules.destroy', $schedule) }}"
                                        class="d-inline delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-5 text-muted">

                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>

                                <h6 class="mb-1">
                                    No Maintenance Schedules Found
                                </h6>

                                <small>
                                    Click <strong>Add Schedule</strong> to create your first maintenance schedule.
                                </small>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white overflow-auto">
            {{ $schedules->links() }}
        </div>

    </div>

</div>

    </div>

  {{-- CREATE MODAL --}}
<div class="modal fade" id="createSchedule">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <form method="POST" action="{{ route('maintenance-schedules.store') }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Create Maintenance Schedule</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        {{-- AIRCRAFT --}}
                        <div class="col-md-6 mb-3">
                            <label>Aircraft</label>
                            <select name="aircraft_id" class="form-select" required>
                                <option value="">-- Select Aircraft --</option>
                                @foreach($aircraft as $plane)
                                    <option value="{{ $plane->id }}">
                                        {{ $plane->registration_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-6 mb-3">
                            <label>Maintenance Type</label>
                            <select name="maintenance_type_id" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button class="btn btn-primary">
                        Save Schedule
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>
@include('admin.modals.perform-modal')
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.delete-form button');

    if (!btn) return;

    e.preventDefault();

    const form = btn.closest('form');

    Swal.fire({
        title: 'Delete Schedule?',
        text: "This maintenance schedule will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

});
</script>