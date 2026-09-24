@extends('layout')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Manage Users</h2>

    <div class="d-flex gap-2">
        <a href="{{ route('applications.index') }}" class="btn btn-primary">
            📋 Applications
        </a>

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ← Back
        </a>
    </div>
</div>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    let alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.transition = "opacity 0.5s ease";
                        alertBox.style.opacity = "0";
                        setTimeout(() => alertBox.remove(), 500);
                    }
                }, 5000);
            </script>
        @endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 users-table">

                <thead class="table-dark">
                    <tr>
                        <th class="text-center text-nowrap">SN#</th>
                        <th class="text-nowrap">Name</th>
                        <th class="text-nowrap">Email</th>
                        <th class="text-center text-nowrap">Role</th>
                        <th class="text-center text-nowrap">Email Verified</th>
                        <th class="text-center text-nowrap">Status</th>
                        <th class="text-center text-nowrap actions-column">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $index => $user)

                        <tr>

                            {{-- SN --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ ($users->firstItem() ?? 0) + $index }}
                            </td>

                            {{-- NAME --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-width-0">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="fw-semibold text-dark text-decoration-none text-truncate user-name">
                                            {{ $user->name }}
                                        </a>
                                    </div>

                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td>
                                <span class="text-muted small user-email">
                                    {{ $user->email }}
                                </span>
                            </td>

                            {{-- ROLE --}}
                            <td class="text-center">
                                <span class="badge bg-light text-dark border role-badge">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            {{-- EMAIL VERIFIED --}}
                            <td class="text-center">

                                @if ($user->email_verified_at)

                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Verified
                                    </span>

                                @else

                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="fas fa-clock me-1"></i>
                                        Pending
                                    </span>

                                @endif

                            </td>

                            {{-- USER STATUS --}}
                            <td class="text-center">

                                @if ($user->status === 'approved')

                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Approved
                                    </span>

                                @elseif ($user->status === 'inactive')

                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user-slash me-1"></i>
                                        Inactive
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($user->status) }}
                                    </span>

                                @endif

                            </td>

                            {{-- ACTIONS --}}
                            <td>

                                <div class="user-actions">

                                    {{-- =====================================================
                                    FLIGHT SCHOOL APPLICATION
                                    ====================================================== --}}

                                    @if ($user->application)

                                        @php
                                            $applicationStatus = $user->application->status;

                                            $applicationStatusConfig = match ($applicationStatus) {
                                                'approved' => [
                                                    'class' => 'bg-success',
                                                    'icon'  => 'fas fa-check-circle',
                                                    'label' => 'Approved',
                                                ],

                                                'rejected' => [
                                                    'class' => 'bg-danger',
                                                    'icon'  => 'fas fa-times-circle',
                                                    'label' => 'Rejected',
                                                ],

                                                'onboarding_completed' => [
                                                    'class' => 'bg-primary',
                                                    'icon'  => 'fas fa-file-check',
                                                    'label' => 'Ready',
                                                ],

                                                default => [
                                                    'class' => 'bg-warning text-dark',
                                                    'icon'  => 'fas fa-clock',
                                                    'label' => ucwords(
                                                        str_replace('_', ' ', $applicationStatus)
                                                    ),
                                                ],
                                            };
                                        @endphp

                                        {{-- =====================================================
                                            APPLICATION ACTIONS
                                        ====================================================== --}}

                                        <div class="application-actions">

                                            {{-- View Application --}}
                                            <a href="{{ route('admin.applications.show', $user->application->id) }}"
                                            class="btn btn-info btn-sm action-btn"
                                            title="View flight school application"
                                            aria-label="View flight school application">

                                                <i class="fas fa-user-graduate"></i>

                                                <span>Application</span>

                                            </a>


                                            {{-- Application Status --}}
                                            <span class="badge {{ $applicationStatusConfig['class'] }} status-badge"
                                                title="Application status: {{ $applicationStatusConfig['label'] }}">

                                                <i class="{{ $applicationStatusConfig['icon'] }}"></i>

                                                <span>
                                                    {{ $applicationStatusConfig['label'] }}
                                                </span>

                                            </span>

                                        </div>

                                    @endif



                                    {{-- =====================================================
                                    APPROVE / APPROVED
                                    ====================================================== --}}

                                    @if ($user->status !== 'approved')

                                        <form id="user-action-form-{{ $user->id }}-approve"
                                              action="{{ route('users.approve', $user->id) }}"
                                              method="POST"
                                              class="action-form">

                                            @csrf

                                            <button type="button"
                                                    class="btn btn-success btn-sm action-btn"
                                                    onclick="confirmAction(
                                                        'approve',
                                                        {{ $user->id }},
                                                        'Approve this user?'
                                                    )">

                                                <i class="fas fa-check"></i>
                                                <span>Approve</span>

                                            </button>

                                        </form>

                                    @else

                                        <button type="button"
                                                class="btn btn-success btn-sm action-btn"
                                                disabled>

                                            <i class="fas fa-check-circle"></i>
                                            <span>Approved</span>

                                        </button>

                                    @endif


                                    {{-- =====================================================
                                    DISAPPROVE
                                    ====================================================== --}}

                                    @if ($user->status !== 'approved')

                                        <form id="user-action-form-{{ $user->id }}-disapprove"
                                              action="{{ route('users.disapprove', $user->id) }}"
                                              method="POST"
                                              class="action-form">

                                            @csrf

                                            <button type="button"
                                                    class="btn btn-warning btn-sm action-btn"
                                                    onclick="confirmAction(
                                                        'disapprove',
                                                        {{ $user->id }},
                                                        'Disapprove this user?'
                                                    )">

                                                <i class="fas fa-ban"></i>
                                                <span>Disapprove</span>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- =====================================================
                                    ACTIVATE / DEACTIVATE
                                    ====================================================== --}}

                                    @if ($user->status !== 'inactive')

                                        <form id="user-action-form-{{ $user->id }}-deactivate"
                                              action="{{ route('users.deactivate', $user->id) }}"
                                              method="POST"
                                              class="action-form">

                                            @csrf

                                            <button type="button"
                                                    class="btn btn-danger btn-sm action-btn"
                                                    onclick="confirmAction(
                                                        'deactivate',
                                                        {{ $user->id }},
                                                        'Deactivate this user?'
                                                    )">

                                                <i class="fas fa-user-slash"></i>
                                                <span>Deactivate</span>

                                            </button>

                                        </form>

                                    @else

                                        <form id="user-action-form-{{ $user->id }}-activate"
                                              action="{{ route('users.activate', $user->id) }}"
                                              method="POST"
                                              class="action-form">

                                            @csrf

                                            <button type="button"
                                                    class="btn btn-primary btn-sm action-btn"
                                                    onclick="confirmAction(
                                                        'activate',
                                                        {{ $user->id }},
                                                        'Activate this user?'
                                                    )">

                                                <i class="fas fa-user-check"></i>
                                                <span>Activate</span>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-users-slash fa-2x mb-3 opacity-50"></i>

                                    <div class="fw-semibold">
                                        No users found
                                    </div>

                                    <small>
                                        There are currently no users to display.
                                    </small>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>


