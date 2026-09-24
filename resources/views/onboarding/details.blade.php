@extends('layout')

@section('content')

<section class="section-padding container pt-5 pb-5">

    <div class="row justify-content-center">

        <div class="col-xl-10">

            <div class="card card-light p-4 p-md-5">

                {{-- =====================================================
                HEADER
                ====================================================== --}}

                <div class="text-center mb-4">

                    <img
                        src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                        alt="Abbyero Aviation"
                        style="max-width: 220px;"
                        class="mb-3"
                    >

                    <h2 class="gold-text mb-2">
                        Student Onboarding
                    </h2>

                    <p class="text-muted mb-1">
                        Welcome,
                        <strong>{{ $application->name }}</strong>
                    </p>

                    <p class="small text-muted mb-0">
                        {{ $application->program ?? 'Flight Training Program' }}
                    </p>

                </div>

                <hr>


                {{-- =====================================================
                INTRODUCTION
                ====================================================== --}}

                <div class="alert alert-info mb-4">

                    <strong>
                        Complete Your Enrollment Information
                    </strong>

                    <p class="mb-0 mt-2">
                        Please complete each section below to provide Abbyero
                        Aviation with the information required to prepare your
                        student enrollment and flight training records.
                    </p>

                </div>


                {{-- =====================================================
                PROGRESS
                ====================================================== --}}

                <div class="onboarding-progress mb-5">

                    <div class="progress" style="height: 8px;">

                        <div
                            id="onboardingProgress"
                            class="progress-bar"
                            role="progressbar"
                            style="width: 11%;"
                        ></div>

                    </div>

                    <div class="d-flex justify-content-between mt-2">

                        <small class="text-muted">
                            Step <span id="currentStep">1</span>
                            of
                            <span id="totalSteps">9</span>
                        </small>

                        <small
                            class="text-muted"
                            id="stepTitle"
                        >
                            Personal Information
                        </small>

                    </div>

                </div>


                {{-- =====================================================
                FORM
                ====================================================== --}}

                <form
                    method="POST"
                    action="{{ route('onboarding.details.store', $token) }}"
                    enctype="multipart/form-data"
                    id="onboardingForm"
                >

                    @csrf


                    {{-- =================================================
                    STEP 1 — PERSONAL INFORMATION
                    ================================================== --}}

                    <div class="onboarding-step active">

                        <h4 class="gold-text mb-3">
                            1. Personal Information
                        </h4>

                        <p class="text-muted">
                            Please confirm your personal information and
                            provide any missing details.
                        </p>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $application->name }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    value="{{ $application->email }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $application->phone }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Middle Name
                                </label>

                                <input
                                    type="text"
                                    name="middle_name"
                                    class="form-control"
                                    value="{{ old('middle_name', $onboardingDetail?->middle_name) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="date_of_birth"
                                    class="form-control"
                                    value="{{ old(
                                        'date_of_birth',
                                        optional($onboardingDetail?->date_of_birth)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Nationality
                                </label>

                                <input
                                    type="text"
                                    name="nationality"
                                    class="form-control"
                                    value="{{ old('nationality', $onboardingDetail?->nationality) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option
                                        value="Male"
                                        @selected(old('gender', $onboardingDetail?->gender) === 'Male')
                                    >
                                        Male
                                    </option>

                                    <option
                                        value="Female"
                                        @selected(old('gender', $onboardingDetail?->gender) === 'Female')
                                    >
                                        Female
                                    </option>

                                    <option
                                        value="Other"
                                        @selected(old('gender', $onboardingDetail?->gender) === 'Other')
                                    >
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Country
                                </label>

                                <input
                                    type="text"
                                    name="country"
                                    class="form-control"
                                    value="{{ old('country', $onboardingDetail?->country) }}"
                                >

                            </div>


                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Address
                                </label>

                                <input
                                    type="text"
                                    name="address"
                                    class="form-control"
                                    value="{{ old('address', $onboardingDetail?->address) }}"
                                >

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    City
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="{{ old('city', $onboardingDetail?->city) }}"
                                >

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    State / Province
                                </label>

                                <input
                                    type="text"
                                    name="state"
                                    class="form-control"
                                    value="{{ old('state', $onboardingDetail?->state) }}"
                                >

                            </div>


                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Postal Code
                                </label>

                                <input
                                    type="text"
                                    name="postal_code"
                                    class="form-control"
                                    value="{{ old('postal_code', $onboardingDetail?->postal_code) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 2 — IDENTIFICATION
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            2. Passport / Identification
                        </h4>

                        <p class="text-muted">
                            Provide the identification information you will
                            use for your enrollment and flight training records.
                        </p>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Identification Type
                                </label>

                                <select
                                    name="id_type"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="Passport">
                                        Passport
                                    </option>

                                    <option value="National ID">
                                        National ID
                                    </option>

                                    <option value="Driver License">
                                        Driver License
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    ID Number
                                </label>

                                <input
                                    type="text"
                                    name="id_number"
                                    class="form-control"
                                    value="{{ old('id_number', $onboardingDetail?->id_number) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Passport Number
                                </label>

                                <input
                                    type="text"
                                    name="passport_number"
                                    class="form-control"
                                    value="{{ old('passport_number', $onboardingDetail?->passport_number) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Passport Country
                                </label>

                                <input
                                    type="text"
                                    name="passport_country"
                                    class="form-control"
                                    value="{{ old('passport_country', $onboardingDetail?->passport_country) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Passport Expiry
                                </label>

                                <input
                                    type="date"
                                    name="passport_expiry"
                                    class="form-control"
                                    value="{{ old(
                                        'passport_expiry',
                                        optional($onboardingDetail?->passport_expiry)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 3 — EMERGENCY CONTACT
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            3. Emergency Contact
                        </h4>

                        <p class="text-muted">
                            Please provide a person Abbyero Aviation can
                            contact in case of an emergency.
                        </p>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="emergency_contact_name"
                                    class="form-control"
                                    required
                                    value="{{ old('emergency_contact_name', $onboardingDetail?->emergency_contact_name) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Relationship <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="emergency_contact_relationship"
                                    class="form-control"
                                    required
                                    value="{{ old('emergency_contact_relationship', $onboardingDetail?->emergency_contact_relationship) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="emergency_contact_phone"
                                    class="form-control"
                                    required
                                    value="{{ old('emergency_contact_phone', $onboardingDetail?->emergency_contact_phone) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="emergency_contact_email"
                                    class="form-control"
                                    value="{{ old('emergency_contact_email', $onboardingDetail?->emergency_contact_email) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 4 — PILOT CERTIFICATION
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            4. Pilot Certificates & Ratings
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Pilot Certificate
                                </label>

                                <input
                                    type="text"
                                    name="pilot_certificate"
                                    class="form-control"
                                    placeholder="e.g. Student Pilot, Private Pilot"
                                    value="{{ old('pilot_certificate', $onboardingDetail?->pilot_certificate) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Certificate Number
                                </label>

                                <input
                                    type="text"
                                    name="certificate_number"
                                    class="form-control"
                                    value="{{ old('certificate_number', $onboardingDetail?->certificate_number) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Certificate Country
                                </label>

                                <input
                                    type="text"
                                    name="certificate_country"
                                    class="form-control"
                                    value="{{ old('certificate_country', $onboardingDetail?->certificate_country) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Certificate Issue Date
                                </label>

                                <input
                                    type="date"
                                    name="certificate_issue_date"
                                    class="form-control"
                                    value="{{ old(
                                        'certificate_issue_date',
                                        optional($onboardingDetail?->certificate_issue_date)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 5 — MEDICAL
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            5. Medical Certificate
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Medical Certificate Class
                                </label>

                                <input
                                    type="text"
                                    name="medical_certificate_class"
                                    class="form-control"
                                    placeholder="e.g. First, Second, Third"
                                    value="{{ old('medical_certificate_class', $onboardingDetail?->medical_certificate_class) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Issuing Authority / AME
                                </label>

                                <input
                                    type="text"
                                    name="medical_issuer"
                                    class="form-control"
                                    value="{{ old('medical_issuer', $onboardingDetail?->medical_issuer) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Issue Date
                                </label>

                                <input
                                    type="date"
                                    name="medical_certificate_issue_date"
                                    class="form-control"
                                    value="{{ old(
                                        'medical_certificate_issue_date',
                                        optional($onboardingDetail?->medical_certificate_issue_date)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Expiry Date
                                </label>

                                <input
                                    type="date"
                                    name="medical_certificate_expiry"
                                    class="form-control"
                                    value="{{ old(
                                        'medical_certificate_expiry',
                                        optional($onboardingDetail?->medical_certificate_expiry)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 6 — FLIGHT EXPERIENCE
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            6. Flight Experience
                        </h4>

                        <p class="text-muted">
                            Enter your approximate flight experience in hours.
                            Use <strong>0</strong> where applicable.
                        </p>

                        <div class="row">

                            @php

                                $flightFields = [

                                    'total_flight_hours'
                                        => 'Total Flight Hours',

                                    'pilot_in_command_hours'
                                        => 'Pilot-in-Command Hours',

                                    'dual_instruction_hours'
                                        => 'Dual Instruction Hours',

                                    'solo_hours'
                                        => 'Solo Hours',

                                    'cross_country_hours'
                                        => 'Cross-Country Hours',

                                    'night_hours'
                                        => 'Night Hours',

                                    'instrument_hours'
                                        => 'Instrument Hours',

                                    'multi_engine_hours'
                                        => 'Multi-Engine Hours',

                                ];

                            @endphp


                            @foreach($flightFields as $field => $label)

                                <div class="col-md-3 mb-3">

                                    <label class="form-label">
                                        {{ $label }}
                                    </label>

                                    <input
                                        type="number"
                                        step="0.1"
                                        min="0"
                                        name="{{ $field }}"
                                        class="form-control"
                                        value="{{ old($field, $onboardingDetail?->{$field}) }}"
                                    >

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 7 — TRAINING HISTORY
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            7. Previous Flight Training
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Previous Flight School
                                </label>

                                <input
                                    type="text"
                                    name="previous_flight_school"
                                    class="form-control"
                                    value="{{ old('previous_flight_school', $onboardingDetail?->previous_flight_school) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Previous Instructor
                                </label>

                                <input
                                    type="text"
                                    name="previous_instructor"
                                    class="form-control"
                                    value="{{ old('previous_instructor', $onboardingDetail?->previous_instructor) }}"
                                >

                            </div>


                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Previous Flight Training
                                </label>

                                <textarea
                                    name="previous_training"
                                    class="form-control"
                                    rows="4"
                                >{{ old('previous_training', $onboardingDetail?->previous_training) }}</textarea>

                            </div>


                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Ratings / Endorsements
                                </label>

                                <textarea
                                    name="ratings_endorsements"
                                    class="form-control"
                                    rows="3"
                                >{{ old('ratings_endorsements', $onboardingDetail?->ratings_endorsements) }}</textarea>

                            </div>


                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Training Goals
                                </label>

                                <textarea
                                    name="training_goals"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Tell us about your aviation and training goals"
                                >{{ old('training_goals', $onboardingDetail?->training_goals) }}</textarea>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 8 — DOCUMENTS
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            8. Required Documents
                        </h4>

                        <p class="text-muted">
                            Upload clear copies of the documents applicable
                            to your training and enrollment.
                        </p>


                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Passport / Government ID
                                </label>

                                <input
                                    type="file"
                                    name="identity_document"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >

                                <small class="text-muted">
                                    PDF, JPG or PNG
                                </small>

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Pilot Certificate
                                </label>

                                <input
                                    type="file"
                                    name="pilot_certificate_document"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Medical Certificate
                                </label>

                                <input
                                    type="file"
                                    name="medical_certificate_document"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Pilot Logbook / Flight Experience
                                </label>

                                <input
                                    type="file"
                                    name="logbook_document"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >

                            </div>

                        </div>

                        <div class="alert alert-warning small mb-0">

                            <strong>Document Security:</strong>

                            Please upload clear and readable documents.
                            Only documents required for enrollment and
                            flight training should be submitted.

                        </div>

                    </div>


                    {{-- =================================================
                    STEP 9 — ADDITIONAL INFORMATION
                    ================================================== --}}

                    <div class="onboarding-step">

                        <h4 class="gold-text mb-3">
                            9. Additional Information & Confirmation
                        </h4>


                        <div class="mb-4">

                            <label class="form-label">
                                Additional Information
                            </label>

                            <textarea
                                name="additional_information"
                                class="form-control"
                                rows="5"
                                placeholder="Anything else Abbyero Aviation should know?"
                            >{{ old('additional_information', $onboardingDetail?->additional_information) }}</textarea>

                        </div>


                        <div class="alert alert-light border">

                            <h5 class="gold-text">
                                Enrollment Confirmation
                            </h5>

                            <p class="mb-0">
                                Please review all information you have
                                provided before completing your onboarding.
                            </p>

                        </div>


                        <div class="form-check mt-4">

                            <input
                                type="checkbox"
                                name="information_confirmed"
                                value="1"
                                class="form-check-input"
                                id="informationConfirmed"
                                required
                            >

                            <label
                                class="form-check-label"
                                for="informationConfirmed"
                            >

                                I confirm that the information provided in
                                this onboarding form is accurate and complete
                                to the best of my knowledge.

                            </label>

                        </div>

                    </div>


                    {{-- =================================================
                    NAVIGATION
                    ================================================== --}}

                    <div class="d-flex justify-content-between align-items-center mt-5">

                        <button
                            type="button"
                            id="previousStep"
                            class="btn btn-outline-secondary"
                        >

                            <i class="fas fa-arrow-left me-2"></i>

                            Previous

                        </button>


                        <a
                            href="{{ route('onboarding.show', $token) }}"
                            class="btn btn-outline-secondary"
                            id="backToStart"
                        >

                            <i class="fas fa-times me-2"></i>

                            Exit

                        </a>


                        <button
                            type="button"
                            id="nextStep"
                            class="btn btn-primary"
                        >

                            Next

                            <i class="fas fa-arrow-right ms-2"></i>

                        </button>


                        <button
                            type="submit"
                            id="submitOnboarding"
                            class="btn btn-success d-none"
                        >

                            <i class="fas fa-check-circle me-2"></i>

                            Complete Onboarding

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>


