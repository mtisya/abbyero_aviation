@extends('layout')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mt-3">

        <h3 class="mb-3 mb-md-0">📊 Tach History</h3>

        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('logbooks.index') }}"
               class="btn btn-success shadow-sm">
                <i class="bi bi-journal-text me-1"></i>
                Logbooks
            </a>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">

        <table class="table table-bordered table-striped align-middle text-center">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Aircraft</th>
                    <th>Logbook</th>
                    <th>Old Tach</th>
                    <th>New Tach</th>
                    <th>Difference</th>
                    <th>Updated By</th>
                    <th>Reason</th>
                </tr>
            </thead>

            <tbody>

                @forelse($histories as $history)

                    <tr>

                        {{-- SN --}}
                        <td>
                            {{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}
                        </td>

                        {{-- Date --}}
                        <td>
                            {{ $history->created_at->format('d M Y H:i') }}
                        </td>

                        {{-- Aircraft --}}
                        <td>
                            {{ $history->aircraft->registration_number ?? 'N/A' }}
                        </td>

                        {{-- Logbook --}}
                        <td>
                            #{{ $history->logbook_id ?? 'N/A' }}
                        </td>

                        {{-- Old Tach --}}
                        <td>
                            {{ number_format($history->old_tach, 2) }}
                        </td>

                        {{-- New Tach --}}
                        <td>
                            {{ number_format($history->new_tach, 2) }}
                        </td>

                        {{-- Difference --}}
                        <td>
                            <span class="fw-bold text-primary">
                                {{ number_format($history->new_tach - $history->old_tach, 2) }}
                            </span>
                        </td>

                        {{-- Updated By --}}
                        <td>
                            {{ $history->user->name ?? 'System' }}
                        </td>

                        {{-- Reason --}}
                        <td>
                            {{ $history->reason ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No tach history found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="card-footer d-flex justify-content-center mt-3">
        {{ $histories->withQueryString()->links() }}
    </div>

</div>
@endsection