{{-- =========================================================
RESPONSIVE TABLE STYLING
========================================================= --}}

<style>
    .user-name {
    max-width: 180px;
    display: block;
    color: #212529 !important;
    transition: color 0.2s ease, text-decoration 0.2s ease;
}

.user-name:hover {
    color: #0d6efd !important;
    text-decoration: underline !important;
}

    /* ---------------------------------------------------------
       USER AVATAR
    --------------------------------------------------------- */

    .user-avatar {
        width: 36px;
        height: 36px;
        min-width: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #072743;
        color: #fff;

        font-size: .85rem;
        font-weight: 600;
    }


    /* ---------------------------------------------------------
       TABLE
    --------------------------------------------------------- */

    .users-table {
        font-size: .9rem;
    }

    .users-table thead th {
        padding: .85rem .75rem;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .02em;
        vertical-align: middle;
    }

    .users-table tbody td {
        padding: .75rem;
    }

    .users-table tbody tr {
        transition: background-color .15s ease;
    }

    .application-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-start;
    gap: .4rem;
    margin: 0;
    vertical-align: middle;
}

.application-actions .action-btn,
.application-actions .status-badge {
    margin: 0;
}

.application-actions .status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .3rem;

    min-height: 32px;

    line-height: 1.2;
    white-space: nowrap;
}


    /* ---------------------------------------------------------
       USER TEXT
    --------------------------------------------------------- */

    .user-name {
        max-width: 180px;
        color: #3b9ff7 !important;
    }

    .user-email {
        white-space: nowrap;
        font-size: .9rem;
    }

    .role-badge {
        font-weight: 500;
        font-size: .9rem;
    }


   /* ---------------------------------------------------------
   ACTIONS
--------------------------------------------------------- */

