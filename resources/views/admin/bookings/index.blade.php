@extends('layout')

@section('content')
<div class="container mt-5 mb-5">

    {{-- Alerts --}}
    @if(session('success'))
        <div id="success-alert" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm"
             style="z-index:1050; max-width:90%; width:300px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm"
             style="z-index:1050; max-width:90%; width:300px;">
            {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(() => {
            ['success-alert','error-alert'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 5000);
    </script>

    {{-- Header + Search --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <h2 class="mb-3 mb-md-0 text-center text-md-start">Booked Flights</h2>

        <form method="GET" class="input-group w-100 w-md-auto" style="max-width:300px;">
            <input type="text"
                   name="reference"
                   class="form-control"
                   placeholder="Search by reference"
                   value="{{ request('reference') }}">
            <button class="btn btn-dark">Search</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Reference</th>
                    <th class="d-none d-md-table-cell">User</th>
                    <th class="d-none d-lg-table-cell">Flight</th>
                    <th class="d-none d-md-table-cell">Booked At</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    {{-- Reference + mobile details --}}
                    <td class="fw-semibold">
                        {{ $booking->reference }}

                        <div class="d-block d-md-none mt-1 text-muted small">
                            {{ $booking->user->name ?? '' }}<br>
                            {{ $booking->flight->departure_location ?? '' }}
                            →
                            {{ $booking->flight->arrival_location ?? '' }}
                        </div>
                    </td>

                    {{-- User --}}
                    <td class="d-none d-md-table-cell">
                        {{ $booking->user->name ?? 'N/A' }}<br>
                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                    </td>

                    {{-- Flight --}}
                    <td class="d-none d-lg-table-cell">
                        {{ $booking->flight->aircraft_model ?? 'N/A' }}
                        ({{ $booking->flight->flight_number ?? '' }})<br>
                        <small class="text-muted">
                            {{ $booking->flight->departure_location ?? '' }}
                            →
                            {{ $booking->flight->arrival_location ?? '' }}
                        </small>
                    </td>

                    {{-- Date --}}
                    <td class="d-none d-md-table-cell">
                        {{ \Carbon\Carbon::parse($booking->booked_at)->format('d M Y H:i') }}
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        <span class="badge {{ $booking->status === 'Cancelled' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">

                            {{-- View --}}
                                    <form method="GET" action="{{ route('admin.bookings.show', $booking->id) }}" class="d-inline">
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"
                                                title="View Flight">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </form>

                            {{-- Cancel --}}
                            <form class="cancel-booking-form"
                                  data-action="{{ route('admin.booking.cancel', $booking->id) }}">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-danger px-3 cancel-booking-btn"
                                        title="Cancel Booking">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No bookings found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
</div>

{{-- Mobile table spacing --}}
<style>
@media (max-width: 576px) {
    table td, table th {
        padding: 0.6rem;
        font-size: 0.85rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cancel-booking-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            const form = this.closest('form');
            const actionUrl = form.dataset.action;
            const row = this.closest('tr');
            const button = this;

            Swal.fire({
                title: 'Cancel Booking',
                input: 'textarea',
                inputLabel: 'Reason for cancellation (optional)',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No'
            }).then(result => {

                if (!result.isConfirmed) return;

                button.disabled = true;
                button.innerHTML = '<i class="bi bi-arrow-repeat"></i>';

                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _method: 'DELETE',
                        reason: result.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error();
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                    button.disabled = false;
                    button.innerHTML = '<i class="bi bi-x-circle"></i>';
                });
            });
        });
    });

});
</script>
@endsection
