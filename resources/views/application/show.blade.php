@extends('layout')

@section('content')

<div class="container py-4">

    {{-- ============================================================
    PAGE HEADER
    ============================================================= --}}

    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="mb-1">
                Flight School Application
            </h2>

            <p class="text-muted mb-0">
                Review applicant enrollment and aviation information.
            </p>

        </div>

        <a href="{{ route('applications.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-2"></i>
            Back to Applications

        </a>

    </div>


    {{-- ============================================================
    FLASH MESSAGES
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
    APPLICATION HEADER CARD
    ============================================================= --}}

    <div class="card card-light shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-primary text-white
                                   d-flex align-items-center justify-content-center"
                            style="width:65px;height:65px;font-size:25px;">

                            {{ strtoupper(substr($application->name, 0, 1)) }}

                        </div>

                        <div>

                            <h3 class="mb-1">
                                {{ $application->name }}
                            </h3>

                            <div class="text-muted">

                                <i class="fas fa-envelope me-1"></i>
                                {{ $application->email }}

                                <span class="mx-2">•</span>

                                <i class="fas fa-phone me-1"></i>
                                {{ $application->phone }}

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    @switch($application->status)

                        @case('approved')

                            <span class="badge bg-success fs-6 px-3 py-2">

                                <i class="fas fa-check-circle me-1"></i>
                                Approved

                            </span>

                            @break

                        @case('rejected')

                            <span class="badge bg-danger fs-6 px-3 py-2">

                                <i class="fas fa-times-circle me-1"></i>
                                Rejected

                            </span>

                            @break

                        @case('onboarding_completed')

                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">

                                <i class="fas fa-file-circle-check me-1"></i>
                                Ready for Review

                            </span>

                            @break

                        @default

                            <span class="badge bg-secondary fs-6 px-3 py-2">

                                {{ ucwords(str_replace('_', ' ', $application->status)) }}

                            </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
    APPLICATION INFORMATION
    ============================================================= --}}

    <div class="card card-light shadow-sm mb-4">

        <div class="card-header">

            <h5 class="gold-text mb-0">

                <i class="fas fa-file-alt me-2"></i>

                Application Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Application ID
                    </small>

                    <strong>
                        #{{ $application->id }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Program
                    </small>

                    <strong>
                        {{ $application->program ?? 'Not specified' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Application Date
                    </small>

                    <strong>
                        {{ $application->created_at?->format('M d, Y H:i') }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Preferred Start Date
                    </small>

                    <strong>
                        {{ $application->preferred_start_date?->format('M d, Y') ?? 'Not specified' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Onboarding Completed
                    </small>

                    <strong>
                        {{ $application->onboarding_completed_at?->format('M d, Y H:i') ?? 'Not completed' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Current Status
                    </small>

                    <strong>
                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                    </strong>

                </div>

            </div>


            @if($application->message)

                <hr>

                <small class="text-muted d-block mb-1">
                    Applicant Message
                </small>

                <p class="mb-0">
                    {{ $application->message }}
                </p>

            @endif

        </div>

    </div>


    {{-- ============================================================
    ONBOARDING PROFILE
    ============================================================= --}}

    @php
        $detail = $application->onboardingDetail;
    @endphp


    @if(!$detail)

        <div class="alert alert-warning">

            <i class="fas fa-exclamation-triangle me-2"></i>

            This applicant has not completed the detailed onboarding
            information yet.

        </div>

    @else


        {{-- ========================================================
        PERSONAL INFORMATION
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-user me-2"></i>

                    Personal Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Full Name
                        </small>

                        <strong>
                            {{ $application->name }}
                            {{ $detail->middle_name }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Date of Birth
                        </small>

                        <strong>
                            {{ $detail->date_of_birth?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Gender
                        </small>

                        <strong>
                            {{ $detail->gender ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Nationality
                        </small>

                        <strong>
                            {{ $detail->nationality ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Country
                        </small>

                        <strong>
                            {{ $detail->country ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Phone
                        </small>

                        <strong>
                            {{ $application->phone }}
                        </strong>

                    </div>


                    <div class="col-12 mb-3">

                        <small class="text-muted d-block">
                            Address
                        </small>

                        <strong>

                            {{ $detail->address ?? '—' }}

                            @if($detail->city)
                                , {{ $detail->city }}
                            @endif

                            @if($detail->state)
                                , {{ $detail->state }}
                            @endif

                            @if($detail->postal_code)
                                {{ $detail->postal_code }}
                            @endif

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        IDENTIFICATION
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-passport me-2"></i>

                    Passport / Identification

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Identification Type
                        </small>

                        <strong>
                            {{ $detail->id_type ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            ID Number
                        </small>

                        <strong>
                            {{ $detail->id_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Passport Number
                        </small>

                        <strong>
                            {{ $detail->passport_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Passport Country
                        </small>

                        <strong>
                            {{ $detail->passport_country ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted d-block">
                            Passport Expiry
                        </small>

                        <strong>
                            {{ $detail->passport_expiry?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        EMERGENCY CONTACT
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-phone-volume me-2"></i>

                    Emergency Contact

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Name
                        </small>

                        <strong>
                            {{ $detail->emergency_contact_name ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Relationship
                        </small>

                        <strong>
                            {{ $detail->emergency_contact_relationship ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Phone
                        </small>

                        <strong>
                            {{ $detail->emergency_contact_phone ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Email
                        </small>

                        <strong>
                            {{ $detail->emergency_contact_email ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        PILOT CERTIFICATION
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-id-card me-2"></i>

                    Pilot Certificates & Ratings

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Pilot Certificate
                        </small>

                        <strong>
                            {{ $detail->pilot_certificate ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Certificate Number
                        </small>

                        <strong>
                            {{ $detail->certificate_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Issuing Country
                        </small>

                        <strong>
                            {{ $detail->certificate_country ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Issue Date
                        </small>

                        <strong>
                            {{ $detail->certificate_issue_date?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        MEDICAL CERTIFICATE
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-heart-pulse me-2"></i>

                    Medical Certificate

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Medical Class
                        </small>

                        <strong>
                            {{ $detail->medical_certificate_class ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Issuing Authority / AME
                        </small>

                        <strong>
                            {{ $detail->medical_issuer ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Issue Date
                        </small>

                        <strong>
                            {{ $detail->medical_certificate_issue_date?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Expiry Date
                        </small>

                        <strong>
                            {{ $detail->medical_certificate_expiry?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        FLIGHT EXPERIENCE
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-plane me-2"></i>

                    Flight Experience

                </h5>

            </div>


            <div class="card-body">

                <div class="row text-center">

                    @php

                        $flightExperience = [

                            'total_flight_hours' =>
                                'Total Flight',

                            'pilot_in_command_hours' =>
                                'PIC',

                            'dual_instruction_hours' =>
                                'Dual Instruction',

                            'solo_hours' =>
                                'Solo',

                            'cross_country_hours' =>
                                'Cross Country',

                            'night_hours' =>
                                'Night',

                            'instrument_hours' =>
                                'Instrument',

                            'multi_engine_hours' =>
                                'Multi Engine',

                        ];

                    @endphp


                    @foreach($flightExperience as $field => $label)

                        <div class="col-6 col-md-3 mb-4">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted small mb-1">
                                    {{ $label }}
                                </div>

                                <div class="fs-4 fw-bold gold-text">

                                    {{ number_format(
                                        (float)($detail->{$field} ?? 0),
                                        1
                                    ) }}

                                    <small class="fs-6">
                                        hrs
                                    </small>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================
        PREVIOUS TRAINING
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-graduation-cap me-2"></i>

                    Previous Flight Training

                </h5>

            </div>


            <div class="card-body">

                <div class="mb-4">

                    <small class="text-muted d-block">
                        Previous Flight School
                    </small>

                    <strong>
                        {{ $detail->previous_flight_school ?? '—' }}
                    </strong>

                </div>


                <div class="mb-4">

                    <small class="text-muted d-block">
                        Previous Instructor
                    </small>

                    <strong>
                        {{ $detail->previous_instructor ?? '—' }}
                    </strong>

                </div>


                <div class="mb-4">

                    <small class="text-muted d-block">
                        Previous Training
                    </small>

                    <div class="border rounded p-3">

                        {!! nl2br(e(
                            $detail->previous_training ?? 'No information provided.'
                        )) !!}

                    </div>

                </div>


                <div class="mb-4">

                    <small class="text-muted d-block">
                        Ratings / Endorsements
                    </small>

                    <div class="border rounded p-3">

                        {!! nl2br(e(
                            $detail->ratings_endorsements ?? 'None provided.'
                        )) !!}

                    </div>

                </div>


                <div>

                    <small class="text-muted d-block">
                        Training Goals
                    </small>

                    <div class="border rounded p-3">

                        {!! nl2br(e(
                            $detail->training_goals ?? 'No training goals provided.'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
        DOCUMENTS
        ========================================================= --}}

        <div class="card card-light shadow-sm mb-4">

            <div class="card-header">

                <h5 class="gold-text mb-0">

                    <i class="fas fa-folder-open me-2"></i>

                    Uploaded Documents

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    @php

                        $documents = [

                            'identity_document_path' => [
                                'title' => 'Passport / Government ID',
                                'icon' => 'fa-passport',
                            ],

                            'pilot_certificate_document_path' => [
                                'title' => 'Pilot Certificate',
                                'icon' => 'fa-id-card',
                            ],

                            'medical_certificate_document_path' => [
                                'title' => 'Medical Certificate',
                                'icon' => 'fa-heart-pulse',
                            ],

                            'logbook_document_path' => [
                                'title' => 'Pilot Logbook',
                                'icon' => 'fa-book',
                            ],

                        ];

                    @endphp


                    @foreach($documents as $field => $document)

                        <div class="col-md-6 mb-3">

                            <div class="border rounded p-3
                                        d-flex justify-content-between
                                        align-items-center gap-3">

                                <div>

                                    <div class="fw-semibold">

                                        <i class="fas {{ $document['icon'] }}
                                           me-2 gold-text"></i>

                                        {{ $document['title'] }}

                                    </div>

                                    @if($detail->{$field})

                                        <small class="text-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Document uploaded

                                        </small>

                                    @else

                                        <small class="text-muted">

                                            <i class="fas fa-minus-circle me-1"></i>

                                            Not uploaded

                                        </small>

                                    @endif

                                </div>


                                @if($detail->{$field})

                                    <a href="{{ asset(
                                        'storage/' . $detail->{$field}
                                    ) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-outline-primary btn-sm">

                                        <i class="fas fa-eye me-1"></i>

                                        View

                                    </a>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================
        ADDITIONAL INFORMATION
        ========================================================= --}}

        @if($detail->additional_information)

            <div class="card card-light shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="gold-text mb-0">

                        <i class="fas fa-info-circle me-2"></i>

                        Additional Information

                    </h5>

                </div>

                <div class="card-body">

                    {!! nl2br(e(
                        $detail->additional_information
                    )) !!}

                </div>

            </div>

        @endif


    @endif


    {{-- ============================================================
    APPLICATION DECISION
    ============================================================= --}}

    <div class="card card-light shadow-sm mb-5">

        <div class="card-header">

            <h5 class="gold-text mb-0">

                <i class="fas fa-gavel me-2"></i>

                Application Decision

            </h5>

        </div>


        <div class="card-body">

            @if($application->status === 'approved')

                <div class="alert alert-success mb-0">

                    <i class="fas fa-check-circle me-2"></i>

                    <strong>
                        Application Approved
                    </strong>

                    <div class="small mt-1">

                        This application has already been approved.
                        No further approval or rejection action is available.

                    </div>

                </div>


            @elseif($application->status === 'rejected')

                <div class="alert alert-danger mb-0">

                    <i class="fas fa-times-circle me-2"></i>

                    <strong>
                        Application Rejected
                    </strong>

                    <div class="small mt-1">

                        This application has been rejected.

                    </div>

                </div>


            @else

                <p class="text-muted">

                    Review the applicant's information and documents before
                    making a final enrollment decision.

                </p>


<div class="d-flex flex-column flex-md-row gap-2">

    @if ($application->status === 'approved')

        {{-- Already Approved --}}
        <button
            type="button"
            class="btn btn-success w-100"
            disabled
        >
            <i class="fas fa-check-circle me-2"></i>
            Application Approved
        </button>

    @elseif ($application->status === 'rejected')

        {{-- Rejected Application --}}
        <button
            type="button"
            class="btn btn-danger w-100"
            disabled
        >
            <i class="fas fa-times-circle me-2"></i>
            Application Rejected
        </button>

        {{-- Optional: allow admin to approve a rejected application --}}
        <form
            id="approve-application"
            method="POST"
            action="{{ route('admin.applications.approve', $application->id) }}"
            class="flex-fill"
        >
            @csrf

            <button
                type="button"
                class="btn btn-outline-success w-100"
                onclick="confirmApplicationDecision('approve')"
            >
                <i class="fas fa-check-circle me-2"></i>
                Approve Application
            </button>
        </form>

    @else

        {{-- APPROVE --}}
        <form
            id="approve-application"
            method="POST"
            action="{{ route('admin.applications.approve', $application->id) }}"
            class="flex-fill"
        >
            @csrf

            <button
                type="button"
                class="btn btn-success w-100"
                onclick="confirmApplicationDecision('approve')"
            >
                <i class="fas fa-check-circle me-2"></i>
                Approve Application
            </button>
        </form>


        {{-- REJECT --}}
        <form
            id="reject-application"
            method="POST"
            action="{{ route('admin.applications.reject', $application->id) }}"
            class="flex-fill"
        >
            @csrf

            <button
                type="button"
                class="btn btn-danger w-100"
                onclick="confirmApplicationDecision('reject')"
            >
                <i class="fas fa-times-circle me-2"></i>
                Reject Application
            </button>
        </form>

    @endif

</div>
            @endif

        </div>

    </div>

</div>


{{-- ================================================================
SWEETALERT
================================================================ --}}

<script>
    function confirmApplicationDecision(action) {

        const formId = action === 'approve'
            ? 'approve-application'
            : 'reject-application';

        const isApprove = action === 'approve';

        Swal.fire({
            title: isApprove
                ? 'Approve Application?'
                : 'Reject Application?',

            text: isApprove
                ? 'This will approve the applicant for the flight training program.'
                : 'This will reject the flight school application.',

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
                    .getElementById(formId)
                    .submit();

            }

        });
    }
</script>
@endsection