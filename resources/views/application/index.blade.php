@extends('layout')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Flight School Applications
            </h2>

            <p class="text-muted mb-0">
                Manage student applications and onboarding profiles.
            </p>
        </div>

        <a href="{{ url()->previous() }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Success message --}}
    @if(session('success'))

        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- Error message --}}
    @if(session('error'))

        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>

    @endif


    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th>SN#</th>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Program</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th width="220">
                        Actions
                    </th>
                </tr>

            </thead>


            <tbody>

                @forelse($applications as $index => $application)

                    <tr>

                        <td>
                            {{ ($applications->firstItem() ?? 0) + $index }}
                        </td>


                        <td>
                            <strong>
                                {{ $application->name }}
                            </strong>
                        </td>


                        <td>
                            {{ $application->email }}
                        </td>


                        <td>
                            {{ $application->program ?? '—' }}
                        </td>


                        <td>
                            {{ $application->created_at?->format('M d, Y') }}
                        </td>


                        <td>

                            @switch($application->status)

                                @case('submitted')
                                    <span class="badge bg-secondary">
                                        Submitted
                                    </span>
                                    @break

                                @case('onboarding_sent')
                                    <span class="badge bg-info">
                                        Onboarding Sent
                                    </span>
                                    @break

                                @case('onboarding_started')
                                    <span class="badge bg-primary">
                                        Onboarding Started
                                    </span>
                                    @break

                                @case('onboarding_completed')
                                    <span class="badge bg-warning text-dark">
                                        Onboarding Completed
                                    </span>
                                    @break

                                @case('approved')
                                    <span class="badge bg-success">
                                        Approved
                                    </span>
                                    @break

                                @case('rejected')
                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($application->status) }}
                                    </span>

                            @endswitch

                        </td>


                        <td>

                            <div class="d-flex flex-column gap-2">

                                {{-- View Profile --}}
                                <a href="{{ route('admin.applications.show', $application->id) }}"
                                class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    View Profile
                                </a>


                                {{-- Approval / Rejection --}}
                                @if($application->status === 'approved')

                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        disabled>

                                        <i class="fas fa-check-circle me-1"></i>

                                        Approved

                                    </button>

                                @elseif($application->status === 'rejected')

                                    <span class="text-danger small text-center">

                                        <i class="fas fa-times-circle me-1"></i>

                                        Application Rejected

                                    </span>

                                @else

                                    <div class="d-flex gap-1">

                                        {{-- Approve --}}
                                        <form
                                    id="approve-{{ $application->id }}"
                                    method="POST"
                                    action="{{ route('admin.applications.approve', $application->id) }}"
                                    class="flex-fill"
                                >
                                    @csrf

                                    @if ($application->status === 'approved')

                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm w-100"
                                            disabled
                                        >
                                            <i class="fas fa-check-circle me-1"></i>
                                            Approved
                                        </button>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm w-100"
                                            onclick="confirmApplicationAction(
                                                'approve',
                                                {{ $application->id }}
                                            )"
                                        >
                                            <i class="fas fa-check me-1"></i>
                                            Approve
                                        </button>

                                    @endif

                                </form>


                                        {{-- Reject --}}
                                <form
                                    id="reject-{{ $application->id }}"
                                    method="POST"
                                    action="{{ route('admin.applications.reject', $application->id) }}"
                                    class="flex-fill"
                                >
                                    @csrf

                                    @if ($application->status === 'approved')

                                        <button
                                            type="button"
                                            class="btn btn-secondary btn-sm w-100"
                                            disabled
                                        >
                                            <i class="fas fa-lock me-1"></i>
                                            Rejection Disabled
                                        </button>

                                    @elseif ($application->status === 'rejected')

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm w-100"
                                            disabled
                                        >
                                            <i class="fas fa-times-circle me-1"></i>
                                            Rejected
                                        </button>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm w-100"
                                            onclick="confirmApplicationAction(
                                                'reject',
                                                {{ $application->id }}
                                            )"
                                        >
                                            <i class="fas fa-times me-1"></i>
                                            Reject
                                        </button>

                                    @endif

                                </form>
                                    </div>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-muted py-5">

                            <i class="fas fa-inbox fa-2x mb-3"></i>

                            <div>
                                No flight school applications found.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{ $applications->links('pagination::bootstrap-5') }}

</div>


<script>

function confirmApplicationAction(action, applicationId) {

    const isApprove = action === 'approve';

    Swal.fire({

        title: isApprove
            ? 'Approve Application?'
            : 'Reject Application?',

        text: isApprove
            ? 'This will approve the student flight school application.'
            : 'This will reject the student flight school application.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: isApprove
            ? '#198754'
            : '#dc3545',

        cancelButtonColor: '#6c757d',

        confirmButtonText: isApprove
            ? 'Yes, Approve'
            : 'Yes, Reject',

        cancelButtonText: 'Cancel'

    }).then((result) => {

        if (result.isConfirmed) {

            document
                .getElementById(
                    action + '-' + applicationId
                )
                .submit();

        }

    });

}

</script>

@endsection