.actions-column {
    min-width: 260px;
}

.user-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
    gap: .4rem;
}

.action-form {
    margin: 0;
    display: flex;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    white-space: nowrap;
    border-radius: .45rem;
    padding: .35rem .65rem;
    font-size: .82rem;
    font-weight: 500;
    transition:
        transform .15s ease,
        box-shadow .15s ease;
}

.action-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 .2rem .45rem rgba(0, 0, 0, .12);
}

.status-badge {
    font-size: .82rem;
    white-space: nowrap;
    padding: .4rem .55rem;
}


/* ---------------------------------------------------------
   TABLET
--------------------------------------------------------- */

@media (max-width: 991.98px) {

    .users-table {
        font-size: .8rem;
    }

    .users-table thead th {
        font-size: .72rem;
        padding: .7rem .6rem;
    }

    .users-table tbody td {
        padding: .65rem .6rem;
    }

    .user-name {
        max-width: 140px;
    }

    .actions-column {
        min-width: 230px;
    }

    .user-actions {
        gap: .3rem;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {

    .actions-column {
        min-width: 0 !important;
        width: 1%;
        white-space: nowrap;
    }

    .user-actions {
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: nowrap;

        min-width: 0 !important;
        width: auto !important;

        gap: .35rem;
        white-space: nowrap;
    }

    .action-form {
        display: inline-flex;
        align-items: center;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Small icon buttons */
    .action-btn {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        min-height: 32px !important;

        padding: 0 !important;
        margin: 0 !important;

        display: inline-flex !important;
        align-items: center;
        justify-content: center;

        border-radius: .4rem;

        font-size: 0 !important;
        line-height: 1;
    }

    /* Hide text */
    .action-btn span {
        display: none !important;
    }

    /* Icon */
    .action-btn i {
        display: inline-block !important;
        margin: 0 !important;
        font-size: .9rem !important;
        line-height: 1;
    }

    /* Application + status */
    .application-actions {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin: 0 !important;
        white-space: nowrap;
    }

    .application-actions .status-badge {
        min-height: 32px;
        padding: .3rem .45rem;
        font-size: .75rem;
    }
}


/* =========================================================
   VERY SMALL PHONES
========================================================= */

@media (max-width: 575.98px) {

    .actions-column {
        min-width: 0 !important;
        width: 1%;
    }

    .user-actions {
        gap: .3rem;
    }

    .action-btn {
        width: 30px !important;
        height: 30px !important;
        min-width: 30px !important;
        min-height: 30px !important;
        border-radius: .35rem;
    }

    .action-btn i {
        font-size: .85rem !important;
    }
}
/* ---------------------------------------------------------
   VERY SMALL PHONES
--------------------------------------------------------- */

@media (max-width: 575.98px) {

    .users-table thead th,
    .users-table tbody td {
        padding: .5rem .4rem;
    }

    .user-name {
        max-width: 100px;
    }

    .user-actions {
        gap: .5rem;
    }

    .action-btn {
        width: auto !important;
        height: auto !important;
        padding: 0 !important;
    }

    .action-btn i {
        font-size: .95rem;
    }

    .status-badge {
        padding: .3rem .4rem;
        font-size: .65rem;
    }
}
</style>


{{-- =========================================================
PAGINATION
========================================================= --}}

<div class="mt-3 d-flex justify-content-center justify-content-md-end">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
    </div>
@endsection
<script>
    function confirmAction(action, userId, message) {

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, proceed'
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById(
                    'user-action-form-' + userId + '-' + action
                ).submit();
            }
        });
    }
</script>