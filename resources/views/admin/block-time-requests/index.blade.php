@extends('layout')

@section('content')

<div class="container">

{{-- HEADER --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mt-3">

    <h3 class="mb-3 mb-md-0">
        <i class="bi bi-clock-history me-2"></i>
        Block Time Requests
    </h3>

    <div class="d-flex flex-wrap gap-2">

        <a href="{{ route('logbooks.index') }}"
           class="btn btn-success shadow-sm">
            <i class="bi bi-journal-text me-1"></i>
            Logbooks
        </a>

    </div>

</div>

{{-- SUCCESS MESSAGE --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>
@endif

{{-- ERROR MESSAGE --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>
@endif

{{-- TABLE --}}
<div class="table-responsive">

    <table class="table table-bordered table-striped align-middle text-center">

        <thead class="table-light">

            <tr>
                <th>#</th>
                <th>Requested By</th>
                <th>Flight</th>
                <th>Schedule</th>
                <th>Hours</th>
                <th>Status</th>
                <th>Requested</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>

            @forelse($blockTimeRequests as $request)

                <tr>

                    {{-- SN --}}
                    <td>
                        {{ $loop->iteration + ($blockTimeRequests->currentPage() - 1) * $blockTimeRequests->perPage() }}
                    </td>

                    {{-- REQUESTED BY --}}
                    <td>
                        <strong>
                            {{ $request->requester?->name ?? 'Unknown' }}
                        </strong>
                    </td>

                    {{-- FLIGHT --}}
                    <td>
                        {{ $request->flight?->id ?? 'N/A' }}
                    </td>

                    {{-- SCHEDULE --}}
                    <td>
                        #{{ $request->schedule_id ?? 'N/A' }}
                    </td>

                    {{-- HOURS --}}
                    <td>
                        <span class="fw-bold text-primary">
                            {{ number_format($request->hours, 1) }}
                        </span>
                        hrs
                    </td>

                    {{-- STATUS --}}
                    <td>

                        @if ($request->isPending())

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @elseif ($request->isApproved())

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif ($request->isRejected())

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @endif

                    </td>

                    {{-- REQUESTED DATE --}}
                    <td>
                        {{ $request->created_at?->format('d M Y H:i') ?? 'N/A' }}
                    </td>

                    {{-- ACTION --}}
                    <td>

                        @if ($request->isPending())

                            <form
                                action="{{ route(
                                    'admin.block-time-requests.approve',
                                    $request->id
                                ) }}"
                                method="POST"
                                class="approve-block-time-form d-inline"
                            >

                                @csrf

                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-success"
                                >
                                    <i class="bi bi-check-circle me-1"></i>
                                    Approve
                                </button>

                            </form>

                        @elseif ($request->isApproved())

                            <span class="text-success">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Approved
                            </span>

                        @else

                            <span class="text-danger">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Rejected
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="text-center text-muted py-4">

                        <i class="bi bi-clock-history fs-2 d-block mb-2"></i>

                        No block-time requests found.

                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- PAGINATION --}}
@if ($blockTimeRequests->hasPages())

    <div class="card-footer d-flex justify-content-center mt-3">

        {{ $blockTimeRequests->withQueryString()->links() }}

    </div>

@endif

</div>
@endsection