{{-- =====================================================
TAB / STEP STYLES
====================================================== --}}

<style>

    .onboarding-step {
        display: none;
        animation: onboardingFade .25s ease-in-out;
    }

    .onboarding-step.active {
        display: block;
    }

    @keyframes onboardingFade {

        from {
            opacity: 0;
            transform: translateX(10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }

    }

    #onboardingProgress {
        transition: width .3s ease;
    }

</style>


{{-- =====================================================
STEP NAVIGATION
====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('onboardingForm');

    const steps = Array.from(
        document.querySelectorAll('.onboarding-step')
    );

    const nextButton =
        document.getElementById('nextStep');

    const previousButton =
        document.getElementById('previousStep');

    const submitButton =
        document.getElementById('submitOnboarding');

    const currentStepElement =
        document.getElementById('currentStep');

    const totalStepsElement =
        document.getElementById('totalSteps');

    const progress =
        document.getElementById('onboardingProgress');

    const stepTitle =
        document.getElementById('stepTitle');

    const stepTitles = [

        'Personal Information',
        'Passport / Identification',
        'Emergency Contact',
        'Pilot Certificates & Ratings',
        'Medical Certificate',
        'Flight Experience',
        'Previous Flight Training',
        'Required Documents',
        'Additional Information & Confirmation'

    ];

    let currentStep = 0;

    totalStepsElement.textContent = steps.length;


    function updateStep() {

        steps.forEach((step, index) => {

            step.classList.toggle(
                'active',
                index === currentStep
            );

        });


        currentStepElement.textContent =
            currentStep + 1;


        stepTitle.textContent =
            stepTitles[currentStep];


        const percentage =
            ((currentStep + 1) / steps.length) * 100;


        progress.style.width =
            percentage + '%';


        previousButton.style.visibility =
            currentStep === 0
                ? 'hidden'
                : 'visible';


        if (currentStep === steps.length - 1) {

            nextButton.classList.add('d-none');

            submitButton.classList.remove('d-none');

        } else {

            nextButton.classList.remove('d-none');

            submitButton.classList.add('d-none');

        }


        window.scrollTo({
            top: document.querySelector(
                '.onboarding-progress'
            ).offsetTop - 100,

            behavior: 'smooth'
        });

    }


    function validateCurrentStep() {

        const current =
            steps[currentStep];

        const fields =
            current.querySelectorAll(
                'input, select, textarea'
            );


        for (const field of fields) {

            if (!field.checkValidity()) {

                field.reportValidity();

                return false;

            }

        }

        return true;

    }


    nextButton.addEventListener('click', function () {

        if (!validateCurrentStep()) {
            return;
        }

        if (currentStep < steps.length - 1) {

            currentStep++;

            updateStep();

        }

    });


    previousButton.addEventListener('click', function () {

        if (currentStep > 0) {

            currentStep--;

            updateStep();

        }

    });


    form.addEventListener('submit', function (event) {

        if (!validateCurrentStep()) {

            event.preventDefault();

            return;

        }

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Submitting...
        `;

    });


    updateStep();

});

</script>

@endsection