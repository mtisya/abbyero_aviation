@extends('layout')

@section('content')
    <div class="container mt-5 mb-5">
        {{-- Alerts --}}
        @if(session('success'))
            <div id="success-alert" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm"
                style="z-index: 1050; max-width: 90%; width: 300px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm"
                style="z-index: 1050; max-width: 90%; width: 300px;">
                {{ session('error') }}
            </div>
        @endif

        <style>
            .table-responsive {
                border-radius: 12px;
            }

            .table th {
                white-space: nowrap;
                font-size: 0.85rem;
            }

            .table td {
                font-size: 0.9rem;
            }

            .btn-sm {
                min-width: 38px;
            }

            @media(max-width:768px) {

                .table td,
                .table th {
                    padding: .65rem .5rem;
                }

            }

            #aircraftSummaryModal .modal-dialog {
                max-width: 90%;
            }

            #aircraftSummaryModal .modal-content {
                border-radius: 6px;
                height: 85vh;
            }

            #aircraftSummaryModal .modal-header {
                padding: 1rem 1.5rem;
            }

            #aircraftSummaryModal .modal-body {
                overflow: hidden;
            }

            #aircraftSummaryModal .table-responsive {
                height: 100%;
            }

            #aircraftSummaryModal .nav-tabs .nav-link {
                color: #555;
            }

            #aircraftSummaryModal .nav-tabs .nav-link.active {
                font-weight: 600;
                border-bottom: 3px solid #0d6efd;
            }

            #aircraftSummaryModal .form-control {
                max-width: 250px;
            }
        </style>

        <script>
            setTimeout(function () {
                const successAlert = document.getElementById('success-alert');
                const errorAlert = document.getElementById('error-alert');

                if (successAlert) {
                    successAlert.style.transition = 'opacity 0.5s ease';
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500);
                }

                if (errorAlert) {
                    errorAlert.style.transition = 'opacity 0.5s ease';
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500);
                }
            }, 5000);
        </script>

        <h2 class="mb-4 text-center text-md-start">Aircraft for Booking And Scheduling</h2>
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

            <a href="javascript:history.back()"
            class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            @auth
                @if(in_array(Auth::user()->role, ['admin', 'instructor']))

                    <div class="d-flex gap-2">

                        <a href="{{ route('flights.create') }}"
                        class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add New Flight
                        </a>

                        <a href="{{ route('flight.schedules') }}"
                        class="btn btn-success">
                            <i class="bi bi-calendar-event me-1"></i>
                            Schedule Flight
                        </a>

                    </div>

                @endif
            @endauth

        </div>
        {{-- Responsive table wrapper --}}
        <div class="table-responsive rounded shadow-sm">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Aircraft</th>
                        <th class="text-center">Hobbs</th>
                        <th class="text-center">Tach</th>
                        <th>Next Maintenance</th>
                        <th class="text-center">Remaining</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Status</th>
                        <th class="text-center text-nowrap">Actions</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($flights as $index => $flight)

                        <tr>

                            <td class="text-center fw-bold">
                                {{ $index + 1 }}
                            </td>


                            {{-- Aircraft --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $flight->aircraft_model }}
                                </div>

                                <small class="text-muted">
                                    <i class="bi bi-upc-scan"></i>
                                    {{ $flight->registration_number }}
                                </small>
                            </td>

                            {{-- Hobbs --}}
                            <td class="text-center">
                                <span class="badge bg-primary">
                                    {{ number_format($flight->current_hobbs, 2) }}
                                </span>
                            </td>


                            {{-- Tach --}}
                            <td class="text-center">

                                <span class="badge bg-info text-dark">
                                    {{ number_format($flight->current_tach, 2) }}
                                </span>

                            </td>



                            {{-- Maintenance --}}
                            <td>

                                @if($flight->maintenance_type)

                                    <div class="fw-semibold">
                                        {{ $flight->maintenance_type }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        No scheduled maintenance
                                    </span>

                                @endif

                            </td>



                            {{-- Remaining --}}
                            <td class="text-center">

                                @if($flight->maintenance_due)

                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Due
                                    </span>


                                @elseif($flight->maintenance_warning)

                                    <span class="badge bg-warning text-dark px-3 py-2">

                                        <i class="bi bi-clock"></i>

                                        {{ number_format($flight->hours_remaining, 1) }}
                                        hrs

                                    </span>


                                @else

                                    <span class="badge bg-success px-3 py-2">

                                        {{ number_format($flight->hours_remaining, 1) }}
                                        hrs

                                    </span>

                                @endif

                            </td>



                            {{-- Rate --}}
                            <td class="text-center">

                                <span class="fw-semibold">
                                    ${{ number_format($flight->hourly_rate, 2) }}
                                </span>

                            </td>



                            {{-- Status --}}
                            @php

                                $status = strtolower($flight->display_status);

                                $statusClass = match ($status) {

                                    'available' => 'success',

                                    'due soon' => 'warning',

                                    'maintenance due',
                                    'maintenance',
                                    'grounded' => 'danger',

                                    default => 'secondary'

                                };

                            @endphp


                            <td class="text-center">

                                <span class="badge bg-{{ $statusClass }} px-3 py-2">

                                    {{ ucfirst($flight->display_status) }}

                                </span>

                            </td>



                            {{-- Actions --}}
                            <td>

                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                    @auth

                                                        <button type="button" class="btn btn-sm btn-primary text-nowrap"
                                                            onclick="openAircraftSummary({{ $flight->id }})">

                                                            <i class="bi bi-eye"></i>

                                                            <span class="d-none d-lg-inline">
                                                                {{ Auth::user()->role === 'admin'
                                                                ? 'View'
                                                                : 'Schedule'
                                                                }}
                                                            </span>

                                                        </button>



                                                        @if(Auth::user()->role === 'admin')


                                                            <a href="{{ route('flights.edit', $flight->id) }}" class="btn btn-sm btn-warning">

                                                                <i class="bi bi-pencil"></i>

                                                                <span class="d-none d-lg-inline">
                                                                    Edit
                                                                </span>

                                                            </a>



                                                            <form id="delete-form-{{ $flight->id }}"
                                                                action="{{ route('flights.destroy', $flight->id) }}" method="POST" class="d-none">

                                                                @csrf
                                                                @method('DELETE')

                                                            </form>


                                                            <button type="button" class="btn btn-sm btn-danger delete-aircraft-btn"
                                                                data-id="{{ $flight->id }}" data-registration="{{ $flight->registration_number }}"
                                                                data-model="{{ $flight->aircraft_model }}">

                                                                <i class="bi bi-trash"></i>

                                                                <span class="d-none d-lg-inline">
                                                                    Delete
                                                                </span>

                                                            </button>


                                                        @endif

                                    @endauth

                                </div>

                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-5 text-muted">

                                <i class="bi bi-airplane fs-2"></i>

                                <br>

                                No aircraft available.

                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>
    </div>
    @include('admin.modals.view-aircraft-modal')

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.addEventListener('click', function (e) {

            const btn = e.target.closest('.delete-aircraft-btn');

            if (!btn) return;

            const aircraftId = btn.dataset.id;
            const registration = btn.dataset.registration;
            const model = btn.dataset.model;

            Swal.fire({
                title: 'Delete Aircraft?',
                html: `
                <div class="text-start">
                    <p><strong>Aircraft:</strong> ${model}</p>
                    <p><strong>Registration:</strong> ${registration}</p>

                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        This action cannot be undone.
                    </div>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete aircraft',
                cancelButtonText: 'Keep aircraft',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {

                if (result.isConfirmed) {
                    document
                        .getElementById(`delete-form-${aircraftId}`)
                        .submit();
                }

            });

        });

    });
</script>