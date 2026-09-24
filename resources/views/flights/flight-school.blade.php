@extends('layout')

@section('content')

    <style>
        body {
            background-color: #f8fbff;
            color: #1a1a1a;
        }

        .hero {
            background: linear-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)),
                url('{{ asset("assets/images/aircraft/hero-aircraft.jpg") }}') center/cover no-repeat;
            height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .gold-text {
            color: #62aaf7;
        }

        .gold-btn {
            background-color: #62aaf7;
            color: #fff;
            font-weight: bold;
            border: none;
        }

        .gold-btn:hover {
            background-color: #62aaf7;
        }

        .section-padding {
            padding: 40px 0;
        }

        .card-light {
            background-color: #ffffff;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #fff;
            border-radius: 50px;
            text-align: center;
            font-size: 28px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero {
            position: relative;
            height: 85vh;
            background: url('{{ asset("assets/images/aircraft/hero-aircraft.jpeg") }}') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #ffffff;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            /* dark overlay for readability */
        }

        .hero div {
            position: relative;
            z-index: 2;
        }

        .highlight-hours {
            background-color: #62aaf7;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
        }

        /* =========================================================
            PROGRAM MODALS
            ========================================================= */

        .modal {
            z-index: 2000 !important;
        }

        .modal-backdrop {
            z-index: 1990 !important;
        }


        /* Keep modal away from the fixed navbar */
        .modal-dialog {
            margin-top: 90px;
            margin-bottom: 30px;
        }


        /* Scroll long program details */
        .modal-dialog-scrollable {
            max-height: calc(100vh - 110px);
        }

        .modal-dialog-scrollable .modal-content {
            max-height: calc(100vh - 110px);
        }

        .pdf-logo-container {
            text-align: center;
            padding: 15px 10px 5px;
            background: #ffffff;
        }

        .pdf-logo {
            width: 250px;
            height: auto;
            max-height: 100px;
            object-fit: contain;
        }

        /* =========================================================
       ABBYERO PROGRAM MODAL HEADER
       Shared by PPL / CPL / Instrument / Multi-Engine
    ========================================================= */

        .program-modal-header {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: nowrap;
        }


        /* =========================================================
       LOGO + TITLE
    ========================================================= */

        .program-modal-title {
            display: flex;
            align-items: center;
            gap: 12px;

            min-width: 0;
            flex: 1 1 auto;
        }

        .program-modal-logo {
            width: 70px;
            height: auto;
            max-height: 55px;

            object-fit: contain;
            flex-shrink: 0;
        }

        .program-modal-title-text {
            min-width: 0;
            flex: 1;
        }

        .program-modal-title-text .modal-title {
            line-height: 1.25;
            word-break: normal;
        }

        .program-modal-title-text small {
            display: block;
            line-height: 1.3;
        }


        /* =========================================================
       ACTIONS
    ========================================================= */

        .program-modal-actions {
            display: flex;
            align-items: center;
            gap: 8px;

            flex-shrink: 0;
        }

        .program-modal-actions .btn {
            white-space: nowrap;
        }

        .program-modal-actions .btn-outline-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }


        /* =========================================================
       TABLET
    ========================================================= */

        @media (max-width: 991.98px) {

            .program-modal-header {
                gap: 10px;
            }

            .program-modal-logo {
                width: 60px;
                max-height: 50px;
            }

            .program-modal-title-text .modal-title {
                font-size: 1.05rem;
            }

        }


        /* =========================================================
       MOBILE
    ========================================================= */

        @media (max-width: 767.98px) {

            .program-modal-header {
                align-items: flex-start;
                flex-wrap: wrap;

                padding: 12px 15px;
                gap: 10px;
            }

            /*
         * Logo + title takes the full first row
         */
            .program-modal-title {
                width: 100%;
                flex: 1 1 100%;

                align-items: center;
            }

            .program-modal-logo {
                width: 55px;
                max-height: 45px;
            }

            .program-modal-title-text {
                min-width: 0;
            }

            .program-modal-title-text .modal-title {
                font-size: 1rem;
                line-height: 1.3;
            }

            .program-modal-title-text small {
                font-size: 0.75rem;
            }


            /*
         * Buttons move to second row
         */
            .program-modal-actions {
                width: 100%;

                justify-content: flex-end;
                align-items: center;

                margin-top: 2px;
            }

            .program-modal-actions .btn {
                min-height: 38px;
            }

            .program-modal-actions .btn-outline-danger {
                padding: 6px 10px;
                font-size: 0.85rem;
            }

        }


        /* =========================================================
       SMALL PHONES
    ========================================================= */

        @media (max-width: 400px) {

            .program-modal-header {
                padding: 10px 12px;
            }

            .program-modal-title {
                gap: 8px;
            }

            .program-modal-logo {
                width: 48px;
                max-height: 40px;
            }

            .program-modal-title-text .modal-title {
                font-size: 1.25rem;
            }

            .program-modal-title-text small {
                font-size: 0.7rem;
            }

            .program-modal-actions {
                justify-content: space-between;
            }

            .program-modal-actions .btn-outline-danger {
                flex: 1;
            }

        }
    </style>
    <!-- HERO SECTION -->
    <section class="hero">
        <div>
            <h1 class="display-4 fw-bold gold-text">
                🌟 Fly High with Abbyero Aviation Flight School! 🌟
            </h1>
            <p class="lead">Earn Your Private Pilot License in 30 Days</p>
            <a href="#apply" class="btn gold-btn btn-lg mt-3">Apply Now</a>
        </div>
    </section>

    <!-- PROGRAM SECTION -->
    <section class="section-padding container">

        <h2 class="gold-text mb-4 text-center">
            Training Program Packages Highlight
        </h2>

        <div class="row g-4 align-items-stretch">

            {{-- =========================================================
            PROGRAM 1: 30-DAY ACCELERATED PRIVATE PILOT
            ========================================================== --}}
            <div class="col-md-6 d-flex">

                <div class="card card-light p-4 h-100 w-100 d-flex flex-column">

                    {{-- CARD HEADER --}}
                    <div>

                        <h3 class="gold-text mb-2">
                            30-Day Accelerated
                        </h3>

                        <h4 class="mb-3">
                            Private Pilot (PPL) Certification
                        </h4>

                        <p class="text-muted mb-3">
                            <strong>Abbyero Aviation LLC</strong>
                        </p>

                    </div>


                    {{-- CARD CONTENT --}}
                    <div class="text-start flex-grow-1">

                        {{-- PROGRAM SUMMARY --}}
                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>
                                    PA-28-140 & PA-28R-200
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Program Duration
                                </small>
                                <strong>
                                    30 Days
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Total Flight Time
                                </small>
                                <strong>
                                    60 Hours
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    PA-28-140
                                </small>
                                <strong>
                                    50 Hours
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    PA-28R-200
                                </small>
                                <strong>
                                    10 Hours Dual
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Housing
                                </small>
                                <strong>
                                    30 Days Included
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Transportation
                                </small>
                                <strong>
                                    Included
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Program Type
                                </small>
                                <strong>
                                    Accelerated PPL
                                </strong>
                            </div>

                        </div>


                        <hr>


                        {{-- COST --}}
                        <h5 class="gold-text">
                            Complete Program Package
                        </h5>

                        <p class="display-6 gold-text mb-3">
                            $14,950
                        </p>


                        {{-- DESCRIPTION --}}
                        <p>
                            The <strong>Abbyero Aviation LLC 30-Day Accelerated
                                Private Pilot Program</strong> is designed to take
                            students through intensive Private Pilot training while
                            introducing them to advanced complex-aircraft operations.
                        </p>

                        <p>
                            With <strong>60 total flight hours</strong>, the program
                            provides substantially more scheduled flight experience
                            than the FAA's 40-hour minimum for Private Pilot
                            Airplane Single-Engine Land certification under
                            <strong>14 CFR §61.109(a)</strong>.
                        </p>


                        {{-- FLIGHT TRAINING --}}
                        <h5 class="gold-text mt-3">
                            Flight Training
                        </h5>

                        <ul class="mb-3">

                            <li>
                                <strong>50 Hours — Piper PA-28-140</strong>
                                — Primary PPL training aircraft
                            </li>

                            <li>
                                <strong>10 Hours — Piper PA-28R-200</strong>
                                — Dual complex-aircraft training
                            </li>

                            <li>
                                <strong>41 Hours Dual / 19 Hours Solo</strong>
                                — Planned total flight experience
                            </li>

                            <li>
                                Night, cross-country and basic instrument training
                            </li>

                            <li>
                                FAA Private Pilot ACS and checkride preparation
                            </li>

                        </ul>


                        {{-- COMPLEX TRAINING --}}
                        <h5 class="gold-text mt-3">
                            Enhanced Complex Training
                        </h5>

                        <p>
                            The <strong>10-hour PA-28R-200 Arrow II phase</strong>
                            introduces students to advanced aircraft operations,
                            including retractable landing gear, constant-speed
                            propeller operations, power management, complex
                            checklists, emergency gear procedures and aircraft
                            performance management.
                        </p>

                        <div class="alert alert-info small mb-3">

                            <strong>Important:</strong>

                            The PA-28R-200 training is an Abbyero Aviation
                            enhanced-training component and is
                            <strong>not an FAA requirement</strong> for obtaining
                            a Private Pilot certificate.

                        </div>


                        {{-- PACKAGE INCLUDES --}}
                        <h5 class="gold-text mt-3">
                            Package Includes
                        </h5>

                        <ul class="mb-3">

                            <li>
                                60 total flight hours
                            </li>

                            <li>
                                30-day accelerated training program
                            </li>

                            <li>
                                30 days housing
                            </li>

                            <li>
                                Transportation
                            </li>

                            <li>
                                Ground and flight briefings
                            </li>

                            <li>
                                Practical-test preparation
                            </li>

                        </ul>

                        <p class="small text-muted mb-0">
                            Completion within 30 days depends on student proficiency,
                            weather, aircraft availability, maintenance and successful
                            completion of required FAA milestones.
                        </p>

                    </div>


                    {{-- CARD ACTIONS --}}
                    <div class="mt-auto pt-4">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#flightTraining60Modal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            PROGRAM 2: 15-DAY ACCELERATED MULTI-ENGINE
            ========================================================== --}}
            <div class="col-md-6 d-flex">

                <div class="card card-light p-4 h-100 w-100 d-flex flex-column">

                    {{-- CARD HEADER --}}
                    <div>

                        <h3 class="gold-text mb-2">
                            15-Day Accelerated
                        </h3>

                        <h4 class="mb-3">
                            Multi-Engine Training
                        </h4>

                        <p class="text-muted mb-3">
                            <strong>Abbyero Aviation LLC</strong>
                        </p>

                    </div>


                    {{-- CARD CONTENT --}}
                    <div class="text-start flex-grow-1">

                        {{-- PROGRAM SUMMARY --}}
                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>
                                    Cessna 310J
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>
                                    15 Days
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Flight Training
                                </small>
                                <strong>
                                    10–15 Hours
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Training Rate
                                </small>
                                <strong>
                                    $410/hour
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Accommodation
                                </small>
                                <strong>
                                    $70/day
                                </strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Program Type
                                </small>
                                <strong>
                                    AMEL Rating
                                </strong>
                            </div>

                        </div>


                        <hr>


                        {{-- COST --}}
                        <h5 class="gold-text">
                            Estimated Program Cost
                        </h5>

                        <p class="display-6 gold-text mb-3">
                            $5,150–$7,200
                        </p>


                        {{-- DESCRIPTION --}}
                        <p>
                            The <strong>Abbyero Aviation LLC 15-Day Accelerated
                                Multi-Engine Training Program</strong> is designed to
                            prepare qualified pilots for an
                            <strong>Airplane Multiengine Land (AMEL)</strong> rating
                            while developing safe, proficient and confident
                            multiengine operating skills in the
                            <strong>Cessna 310J</strong>.
                        </p>

                        <p>
                            Training is <strong>proficiency-based</strong>, allowing
                            the instructor to adjust the flight syllabus according
                            to the student's experience, proficiency and practical-test
                            readiness.
                        </p>


                        {{-- TRAINING OBJECTIVES --}}
                        <h5 class="gold-text mt-3">
                            Training Objectives
                        </h5>

                        <div class="row">

                            <div class="col-6">

                                <ul class="mb-3">

                                    <li>
                                        Cessna 310J aircraft familiarization
                                    </li>

                                    <li>
                                        Multiengine aerodynamics
                                    </li>

                                    <li>
                                        Aircraft systems and limitations
                                    </li>

                                    <li>
                                        Normal and crosswind operations
                                    </li>

                                    <li>
                                        Performance and weight & balance
                                    </li>

                                </ul>

                            </div>

                            <div class="col-6">

                                <ul class="mb-3">

                                    <li>
                                        Critical-engine concepts
                                    </li>

                                    <li>
                                        VMC factors and awareness
                                    </li>

                                    <li>
                                        Engine-failure recognition
                                    </li>

                                    <li>
                                        Single-engine aircraft control
                                    </li>

                                    <li>
                                        Emergency procedures
                                    </li>

                                </ul>

                            </div>

                        </div>


                        {{-- ADVANCED MULTI-ENGINE OPERATIONS --}}
                        <h5 class="gold-text mt-2">
                            Advanced Multi-Engine Operations
                        </h5>

                        <p>
                            Flight training develops practical proficiency in
                            multiengine aircraft handling and emergency procedures,
                            including:
                        </p>

                        <ul class="mb-3">

                            <li>
                                Engine failure during takeoff and after liftoff
                            </li>

                            <li>
                                Single-engine maneuvering and performance
                            </li>

                            <li>
                                Propeller feathering and engine-out procedures
                            </li>

                            <li>
                                Engine shutdown and restart procedures
                            </li>

                            <li>
                                Single-engine approaches and landings
                            </li>

                            <li>
                                Emergency checklist procedures
                            </li>

                            <li>
                                Practical-test and ACS preparation
                            </li>

                        </ul>

                        {{-- PACKAGE --}}
                        <h5 class="gold-text mt-3">
                            Package Includes
                        </h5>

                        <ul class="mb-3">

                            <li>
                                <strong>10–15 Hours</strong>
                                Dual Multi-Engine Flight Instruction
                            </li>

                            <li>
                                <strong>15-Day</strong>
                                Accelerated Training Program
                            </li>

                            <li>
                                <strong>Cessna 310J</strong>
                                Multi-Engine Aircraft
                            </li>

                            <li>
                                <strong>Accommodation</strong>
                                Included for 15 Days
                            </li>

                            <li>
                                Ground instruction and flight briefings
                            </li>

                            <li>
                                Practical-test / checkride preparation
                            </li>

                        </ul>


                        {{-- IMPORTANT NOTE --}}
                        <div class="alert alert-info small mb-3">

                            <strong>Important:</strong>

                            The program is proficiency-based. Actual training
                            hours may vary depending on student experience,
                            proficiency, weather, aircraft availability,
                            maintenance and instructor evaluation.

                        </div>


                        <p class="small text-muted mb-0">

                            FAA/DPE practical-test fees, checkride aircraft time,
                            transportation, meals and flight training beyond the
                            selected training package are not included unless
                            specifically stated in the enrollment agreement.

                        </p>

                    </div>


                    {{-- CARD ACTIONS --}}
                    <div class="mt-auto pt-4">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#multiEngineProgramModal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>


        </div>

    </section>


    {{-- =========================================================
    30-DAY ACCELERATED PRIVATE PILOT PROGRAM MODAL
    ========================================================== --}}
    <div class="modal fade" id="flightTraining60Modal" tabindex="-1" aria-labelledby="flightTraining60ModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="pplProgramPdfContent">


                {{-- =================================================
                MODAL HEADER
                ================================================== --}}

                <div class="modal-header program-modal-header">

                    {{-- =================================================
                    LOGO + PROGRAM TITLE
                    ================================================== --}}
                    <div class="program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                            alt="Abbyero Aviation"
                            class="program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1"
                                id="flightTraining60ModalLabel">

                                30-Day Accelerated Private Pilot
                                Certification Program

                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC •
                                PA-28-140 &amp; PA-28R-200
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                    HEADER ACTIONS
                    ================================================== --}}
                    <div class="program-modal-actions"
                        data-html2canvas-ignore="true">

                        <button type="button"
                            class="btn btn-outline-danger"
                            id="downloadPPLProgramPdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>


                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>

                {{-- =================================================
                MODAL BODY
                ================================================== --}}

                <div class="modal-body">


                    {{-- =================================================
                    PROGRAM SUMMARY
                    ================================================== --}}

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Aircraft
                                </small>

                                <strong>
                                    PA-28-140 /
                                    PA-28R-200
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Duration
                                </small>

                                <strong>
                                    30 Days
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Flight Training
                                </small>

                                <strong>
                                    60 Hours
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Total Cost
                                </small>

                                <strong class="gold-text">
                                    $14,950
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    PA-28-140
                                </small>

                                <strong>
                                    50 Hours
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    PA-28R-200
                                </small>

                                <strong>
                                    10 Hours Dual
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Housing
                                </small>

                                <strong>
                                    30 Days Included
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Transportation
                                </small>

                                <strong>
                                    Included
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    PROGRAM OVERVIEW
                    ================================================== --}}

                    <h5 class="gold-text">
                        Program Overview
                    </h5>

                    <p>

                        The <strong>Abbyero Aviation LLC Accelerated
                            Private Pilot Program</strong> is designed to take
                        a student through Private Pilot training in an
                        intensive 30-day format while also introducing
                        the student to advanced complex-aircraft operations.

                    </p>

                    <p>

                        The program provides <strong>60 total flight
                            hours</strong>, exceeding the FAA's 40-hour minimum
                        for Private Pilot Airplane Single-Engine Land
                        certification under Part 61,
                        <strong>14 CFR §61.109</strong>.

                    </p>


                    {{-- =================================================
                    FLIGHT TRAINING STRUCTURE
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        Flight Training Structure
                    </h5>


                    {{-- PA-28-140 --}}

                    <h6 class="mt-3">
                        Piper PA-28-140 — 50 Hours
                    </h6>

                    <p>

                        The PA-28-140 serves as the primary training
                        aircraft.

                    </p>

                    <p>
                        The 50 hours consist of a combination of:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>Dual instruction</li>
                                <li>Supervised solo flying</li>
                                <li>Solo cross-country</li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>

                                <li>Night training</li>
                                <li>Basic instrument flying</li>
                                <li>Checkride preparation</li>

                            </ul>

                        </div>

                    </div>


                    {{-- PA-28 ALLOCATION --}}

                    <div class="table-responsive mt-3">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Training
                                    </th>

                                    <th>
                                        Hours
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>
                                        PA-28-140 Dual Instruction
                                    </td>

                                    <td>
                                        31 hr
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        PA-28-140 Solo
                                    </td>

                                    <td>
                                        19 hr
                                    </td>

                                </tr>

                                <tr class="fw-bold">

                                    <td>
                                        PA-28-140 Total
                                    </td>

                                    <td>
                                        50 hr
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <p class="small text-muted">

                        The exact dual/solo division may change according
                        to student proficiency while maintaining all
                        applicable FAA minimum requirements.

                    </p>


                    {{-- =================================================
                    PA-28R-200
                    ================================================== --}}

                    <h6 class="mt-4">
                        Piper PA-28R-200 — 10 Hours
                    </h6>

                    <p>

                        <strong>10 hours dual instruction</strong> in the
                        Piper Arrow II PA-28R-200.

                    </p>

                    <p>
                        Advanced training includes:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>Retractable landing gear</li>
                                <li>Constant-speed propeller</li>
                                <li>Propeller and power management</li>
                                <li>Gear-extension procedures</li>
                                <li>Complex aircraft checklists</li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>

                                <li>Emergency gear-extension procedures</li>
                                <li>Advanced takeoffs and landings</li>
                                <li>Aircraft performance management</li>
                                <li>Cross-country operations</li>
                                <li>Introduction to commercial-level aircraft management</li>

                            </ul>

                        </div>

                    </div>


                    <div class="alert alert-info">

                        <strong>Enhanced Training Component:</strong>

                        The 10-hour Arrow phase is an
                        <strong>Abbyero Aviation enhanced-training
                            component</strong>, not an FAA requirement for
                        obtaining a Private Pilot certificate.
                        It gives the new pilot valuable exposure to a
                        more advanced airplane early in their aviation
                        career.

                    </div>


                    {{-- =================================================
                    TOTAL FLIGHT EXPERIENCE
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        Total Flight Experience
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Aircraft
                                    </th>

                                    <th>
                                        Dual
                                    </th>

                                    <th>
                                        Solo
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>
                                        PA-28-140
                                    </td>

                                    <td>
                                        31 hr
                                    </td>

                                    <td>
                                        19 hr
                                    </td>

                                    <td>
                                        50 hr
                                    </td>

                                </tr>

                                <tr>

                                    <td>
                                        PA-28R-200
                                    </td>

                                    <td>
                                        10 hr
                                    </td>

                                    <td>
                                        —
                                    </td>

                                    <td>
                                        10 hr
                                    </td>

                                </tr>

                                <tr class="fw-bold">

                                    <td>
                                        Program Total
                                    </td>

                                    <td>
                                        41 hr
                                    </td>

                                    <td>
                                        19 hr
                                    </td>

                                    <td>
                                        60 hr
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                    30-DAY TRAINING SCHEDULE
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        30-Day Training Schedule
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Days
                                    </th>

                                    <th>
                                        Training Phase
                                    </th>

                                    <th>
                                        Flight Hours
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>1–5</td>

                                    <td>
                                        Fundamentals, aircraft control,
                                        takeoffs & landings
                                    </td>

                                    <td>
                                        10
                                    </td>

                                </tr>

                                <tr>

                                    <td>6–10</td>

                                    <td>
                                        Maneuvers, emergencies &
                                        initial solo
                                    </td>

                                    <td>
                                        10
                                    </td>

                                </tr>

                                <tr>

                                    <td>11–15</td>

                                    <td>
                                        Navigation, cross-country &
                                        solo development
                                    </td>

                                    <td>
                                        10
                                    </td>

                                </tr>

                                <tr>

                                    <td>16–20</td>

                                    <td>
                                        Night, instrument &
                                        cross-country training
                                    </td>

                                    <td>
                                        10
                                    </td>

                                </tr>

                                <tr>

                                    <td>21–24</td>

                                    <td>
                                        Solo cross-country &
                                        PPL ACS proficiency
                                    </td>

                                    <td>
                                        10
                                    </td>

                                </tr>

                                <tr>

                                    <td>25–27</td>

                                    <td>
                                        PA-28R-200 complex-aircraft
                                        training
                                    </td>

                                    <td>
                                        6
                                    </td>

                                </tr>

                                <tr>

                                    <td>28–29</td>

                                    <td>
                                        Advanced PA-28R-200 training
                                    </td>

                                    <td>
                                        4
                                    </td>

                                </tr>

                                <tr>

                                    <td>30</td>

                                    <td>
                                        Final review/checkride preparation/
                                        weather reserve
                                    </td>

                                    <td>
                                        —
                                    </td>

                                </tr>

                                <tr class="fw-bold">

                                    <td colspan="2">
                                        Total
                                    </td>

                                    <td>
                                        60 hr
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <p class="small text-muted">

                        Actual scheduling will be adjusted for weather,
                        maintenance, student progress and FAA solo
                        requirements.

                    </p>


                    {{-- =================================================
                    FAA REQUIREMENTS
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        FAA Private Pilot Requirements
                    </h5>

                    <p>

                        Under current <strong>14 CFR §61.109(a)</strong>,
                        a Private Pilot ASEL applicant needs at least:

                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>
                                    40 hours total flight time
                                </li>

                                <li>
                                    20 hours flight training from an
                                    authorized instructor
                                </li>

                                <li>
                                    10 hours solo
                                </li>

                                <li>
                                    3 hours cross-country flight training
                                </li>

                                <li>
                                    3 hours night flight training
                                </li>

                                <li>
                                    Night cross-country exceeding
                                    100 NM total distance
                                </li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>

                                <li>
                                    10 night takeoffs and 10 full-stop
                                    landings
                                </li>

                                <li>
                                    3 hours of flight solely by reference
                                    to instruments
                                </li>

                                <li>
                                    3 hours of practical-test preparation
                                    within the prescribed recent-training
                                    period
                                </li>

                            </ul>

                        </div>

                    </div>


                    <h6 class="mt-4">
                        Required Solo Experience
                    </h6>

                    <ul>

                        <li>
                            At least 5 hours solo cross-country
                        </li>

                        <li>
                            One 150-NM solo cross-country
                        </li>

                        <li>
                            Full-stop landings at three points
                        </li>

                        <li>
                            At least one segment exceeding
                            50 NM straight-line distance
                        </li>

                        <li>
                            Three takeoffs and three full-stop landings
                            at an airport with an operating control tower
                        </li>

                    </ul>


                    <div class="alert alert-warning">

                        <strong>Important:</strong>

                        Abbyero's 60-hour program provides considerably
                        more scheduled flight experience than the FAA
                        40-hour minimum. Students must still satisfy
                        each individual FAA requirement applicable to
                        their certificate.

                    </div>


                    {{-- =================================================
                    TRAINING AREAS
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        Training Areas
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>Preflight planning and inspections</li>
                                <li>Aircraft systems</li>
                                <li>Normal and crosswind takeoffs and landings</li>
                                <li>Traffic-pattern operations</li>
                                <li>Slow flight</li>
                                <li>Power-on and power-off stalls</li>
                                <li>Steep turns</li>
                                <li>Ground-reference maneuvers</li>
                                <li>Emergency procedures</li>
                                <li>Navigation</li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>

                                <li>Radio communications</li>
                                <li>Cross-country flight planning</li>
                                <li>Aviation weather</li>
                                <li>Night operations</li>
                                <li>Basic instrument flying</li>
                                <li>Lost procedures</li>
                                <li>Diversions</li>
                                <li>Aeronautical decision-making</li>
                                <li>Risk management</li>
                                <li>FAA Private Pilot ACS preparation</li>

                            </ul>

                        </div>

                    </div>


                    {{-- =================================================
                    GROUND TRAINING
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        Ground Training
                    </h5>

                    <p>
                        Ground instruction and briefings support the
                        flight syllabus with:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>Federal Aviation Regulations</li>
                                <li>Airspace</li>
                                <li>Aerodynamics</li>
                                <li>Aircraft systems</li>
                                <li>Weather</li>
                                <li>Weight and balance</li>
                                <li>Aircraft performance</li>
                                <li>Navigation</li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>

                                <li>Airport operations</li>
                                <li>Radio communications</li>
                                <li>Human factors</li>
                                <li>Aeromedical factors</li>
                                <li>Cross-country planning</li>
                                <li>Emergency procedures</li>
                                <li>Private Pilot knowledge-test preparation</li>
                                <li>Private Pilot ACS/checkride preparation</li>

                            </ul>

                        </div>

                    </div>


                    {{-- =================================================
                    COMPLETE PACKAGE
                    ================================================== --}}

                    <h5 class="gold-text mt-4">
                        Abbyero Aviation LLC Package
                    </h5>

                    <div class="alert alert-light border">

                        <h4 class="gold-text">
                            Complete Package — $14,950
                        </h4>

                        <ul class="mb-2">

                            <li>
                                60 total flight hours
                            </li>

                            <li>
                                50 hours in the Piper PA-28-140
                            </li>

                            <li>
                                10 hours dual in the Piper PA-28R-200
                            </li>

                            <li>
                                Dual and solo training as applicable
                            </li>

                            <li>
                                30-day accelerated training program
                            </li>

                            <li>
                                30 days housing
                            </li>

                            <li>
                                Transportation
                            </li>

                            <li>
                                Ground and flight briefings
                            </li>

                            <li>
                                Practical-test preparation
                            </li>

                        </ul>

                    </div>


                    {{-- =================================================
                    FEES / DISCLAIMER
                    ================================================== --}}

                    <p class="small text-muted">

                        FAA knowledge-test fees, aviation medical
                        examination, DPE/checkride fees and other items
                        not expressly included in Abbyero Aviation's
                        enrollment agreement should be identified
                        separately.

                    </p>

                    <p class="small text-muted">

                        Completion within 30 days is dependent on student
                        proficiency, weather, aircraft availability,
                        maintenance and successful completion of required
                        FAA milestones.

                    </p>


                </div>


                {{-- =================================================
                MODAL FOOTER
                ================================================== --}}

                <div class="modal-footer" data-html2canvas-ignore="false">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-html2canvas-ignore="true">

                        Close

                    </button>


                    <a href="#apply" class="btn btn-primary" data-html2canvas-ignore="true">

                        <i class="fas fa-paper-plane me-2"></i>

                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>
    {{-- ================================================================
    MULTI-ENGINE PROGRAM DETAILS MODAL
    ================================================================ --}}

    <div class="modal fade" id="multiEngineProgramModal" tabindex="-1" aria-labelledby="multiEngineProgramModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="multiEnginePdfContent">


                {{-- MODAL HEADER --}}
                <div class="modal-header program-modal-header">

                    {{-- LEFT: LOGO + TITLE --}}
                    <div class="d-flex align-items-center program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}" alt="Abbyero Aviation"
                            class="pdf-logo program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1" id="multiEngineProgramModalLabel">
                                15-Day Accelerated Multi-Engine Training
                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC • Cessna 310J
                            </small>

                        </div>

                    </div>

                    {{-- RIGHT: ACTIONS --}}
                    <div class="program-modal-actions" data-html2canvas-ignore="true">

                        <button type="button" class="btn btn-outline-danger" id="downloadMultiEnginePdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>



                {{-- PDF CONTENT --}}
                <div class="modal-body">

                    {{-- PROGRAM SUMMARY --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-3">

                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Aircraft
                                </small>

                                <strong>Cessna 310J</strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Duration
                                </small>

                                <strong>15 Days</strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Dual Training
                                </small>

                                <strong>10–15 Hours</strong>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Total Cost
                                </small>

                                <strong class="gold-text">
                                    $5,150–$7,200
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- PROGRAM DESCRIPTION --}}
                    <h5 class="gold-text">
                        Program Overview
                    </h5>

                    <p>
                        The Abbyero Aviation LLC Accelerated Multi-Engine Training
                        Program is designed to prepare pilots for the addition of
                        an <strong>Airplane Multiengine Land (AMEL)</strong> rating
                        while developing safe and proficient multiengine
                        operating skills in the Cessna 310J.
                    </p>

                    <p>
                        The program is proficiency-based. Under
                        <strong>14 CFR §61.63(c)</strong>, the FAA does not specify
                        a minimum flight-training-hour requirement for a pilot
                        adding an aircraft class rating to an existing certificate.
                        The student must receive the appropriate instructor
                        endorsement and pass the practical test.
                    </p>


                    {{-- TRAINING OBJECTIVES --}}
                    <h5 class="gold-text mt-4">
                        Training Objectives
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>

                                <li>Cessna 310J aircraft familiarization</li>
                                <li>Multiengine aerodynamics</li>
                                <li>Normal and crosswind takeoffs and landings</li>
                                <li>Aircraft systems and limitations</li>
                                <li>Performance and weight-and-balance calculations</li>
                                <li>Critical-engine concepts</li>
                                <li>VMC factors and demonstration</li>
                                <li>Engine-failure recognition</li>

                            </ul>

                        </div>


                        <div class="col-md-6">

                            <ul>

                                <li>Engine failure during takeoff</li>
                                <li>Engine failure after liftoff</li>
                                <li>Propeller feathering</li>
                                <li>Engine shutdown and restart</li>
                                <li>Single-engine aircraft control</li>
                                <li>Single-engine maneuvering and performance</li>
                                <li>Single-engine approaches and landings</li>
                                <li>Emergency operations</li>
                                <li>Practical-test preparation</li>

                            </ul>

                        </div>

                    </div>


                    {{-- 15 DAY SCHEDULE --}}
                    <h5 class="gold-text mt-4">
                        15-Day Training Schedule
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>

                                    <th>Days</th>
                                    <th>Training Phase</th>
                                    <th>Approx. Flight Time</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>1–2</td>
                                    <td>
                                        Cessna 310J systems, familiarization
                                        & normal operations
                                    </td>
                                    <td>1.0–1.5 hr</td>
                                </tr>

                                <tr>
                                    <td>3–4</td>
                                    <td>
                                        Takeoffs, landings & multiengine
                                        maneuvering
                                    </td>
                                    <td>1.5–2.0 hr</td>
                                </tr>

                                <tr>
                                    <td>5–6</td>
                                    <td>
                                        Multiengine aerodynamics, VMC &
                                        critical engine
                                    </td>
                                    <td>1.5–2.0 hr</td>
                                </tr>

                                <tr>
                                    <td>7–8</td>
                                    <td>
                                        Engine failures, feathering &
                                        restart procedures
                                    </td>
                                    <td>1.5–2.0 hr</td>
                                </tr>

                                <tr>
                                    <td>9–10</td>
                                    <td>
                                        Single-engine maneuvering &
                                        performance
                                    </td>
                                    <td>1.0–1.5 hr</td>
                                </tr>

                                <tr>
                                    <td>11–12</td>
                                    <td>
                                        Single-engine approaches &
                                        emergency operations
                                    </td>
                                    <td>1.0–1.5 hr</td>
                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>
                                        Advanced emergency scenarios
                                    </td>
                                    <td>1.0–1.5 hr</td>
                                </tr>

                                <tr>
                                    <td>14</td>
                                    <td>
                                        ACS practical-test preparation
                                    </td>
                                    <td>0.5–1.5 hr</td>
                                </tr>

                                <tr>
                                    <td>15</td>
                                    <td>
                                        Mock checkride/final proficiency training
                                    </td>
                                    <td>1.0–1.5 hr</td>
                                </tr>

                                <tr class="fw-bold">

                                    <td colspan="2">
                                        Total
                                    </td>

                                    <td>
                                        10–15 hr
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <p class="small text-muted">

                        Actual hours within the range will depend on student
                        proficiency, weather, maintenance availability, and
                        instructor evaluation.

                    </p>


                    {{-- GROUND TRAINING --}}
                    <h5 class="gold-text mt-4">
                        Ground Training
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ol>

                                <li>Cessna 310J aircraft systems</li>
                                <li>Engines and propeller systems</li>
                                <li>Fuel, electrical and landing-gear systems</li>
                                <li>Multiengine aerodynamics</li>
                                <li>Critical-engine principles</li>
                                <li>VMC and factors affecting VMC</li>
                                <li>Single-engine performance</li>

                            </ol>

                        </div>


                        <div class="col-md-6">

                            <ol start="8">

                                <li>Weight and balance</li>
                                <li>Takeoff and landing performance</li>
                                <li>Engine-out procedures</li>
                                <li>Emergency checklists</li>
                                <li>Aeronautical decision-making</li>
                                <li>FAA ACS practical-test preparation</li>

                            </ol>

                        </div>

                    </div>


                    {{-- COST --}}
                    <h5 class="gold-text mt-4">
                        Training Cost
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>Package</th>
                                    <th>Flight Training</th>
                                    <th>Accommodation</th>
                                    <th>Total</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>10-Hour Program</td>
                                    <td>10 × $410 = $4,100</td>
                                    <td>$1,050</td>
                                    <td><strong>$5,150</strong></td>

                                </tr>

                                <tr>

                                    <td>12-Hour Program</td>
                                    <td>12 × $410 = $4,920</td>
                                    <td>$1,050</td>
                                    <td><strong>$5,970</strong></td>

                                </tr>

                                <tr>

                                    <td>13-Hour Program</td>
                                    <td>13 × $410 = $5,330</td>
                                    <td>$1,050</td>
                                    <td><strong>$6,380</strong></td>

                                </tr>

                                <tr>

                                    <td>14-Hour Program</td>
                                    <td>14 × $410 = $5,740</td>
                                    <td>$1,050</td>
                                    <td><strong>$6,790</strong></td>

                                </tr>

                                <tr>

                                    <td>15-Hour Program</td>
                                    <td>15 × $410 = $6,150</td>
                                    <td>$1,050</td>
                                    <td><strong>$7,200</strong></td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- PACKAGE --}}
                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            Abbyero Aviation LLC Package
                        </h5>

                        <ul class="mb-2">

                            <li>10–15 Hours Dual Instruction</li>
                            <li>15-Day Accelerated Program</li>
                            <li>Cessna 310J</li>
                            <li>Accommodation Included</li>

                        </ul>

                        <strong>

                            Total Estimated Cost:

                            <span class="gold-text">
                                $5,150–$7,200
                            </span>

                        </strong>

                    </div>


                    {{-- PDF-EXCLUDED DISCLAIMER --}}
                    <p class="small text-muted" data-html2canvas-ignore="true">

                        FAA/DPE practical-test fees, checkride aircraft time,
                        transportation, meals, and flight training beyond
                        15 hours are not included unless specifically incorporated
                        into the package.

                    </p>

                </div>


                {{-- MODAL FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-html2canvas-ignore="true">

                        Close

                    </button>


                    <a href="#apply" class="btn btn-primary" data-html2canvas-ignore="true">

                        <i class="fas fa-paper-plane me-2"></i>
                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- ================================================================
    ROW 2 — INSTRUMENT + COMMERCIAL PILOT PROGRAMS
    ================================================================ --}}

    <section class="section-padding container">

        <div class="row g-4">

            {{-- =========================================================
            PROGRAM 3: INSTRUMENT RATING
            ========================================================== --}}
            <div class="col-md-6">

                <div class="card card-light p-4 h-100">

                    <h3 class="gold-text mb-2">
                        30-Day Accelerated
                    </h3>

                    <h4 class="mb-2">
                        Instrument Rating Training
                    </h4>

                    <p class="text-muted mb-3">
                        <strong>Abbyero Aviation LLC</strong>
                    </p>

                    <div class="text-start">

                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>Piper PA-28-140</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>30 Days</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Flight Training
                                </small>
                                <strong>40 Hours Dual</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Training Rate
                                </small>
                                <strong>$197.475/hour</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Accommodation
                                </small>
                                <strong>$70/day</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Program Type
                                </small>
                                <strong>Instrument Rating</strong>
                            </div>

                        </div>

                        <hr>

                        <h5 class="gold-text">
                            Total Program Cost
                        </h5>

                        <p class="display-6 gold-text mb-3">
                            $9,999
                        </p>

                        <p>
                            An accelerated 30-day program designed for pilots
                            seeking concentrated instrument flight training.
                            All 40 flight hours are conducted as dual instruction
                            with an appropriately rated flight instructor.
                        </p>

                        <ul class="mb-3">
                            <li>40 Hours Dual Flight Instruction</li>
                            <li>Piper PA-28-140 Training Aircraft</li>
                            <li>30-Day Accelerated Program</li>
                            <li>30 Days Accommodation</li>
                            <li>FAA Instrument Rating ACS Preparation</li>
                        </ul>

                    </div>

                    <div class="mt-auto">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#instrumentRatingModal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            PROGRAM 4: COMMERCIAL PILOT CPL
            ========================================================== --}}
            <div class="col-md-6">

                <div class="card card-light p-4 h-100">

                    <h3 class="gold-text mb-2">
                        30-Day Accelerated
                    </h3>

                    <h4 class="mb-2">
                        Commercial Pilot (CPL)
                    </h4>

                    <p class="text-muted mb-3">
                        <strong>Abbyero Aviation LLC</strong>
                    </p>

                    <div class="text-start">

                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Location
                                </small>
                                <strong>El Paso, Texas</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>30 Days</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>PA-28-140 / PA-28R-200</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Flight Training
                                </small>
                                <strong>40–50 Hours</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Enrollment
                                </small>
                                <strong>200 Hours</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Program Type
                                </small>
                                <strong>Commercial ASEL</strong>
                            </div>

                        </div>

                        <hr>

                        <h5 class="gold-text">
                            Program Packages
                        </h5>

                        <p class="mb-1">
                            <strong>40-Hour CPL:</strong>
                            <span class="gold-text">$9,999</span>
                        </p>

                        <p>
                            <strong>50-Hour + Complex:</strong>
                            <span class="gold-text">$12,199</span>
                        </p>

                        <p>
                            Designed for experienced private pilots preparing
                            for the FAA Commercial Pilot – Airplane Single-Engine
                            Land (ASEL) certificate under Part 61.
                        </p>

                        <ul class="mb-3">
                            <li>Advanced Commercial Maneuvers</li>
                            <li>Cross-Country Operations</li>
                            <li>Night Operations</li>
                            <li>Instrument Proficiency</li>
                            <li>Complex Aircraft Training Available</li>
                            <li>FAA Commercial Pilot ACS Preparation</li>
                        </ul>

                    </div>

                    <div class="mt-auto">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#commercialPilotModal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ================================================================
    INSTRUMENT RATING MODAL
    ================================================================ --}}

    <div class="modal fade" id="instrumentRatingModal" tabindex="-1" aria-labelledby="instrumentRatingModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="instrumentRatingPdfContent">
                <!-- MODAL HEADER -->
                <div class="modal-header program-modal-header">

                    {{-- =================================================
                    LOGO + PROGRAM TITLE
                    ================================================== --}}
                    <div class="program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                            alt="Abbyero Aviation"
                            class="program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1"
                                id="instrumentRatingModalLabel">

                                30-Day Accelerated Instrument Rating (AIR)

                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC • Piper PA-28-140
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                    HEADER ACTIONS
                    ================================================== --}}
                    <div class="program-modal-actions"
                        data-html2canvas-ignore="true">

                        <button type="button"
                            class="btn btn-outline-danger"
                            id="downloadInstrumentPdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>


                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>

                <div class="modal-body">

                    {{-- SUMMARY --}}

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>Piper PA-28-140</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>30 Days</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">
                                    Flight Training
                                </small>
                                <strong>40 Hours Dual</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <small class="text-muted d-block">
                                    Total Cost
                                </small>
                                <strong class="gold-text">
                                    $9,999
                                </strong>
                            </div>
                        </div>

                    </div>


                    {{-- OVERVIEW --}}

                    <h5 class="gold-text">
                        Program Overview
                    </h5>

                    <p>
                        This accelerated program is designed for pilots seeking
                        concentrated instrument flight training over a 30-day
                        period. All 40 flight hours are conducted as dual
                        instruction with an appropriately rated flight instructor.
                    </p>

                    <p>
                        The curriculum is structured around the FAA Instrument
                        Rating–Airplane standards and the aeronautical experience
                        requirements of <strong>14 CFR §61.65</strong>.
                    </p>


                    {{-- OBJECTIVES --}}

                    <h5 class="gold-text mt-4">
                        Program Objectives
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>Aircraft control solely by reference to instruments</li>
                                <li>Instrument scan and basic attitude instrument flying</li>
                                <li>IFR flight planning</li>
                                <li>IFR regulations and procedures</li>
                                <li>ATC clearances and communications</li>
                                <li>Navigation systems</li>
                                <li>Course interception and tracking</li>
                                <li>Holding procedures</li>
                                <li>Departure and arrival procedures</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Instrument approaches</li>
                                <li>Missed approaches</li>
                                <li>Circling approaches</li>
                                <li>Partial-panel operations</li>
                                <li>Abnormal and emergency procedures</li>
                                <li>IFR cross-country operations</li>
                                <li>Weather interpretation and decision-making</li>
                                <li>Practical-test preparation</li>
                            </ul>

                        </div>

                    </div>


                    {{-- SCHEDULE --}}

                    <h5 class="gold-text mt-4">
                        30-Day Flight Training Schedule
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Training Focus</th>
                                    <th>Dual Hours</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>1–3</td>
                                    <td>PA-28 familiarization & basic instrument flying</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>4–6</td>
                                    <td>Instrument scan, climbs, descents & turns</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>7–9</td>
                                    <td>VOR/GPS navigation, interception & tracking</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>10–12</td>
                                    <td>Holding procedures & ATC clearances</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>13–16</td>
                                    <td>Instrument approaches</td>
                                    <td>6.0</td>
                                </tr>

                                <tr>
                                    <td>17–19</td>
                                    <td>Approaches, missed approaches & circling</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>20–22</td>
                                    <td>Partial panel, failures & emergencies</td>
                                    <td>3.0</td>
                                </tr>

                                <tr>
                                    <td>23–25</td>
                                    <td>IFR cross-country procedures</td>
                                    <td>4.0</td>
                                </tr>

                                <tr>
                                    <td>26–27</td>
                                    <td>Required long IFR cross-country</td>
                                    <td>3.0</td>
                                </tr>

                                <tr>
                                    <td>28</td>
                                    <td>Advanced instrument proficiency</td>
                                    <td>1.5</td>
                                </tr>

                                <tr>
                                    <td>29</td>
                                    <td>ACS/checkride preparation</td>
                                    <td>1.5</td>
                                </tr>

                                <tr>
                                    <td>30</td>
                                    <td>Mock checkride & final proficiency flight</td>
                                    <td>1.0</td>
                                </tr>

                                <tr class="fw-bold">
                                    <td colspan="2">Total</td>
                                    <td>40.0 Hours</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- IFR CROSS COUNTRY --}}

                    <h5 class="gold-text mt-4">
                        Required IFR Cross-Country
                    </h5>

                    <p>
                        The course will incorporate the applicable §61.65
                        instrument cross-country requirement, including:
                    </p>

                    <ul>
                        <li>250 NM along airways or ATC-directed routing</li>
                        <li>IFR flight plan</li>
                        <li>Instrument approach at each airport</li>
                        <li>Three different kinds of approaches using navigation systems</li>
                    </ul>

                    <p>
                        The program also schedules practical-test preparation
                        near the end so the required recent instrument training
                        can be satisfied when the checkride is appropriately
                        scheduled.
                    </p>


                    {{-- GROUND TRAINING --}}

                    <h5 class="gold-text mt-4">
                        Ground Training and Briefings
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>Federal Aviation Regulations</li>
                                <li>IFR charts and publications</li>
                                <li>Weather reports and forecasts</li>
                                <li>Aircraft instruments and systems</li>
                                <li>IFR flight planning</li>
                                <li>ATC system and procedures</li>
                                <li>IFR clearances</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Departure and arrival procedures</li>
                                <li>Holding procedures</li>
                                <li>Instrument approach procedures</li>
                                <li>Alternate requirements</li>
                                <li>Lost-communications procedures</li>
                                <li>Aeronautical decision-making</li>
                                <li>Risk management</li>
                                <li>Instrument Rating ACS review</li>
                            </ul>

                        </div>

                    </div>


                    {{-- COST --}}

                    <h5 class="gold-text mt-4">
                        Program Cost
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Program Component</th>
                                    <th>Calculation</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>40 hours PA-28-140 dual training</td>
                                    <td>40 × $197.475</td>
                                    <td>$7,899</td>
                                </tr>

                                <tr>
                                    <td>Accommodation for 30 days</td>
                                    <td>30 × $70</td>
                                    <td>$2,100</td>
                                </tr>

                                <tr class="fw-bold">
                                    <td colspan="2">TOTAL PROGRAM COST</td>
                                    <td>$9,999</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- PACKAGE --}}

                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            Complete 30-Day Package: $9,999
                        </h5>

                        <ul class="mb-2">
                            <li>40 hours of dual flight instruction</li>
                            <li>Piper PA-28-140 training aircraft</li>
                            <li>30-day accelerated training program</li>
                            <li>30 days of accommodation</li>
                        </ul>

                        <strong>
                            Total:
                            <span class="gold-text">$9,999</span>
                        </strong>

                    </div>


                    {{-- ELIGIBILITY --}}

                    <h5 class="gold-text mt-4">
                        Student Eligibility
                    </h5>

                    <p>
                        For an instrument-airplane rating under Part 61, the
                        applicant must meet the FAA eligibility and
                        aeronautical-experience requirements, including the
                        applicable 50 hours of cross-country PIC time and
                        40 hours of actual or simulated instrument time.
                    </p>

                    <p>
                        The 40-hour Abbyero program provides all 40 planned
                        instrument-training hours as dual instruction.
                    </p>


                    <p class="small text-muted mt-4">
                        FAA written-test fees, examiner/DPE fees, checkride
                        aircraft expenses, meals, transportation, and any
                        additional flight hours needed to reach proficiency
                        are not included unless specifically added to the
                        package.
                    </p>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-html2canvas-ignore="true">

                        Close

                    </button>

                    <a href="#apply" class="btn btn-primary" data-html2canvas-ignore="true">

                        <i class="fas fa-paper-plane me-2"></i>
                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- ================================================================
    COMMERCIAL PILOT MODAL
    ================================================================ --}}

    <div class="modal fade" id="commercialPilotModal" tabindex="-1" aria-labelledby="commercialPilotModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="commercialPilotPdfContent">


                <div class="modal-header program-modal-header">

                    {{-- =================================================
                    LOGO + PROGRAM TITLE
                    ================================================== --}}
                    <div class="program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                            alt="Abbyero Aviation"
                            class="program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1"
                                id="commercialPilotModalLabel">

                                30-Day Accelerated Commercial Pilot (CPL)

                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC •
                                PA-28-140 / PA-28R-200
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                    HEADER ACTIONS
                    ================================================== --}}
                    <div class="program-modal-actions"
                        data-html2canvas-ignore="true">

                        <button type="button"
                            class="btn btn-outline-danger"
                            id="downloadCplPdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>


                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>


                <div class="modal-body">

                    {{-- SUMMARY --}}

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Duration
                                </small>

                                <strong>30 Days</strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Aircraft
                                </small>

                                <strong>
                                    PA-28-140 / PA-28R-200
                                </strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Training
                                </small>

                                <strong>40–50 Hours</strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded">

                                <small class="text-muted d-block">
                                    Starting Price
                                </small>

                                <strong class="gold-text">
                                    $9,999
                                </strong>

                            </div>
                        </div>

                    </div>


                    {{-- OVERVIEW --}}

                    <h5 class="gold-text">
                        Program Overview
                    </h5>

                    <p>
                        This accelerated program is designed for experienced
                        private pilots preparing for the FAA Commercial Pilot –
                        Airplane Single-Engine Land (ASEL) certificate under
                        Part 61.
                    </p>

                    <p>
                        The program combines advanced commercial maneuvers,
                        cross-country operations, night operations,
                        complex-aircraft training when required, and intensive
                        FAA practical-test preparation.
                    </p>


                    {{-- TWO OPTIONS --}}

                    <h5 class="gold-text mt-4">
                        Training Options
                    </h5>

                    <div class="row g-4">

                        {{-- TRACK 1 --}}

                        <div class="col-md-6">

                            <div class="border rounded p-4 h-100">

                                <h5>
                                    Track 1 — 40-Hour CPL Program
                                </h5>

                                <p>
                                    For students whose previous experience/logbook
                                    already satisfies the applicable complex,
                                    turbine, or TAA training requirement.
                                </p>

                                <ul>
                                    <li>Aircraft: PA-28-140</li>
                                    <li>Training: 40 Hours</li>
                                    <li>Rate: $197.475/hour</li>
                                </ul>

                                <table class="table table-sm table-bordered">

                                    <tbody>

                                        <tr>
                                            <td>40 hr PA-28-140 training</td>
                                            <td>$7,899</td>
                                        </tr>

                                        <tr>
                                            <td>30 days accommodation</td>
                                            <td>$2,100</td>
                                        </tr>

                                        <tr class="fw-bold">
                                            <td>Total Package</td>
                                            <td>$9,999</td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        {{-- TRACK 2 --}}

                        <div class="col-md-6">

                            <div class="border rounded p-4 h-100">

                                <h5>
                                    Track 2 — 50-Hour CPL + Complex
                                </h5>

                                <p>
                                    For students who still require the applicable
                                    commercial complex-aircraft training.
                                </p>

                                <ul>
                                    <li>PA-28-140 + PA-28R-200 Piper Arrow II</li>
                                    <li>40 hr PA-28-140 × $197.475</li>
                                    <li>10 hr PA-28R-200 × $220</li>
                                </ul>

                                <table class="table table-sm table-bordered">

                                    <tbody>

                                        <tr>
                                            <td>PA-28-140 training</td>
                                            <td>$7,899</td>
                                        </tr>

                                        <tr>
                                            <td>PA-28R-200 training</td>
                                            <td>$2,200</td>
                                        </tr>

                                        <tr>
                                            <td>30 days accommodation</td>
                                            <td>$2,100</td>
                                        </tr>

                                        <tr class="fw-bold">
                                            <td>Total Package</td>
                                            <td>$12,199</td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    <p class="mt-3">
                        The PA-28R-200 portion provides training in retractable
                        landing gear, constant-speed propeller operation, power
                        management, emergency procedures, and complex-aircraft
                        operations.
                    </p>


                    {{-- SCHEDULE --}}

                    <h5 class="gold-text mt-4">
                        30-Day Training Schedule
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>
                                    <th>Days</th>
                                    <th>Training Focus</th>
                                    <th>Approx. Hours</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>1–3</td>
                                    <td>Commercial standards, aircraft control & proficiency assessment</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>4–6</td>
                                    <td>Steep turns, chandelles & advanced maneuvering</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>7–9</td>
                                    <td>Lazy eights & commercial maneuver proficiency</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>10–12</td>
                                    <td>Power-off 180° accuracy approaches & landings</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>13–15</td>
                                    <td>Short/soft-field operations & emergency procedures</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>16–18</td>
                                    <td>Navigation & commercial cross-country operations</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>19–21</td>
                                    <td>Instrument proficiency & advanced aircraft control</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>22–23</td>
                                    <td>Night/cross-country requirements as applicable</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>24–26</td>
                                    <td>ACS maneuvers & scenario-based commercial operations</td>
                                    <td>4</td>
                                </tr>

                                <tr>
                                    <td>27–30</td>
                                    <td>Mock checkrides & practical-test preparation</td>
                                    <td>4</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- TRAINING AREAS --}}

                    <h5 class="gold-text mt-4">
                        Training Areas
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>Commercial-level aircraft control</li>
                                <li>Preflight planning and preparation</li>
                                <li>Performance and limitations</li>
                                <li>Weight and balance</li>
                                <li>Advanced takeoffs and landings</li>
                                <li>Steep turns</li>
                                <li>Chandelles</li>
                                <li>Lazy eights</li>
                                <li>Eights on pylons</li>
                                <li>Power-off 180° accuracy approaches</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Slow flight and stalls</li>
                                <li>Cross-country operations</li>
                                <li>Night operations</li>
                                <li>Instrument proficiency</li>
                                <li>Emergency procedures</li>
                                <li>Aeronautical decision-making</li>
                                <li>Risk management</li>
                                <li>Commercial privileges and limitations</li>
                                <li>FAA Commercial Pilot ACS preparation</li>
                                <li>Mock practical tests</li>
                            </ul>

                        </div>

                    </div>


                    {{-- FAA REQUIREMENTS --}}

                    <h5 class="gold-text mt-4">
                        Important FAA Requirements
                    </h5>

                    <p>
                        Under <strong>14 CFR §61.129(a)</strong>, a Commercial
                        ASEL applicant normally needs at least 250 total flight
                        hours, including specified PIC, cross-country, training,
                        instrument, advanced-airplane, night, and solo or
                        performing-duties-of-PIC experience.
                    </p>

                    <div class="alert alert-light border">

                        <strong>
                            200 hours is the minimum to ENTER the Abbyero
                            accelerated program — not the FAA minimum for taking
                            the CPL checkride.
                        </strong>

                    </div>

                    <p>
                        For example:
                    </p>

                    <ul>
                        <li>Student enters with 200 hours</li>
                        <li>Completes 40-hour program → approximately 240 hours</li>
                        <li>
                            Student still needs approximately 10 additional
                            qualifying hours before reaching 250
                        </li>
                        <li>
                            Student enters with 200 hours and completes the
                            entire 50-hour program → approximately 250 hours
                        </li>
                    </ul>

                    <p>
                        A student entering with 210+ qualifying hours could
                        potentially reach 250 hours through the 40-hour package.
                    </p>


                    {{-- COMPLEX TRAINING --}}

                    <h5 class="gold-text mt-4">
                        Complex Training Requirement
                    </h5>

                    <p>
                        For Commercial ASEL, §61.129(a) requires 10 hours of
                        training in a complex airplane, turbine-powered airplane,
                        technically advanced airplane (TAA), or a combination
                        thereof.
                    </p>

                    <p>
                        Therefore, the student’s logbook should be reviewed before
                        deciding that the optional PA-28R-200 portion is
                        unnecessary. Simply having 10 hours in a complex airplane
                        does not automatically establish that all required
                        training was properly received and logged.
                    </p>


                    {{-- LOGBOOK AUDIT --}}

                    <h5 class="gold-text mt-1">
                        FAA Experience Verification
                    </h5>

                    <p>
                        Because the commercial certificate contains specific
                        experience requirements, Abbyero Aviation should perform
                        a pre-enrollment logbook audit for every accelerated CPL
                        applicant.
                    </p>

                    <p>Particular attention should be given to:</p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>250-hour total requirement</li>
                                <li>100 hours powered aircraft</li>
                                <li>100 hours PIC</li>
                                <li>50 hours PIC in airplanes</li>
                                <li>50 hours cross-country PIC</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Required instrument training</li>
                                <li>Complex/TAA/turbine training</li>
                                <li>Day and night commercial cross-countries</li>
                                <li>Required solo or performing-duties-of-PIC experience</li>
                                <li>Night takeoffs and landings</li>
                                <li>Recent checkride-preparation training</li>
                            </ul>

                        </div>

                    </div>

                    <p class="small text-muted">
                        Some §61.129 experience must be logged in a particular
                        capacity, such as solo or performing the duties of PIC.
                        Therefore, 40–50 hours with an instructor aboard should
                        not automatically be advertised as satisfying every
                        aeronautical-experience requirement.
                    </p>


                    {{-- PACKAGE SUMMARY --}}

                    <h5 class="gold-text mt-4">
                        Package Summary
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>
                                    <th>CPL Package</th>
                                    <th>Flight Hours</th>
                                    <th>Accommodation</th>
                                    <th>Total</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>CPL Core Program</td>
                                    <td>40 hr</td>
                                    <td>30 days</td>
                                    <td><strong>$9,999</strong></td>
                                </tr>

                                <tr>
                                    <td>CPL + Complex Program</td>
                                    <td>50 hr</td>
                                    <td>30 days</td>
                                    <td><strong>$12,199</strong></td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            ABBYERO AVIATION LLC
                        </h5>

                        <p class="mb-1">
                            30-Day Accelerated Commercial Pilot Training
                        </p>

                        <strong>
                            PA-28-140 • PA-28R-200 • 40–50 Hours
                        </strong>

                        <br>

                        <strong>
                            Programs starting at
                            <span class="gold-text">$9,999</span>
                        </strong>

                    </div>


                    <p class="small text-muted">
                        The FAA knowledge-test fee, DPE/checkride fee, meals,
                        transportation, checkride aircraft charges, and
                        additional time required to satisfy FAA experience or
                        proficiency requirements are not included unless
                        specifically arranged.
                    </p>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-html2canvas-ignore="true">

                        Close

                    </button>

                    <a href="#apply" class="btn btn-primary" data-html2canvas-ignore="true">

                        <i class="fas fa-paper-plane me-2"></i>
                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>

    <section class="section-padding container">

        <div class="row g-4">

            {{-- =========================================================
            PROGRAM 5: MILITARY ROTARY-WING TO FIXED-WING
            ========================================================== --}}
            <div class="col-md-6">

                <div class="card card-light p-4 h-100">

                    <h3 class="gold-text mb-2">
                        15-Day Accelerated
                    </h3>

                    <h4 class="mb-2">
                        Military Pilot Rotary-Wing to Fixed-Wing
                    </h4>

                    <p class="text-muted mb-3">
                        <strong>Abbyero Aviation LLC</strong>
                    </p>

                    <div class="text-start">

                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>Piper PA-28-140</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>15 Days</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Dual Training
                                </small>
                                <strong>Approx. 30 Hours</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft Rate
                                </small>
                                <strong>$100/hour Dry</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Instructor
                                </small>
                                <strong>$50/hour</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Training Rate
                                </small>
                                <strong>$150/hour + Fuel</strong>
                            </div>

                        </div>

                        <hr>

                        <h5 class="gold-text">
                            Estimated Training Cost
                        </h5>

                        <p class="display-6 gold-text mb-3">
                            $4,500 + Fuel
                        </p>

                        <p>
                            A focused transition program designed for current or
                            former U.S. Army helicopter pilots moving from
                            rotary-wing operations into fixed-wing training.
                        </p>

                        <ul class="mb-3">
                            <li>Approximately 30 Hours Dual Instruction</li>
                            <li>Piper PA-28-140 Training Aircraft</li>
                            <li>15-Day Accelerated Program</li>
                            <li>Fixed-Wing Aircraft Transition Training</li>
                            <li>Commercial ASEL-Oriented Training</li>
                            <li>FAA Commercial ACS Preparation</li>
                        </ul>

                        <div class="alert alert-light border small">
                            <strong>Important:</strong>
                            The 30-hour transition program does not by itself
                            guarantee Commercial ASEL eligibility. Final
                            certification depends on the pilot's FAA certificates,
                            military records, airplane experience, and applicable
                            FAA aeronautical-experience requirements.
                        </div>

                    </div>

                    <div class="mt-auto">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#militaryPilotModal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            PROGRAM 6: 100-HOUR CPL TIME BUILDING
            ========================================================== --}}
            <div class="col-md-6">

                <div class="card card-light p-4 h-100">

                    <h3 class="gold-text mb-2">
                        30-Day Accelerated
                    </h3>

                    <h4 class="mb-2">
                        Commercial Pilot 100-Hour Time Building
                    </h4>

                    <p class="text-muted mb-3">
                        <strong>Abbyero Aviation LLC</strong>
                    </p>

                    <div class="text-start">

                        <div class="row g-3">

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft
                                </small>
                                <strong>Piper PA-28-140</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Duration
                                </small>
                                <strong>30 Days</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Flight Time
                                </small>
                                <strong>100 Hours</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Aircraft Rate
                                </small>
                                <strong>$80/hour Dry</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Accommodation
                                </small>
                                <strong>$70/day</strong>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">
                                    Instructor
                                </small>
                                <strong>Not Included</strong>
                            </div>

                        </div>

                        <hr>

                        <h5 class="gold-text">
                            Total 30-Day Package
                        </h5>

                        <p class="display-6 gold-text mb-3">
                            $10,100
                        </p>

                        <p>
                            An exclusive discounted time-building program for
                            returning Abbyero Aviation students working toward
                            the FAA Commercial Pilot certificate and remaining
                            aeronautical-experience requirements.
                        </p>

                        <ul class="mb-3">
                            <li>100 Hours PA-28-140 Aircraft Time</li>
                            <li>$80/hour Discounted Dry Rate</li>
                            <li>30-Day Accelerated Time Building</li>
                            <li>30 Days Accommodation</li>
                            <li>PIC & Cross-Country Experience Building</li>
                            <li>CPL-Oriented Flight Planning</li>
                        </ul>

                        <div class="alert alert-light border small">
                            <strong>Returning Students Only:</strong>
                            This discounted package is available to students who
                            have previously undertaken qualifying programs with
                            Abbyero Aviation LLC and meet the applicable FAA,
                            aircraft checkout, currency, rental, and insurance
                            requirements.
                        </div>

                    </div>

                    <div class="mt-auto">

                        <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#cplTimeBuildingModal">

                            <i class="fas fa-info-circle me-2"></i>
                            View Full Program Details

                        </button>

                        <a href="#apply" class="btn btn-primary w-100">

                            <i class="fas fa-paper-plane me-2"></i>
                            Apply / Enquire

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <div class="modal fade" id="militaryPilotModal" tabindex="-1" aria-labelledby="militaryPilotModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="militaryPilotPdfContent">

                {{-- =====================================================
                MODAL HEADER
                ====================================================== --}}

                <div class="modal-header program-modal-header">

                    {{-- =================================================
                    LOGO + PROGRAM TITLE
                    ================================================== --}}
                    <div class="program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                            alt="Abbyero Aviation"
                            class="program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1"
                                id="militaryPilotModalLabel">

                                15-Day Military Pilot
                                Rotary-Wing to Fixed-Wing
                                CPL Transition Program

                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC •
                                U.S. Army Aviators •
                                Piper PA-28-140
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                    HEADER ACTIONS
                    ================================================== --}}
                    <div class="program-modal-actions"
                        data-html2canvas-ignore="true">

                        <button type="button"
                            class="btn btn-outline-danger"
                            id="downloadMilitaryPilotPdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>


                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>


                {{-- =====================================================
                PDF CONTENT
                ====================================================== --}}

                <div class="modal-body">

                    {{-- PROGRAM SUMMARY --}}

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Aircraft
                                </small>

                                <strong>
                                    Piper PA-28-140
                                </strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Duration
                                </small>

                                <strong>
                                    15 Days
                                </strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Dual Instruction
                                </small>

                                <strong>
                                    Approximately 30 Hours
                                </strong>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Estimated Training Cost
                                </small>

                                <strong class="gold-text">
                                    $4,500 + Fuel
                                </strong>

                            </div>
                        </div>

                    </div>


                    {{-- PROGRAM OVERVIEW --}}

                    <h5 class="gold-text">
                        Program Overview
                    </h5>

                    <p>
                        The Abbyero Aviation LLC Military Pilot Rotary-Wing to
                        Fixed-Wing Transition Program is designed for U.S. Army
                        helicopter pilots transitioning from rotary-wing aviation
                        to fixed-wing commercial flying.
                    </p>

                    <p>
                        The program uses the Piper PA-28-140 and focuses on
                        developing safe, proficient fixed-wing aircraft control,
                        commercial maneuvers, navigation, cross-country operations,
                        and preparation for eventual Commercial Airplane
                        Single-Engine Land (ASEL) privileges.
                    </p>


                    {{-- IMPORTANT FAA DISTINCTION --}}

                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            Important FAA Qualification Notice
                        </h5>

                        <p class="mb-0">
                            Thirty hours of dual PA-28-140 training alone cannot
                            take an Army helicopter pilot with zero airplane
                            experience directly to a Commercial ASEL certificate.
                            Military helicopter experience may satisfy portions
                            of the FAA commercial aeronautical-experience
                            requirements, but substantial airplane-specific
                            experience is still required under
                            <strong>14 CFR §61.129</strong>.
                        </p>

                    </div>


                    {{-- WHO PROGRAM IS FOR --}}

                    <h5 class="gold-text mt-4">
                        Who This Program Is Designed For
                    </h5>

                    <ul>
                        <li>Current or former U.S. Army helicopter pilots</li>
                        <li>Military aviators with substantial rotorcraft flight time</li>
                        <li>Pilots transitioning from rotary-wing to fixed-wing aviation</li>
                        <li>
                            Military pilots who already hold, or qualify for,
                            an FAA Commercial Rotorcraft-Helicopter certificate
                            under military competency provisions
                        </li>
                        <li>
                            Pilots seeking eventual Commercial Airplane
                            Single-Engine Land (ASEL) privileges
                        </li>
                    </ul>


                    {{-- FAA MILITARY CONVERSION --}}

                    <h5 class="gold-text mt-4">
                        FAA Military Pilot Conversion
                    </h5>

                    <p>
                        Under <strong>14 CFR §61.73</strong>, qualifying U.S.
                        military pilots may obtain FAA commercial pilot certificates
                        and ratings based on their military qualifications.
                    </p>

                    <p>
                        An Army helicopter pilot can generally use qualifying
                        military credentials toward:
                    </p>

                    <ul>
                        <li>Commercial Pilot — Rotorcraft Helicopter</li>
                        <li>Instrument — Helicopter</li>
                    </ul>

                    <p>
                        The applicant must present the required military records
                        and meet the applicable military competency requirements,
                        including the required knowledge test.
                    </p>

                    <div class="alert alert-light border">

                        <strong>
                            Important:
                        </strong>

                        Army helicopter qualification does not automatically
                        provide an Airplane Single-Engine Land rating.

                    </div>


                    {{-- ADDING FIXED WING --}}

                    <h5 class="gold-text mt-4">
                        Adding Fixed-Wing Commercial Privileges
                    </h5>

                    <p>
                        A military helicopter pilot seeking Commercial ASEL is
                        adding a new aircraft category.
                    </p>

                    <p>
                        Under <strong>14 CFR §61.63(b)</strong>, an applicant
                        adding an aircraft category must:
                    </p>

                    <ul>
                        <li>Complete the applicable training</li>
                        <li>Meet the applicable aeronautical-experience requirements</li>
                        <li>Receive an authorized instructor endorsement</li>
                        <li>Pass the appropriate practical test</li>
                    </ul>

                    <p>
                        If the pilot already holds an FAA commercial certificate
                        at the applicable certificate level, an additional
                        knowledge test is generally not required for the category
                        addition.
                    </p>


                    {{-- COMMERCIAL ASEL REQUIREMENTS --}}

                    <h5 class="gold-text mt-4">
                        Commercial ASEL Minimum Experience
                    </h5>

                    <p>
                        For Commercial ASEL, <strong>§61.129(a)</strong> normally
                        requires the following aeronautical experience:
                    </p>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>Requirement</th>
                                    <th>FAA Minimum</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>Total flight time</td>
                                    <td>250 hr</td>
                                </tr>

                                <tr>
                                    <td>Powered-aircraft time</td>
                                    <td>100 hr</td>
                                </tr>

                                <tr>
                                    <td>Airplane time</td>
                                    <td>50 hr</td>
                                </tr>

                                <tr>
                                    <td>Total PIC</td>
                                    <td>100 hr</td>
                                </tr>

                                <tr>
                                    <td>PIC in airplanes</td>
                                    <td>50 hr</td>
                                </tr>

                                <tr>
                                    <td>Cross-country PIC</td>
                                    <td>50 hr</td>
                                </tr>

                                <tr>
                                    <td>Cross-country PIC in airplanes</td>
                                    <td>10 hr</td>
                                </tr>

                                <tr>
                                    <td>Required commercial training</td>
                                    <td>20 hr</td>
                                </tr>

                                <tr>
                                    <td>Solo or performing duties of PIC</td>
                                    <td>10 hr</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <p>
                        Qualifying helicopter experience may contribute toward
                        total flight time, powered-aircraft time, total PIC time,
                        and applicable cross-country totals.
                    </p>

                    <p>
                        It does not replace the airplane-specific requirements,
                        including the required PIC experience in airplanes.
                    </p>


                    {{-- ZERO FIXED WING --}}

                    <h5 class="gold-text mt-3">
                        Critical Point for a Zero-Fixed-Wing Army Pilot
                    </h5>

                    <p>
                        If an Army helicopter pilot arrives with no civilian or
                        military airplane-category experience, Abbyero Aviation
                        should not advertise the 30-hour course as sufficient by
                        itself for a Commercial ASEL certificate.
                    </p>

                    <p>
                        A practical pathway may include:
                    </p>

                    <ol>
                        <li>
                            <strong>Military competency conversion</strong> —
                            Obtain appropriate FAA Commercial Rotorcraft and
                            Instrument privileges from qualifying Army credentials.
                        </li>

                        <li>
                            <strong>Airplane transition and ASEL certification</strong> —
                            Obtain the airplane category/class rating.
                        </li>

                        <li>
                            <strong>Build required airplane PIC experience</strong> —
                            Work toward the applicable airplane PIC requirements.
                        </li>

                        <li>
                            <strong>Complete Commercial ASEL-specific training</strong> —
                            Meet §61.129 requirements and Commercial ACS standards.
                        </li>

                        <li>
                            <strong>Commercial ASEL practical test</strong>
                        </li>
                    </ol>


                    {{-- 15 DAY SYLLABUS --}}

                    <h5 class="gold-text mt-4">
                        Abbyero 15-Day / 30-Hour Training Syllabus
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Training Focus</th>
                                    <th>Dual</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>1–2</td>
                                    <td>Fixed-wing aerodynamics, PA-28 systems & aircraft control</td>
                                    <td>4 hr</td>
                                </tr>

                                <tr>
                                    <td>3–4</td>
                                    <td>Takeoffs, landings & traffic patterns</td>
                                    <td>4 hr</td>
                                </tr>

                                <tr>
                                    <td>5–6</td>
                                    <td>Slow flight, stalls & emergency procedures</td>
                                    <td>4 hr</td>
                                </tr>

                                <tr>
                                    <td>7–8</td>
                                    <td>Ground-reference & performance maneuvers</td>
                                    <td>4 hr</td>
                                </tr>

                                <tr>
                                    <td>9–10</td>
                                    <td>Cross-country navigation & flight planning</td>
                                    <td>4 hr</td>
                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td>Instrument-reference flying</td>
                                    <td>2 hr</td>
                                </tr>

                                <tr>
                                    <td>12</td>
                                    <td>Night/cross-country operations as applicable</td>
                                    <td>2 hr</td>
                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>Commercial maneuvers & accuracy landings</td>
                                    <td>2 hr</td>
                                </tr>

                                <tr>
                                    <td>14</td>
                                    <td>Commercial ACS proficiency</td>
                                    <td>2 hr</td>
                                </tr>

                                <tr>
                                    <td>15</td>
                                    <td>Mock practical test & final preparation</td>
                                    <td>2 hr</td>
                                </tr>

                                <tr class="fw-bold">
                                    <td colspan="2">Total</td>
                                    <td>30 hr</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <p class="small text-muted">
                        The actual syllabus must be adjusted after reviewing the
                        pilot's FAA certificates, Army flight records and civilian
                        logbook.
                    </p>


                    {{-- ADDITIONAL COMMERCIAL REQUIREMENTS --}}

                    <h5 class="gold-text mt-4">
                        Additional Commercial Requirements
                    </h5>

                    <p>
                        Commercial ASEL requirements may also include specific
                        training and experience such as:
                    </p>

                    <ul>
                        <li>
                            10 hours instrument training, including applicable
                            single-engine airplane requirements
                        </li>
                        <li>
                            10 hours in a complex, turbine-powered or technically
                            advanced airplane, or a combination
                        </li>
                        <li>Required daytime cross-country experience</li>
                        <li>Required nighttime cross-country experience</li>
                        <li>Practical-test preparation within the required period</li>
                        <li>Required solo or performing-duties-of-PIC experience</li>
                        <li>Required commercial cross-country experience</li>
                        <li>Required night VFR experience</li>
                    </ul>

                    <p>
                        The PA-28-140 program may therefore need to be supplemented
                        by Abbyero's <strong>PA-28R-200 Piper Arrow II</strong>
                        for applicable complex-airplane requirements if the
                        applicant has not already satisfied them.
                    </p>


                    {{-- COST --}}

                    <h5 class="gold-text mt-4">
                        15-Day Program Cost
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Component</th>
                                    <th>Calculation</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>PA-28-140 dry</td>
                                    <td>30 × $100</td>
                                    <td>$3,000</td>
                                </tr>

                                <tr>
                                    <td>Flight instructor</td>
                                    <td>30 × $50</td>
                                    <td>$1,500</td>
                                </tr>

                                <tr class="fw-bold">
                                    <td>Training Total</td>
                                    <td></td>
                                    <td>$4,500</td>
                                </tr>

                                <tr>
                                    <td>Fuel</td>
                                    <td>Not included</td>
                                    <td>Additional</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            Estimated Training Cost
                        </h5>

                        <strong>
                            $4,500 + Fuel
                        </strong>

                    </div>


                    {{-- ENTRY REVIEW --}}

                    <h5 class="gold-text mt-4">
                        Recommended Abbyero Entry Review
                    </h5>

                    <p>
                        Before quoting a military pilot a final fixed-wing CPL
                        package, Abbyero Aviation should obtain and review:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>FAA pilot certificates currently held</li>
                                <li>Army aeronautical orders/qualification records</li>
                                <li>Military competency documentation</li>
                                <li>Total military flight time</li>
                                <li>Helicopter PIC time</li>
                                <li>Military cross-country time</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Previous airplane time</li>
                                <li>Airplane PIC time, if any</li>
                                <li>Instrument qualifications</li>
                                <li>Civilian logbook records</li>
                            </ul>

                        </div>

                    </div>

                    <p>
                        This review is essential because two Army helicopter
                        pilots with substantial flight time could require very
                        different amounts of airplane training depending on their
                        existing FAA ratings and airplane experience.
                    </p>


                    {{-- PDF FOOTER / SUMMARY --}}

                    <div class="alert alert-light border mt-4 text-center" data-html2canvas-ignore="true">

                        <h5 class="gold-text">
                            ABBYERO AVIATION LLC
                        </h5>

                        <p class="mb-1">
                            Military Rotary-Wing to Fixed-Wing Transition Program
                        </p>

                        <strong>
                            U.S. Army Aviators • PA-28-140 • 15 Days • 30 Hours Dual
                        </strong>

                        <br>

                        <strong>
                            $4,500 + Fuel
                        </strong>

                    </div>

                </div>


                {{-- =====================================================
                MODAL FOOTER - NOT INCLUDED IN PDF
                ====================================================== --}}

                <div class="modal-footer" data-html2canvas-ignore="true">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>

                    <a href="#apply" class="btn btn-primary">

                        <i class="fas fa-paper-plane me-2"></i>
                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="cplTimeBuildingModal" tabindex="-1" aria-labelledby="cplTimeBuildingModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content" id="cplTimeBuildingPdfContent">

                {{-- =====================================================
                MODAL HEADER
                ====================================================== --}}
                <div class="modal-header program-modal-header">

                    {{-- =================================================
                    LOGO + PROGRAM TITLE
                    ================================================== --}}
                    <div class="program-modal-title">

                        <img src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                            alt="Abbyero Aviation"
                            class="program-modal-logo">

                        <div class="program-modal-title-text">

                            <h4 class="modal-title gold-text mb-1"
                                id="cplTimeBuildingModalLabel">

                                30-Day Commercial Pilot (CPL)
                                100-Hour Time-Building Program

                            </h4>

                            <small class="text-muted">
                                Abbyero Aviation LLC • Piper PA-28-140
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                    HEADER ACTIONS
                    ================================================== --}}
                    <div class="program-modal-actions"
                        data-html2canvas-ignore="true">

                        <button type="button"
                            class="btn btn-outline-danger"
                            id="downloadCplTimeBuildingPdf"
                            data-html2canvas-ignore="true">

                            <i class="fas fa-file-pdf me-2"></i>
                            <span>Download PDF</span>

                        </button>


                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            data-html2canvas-ignore="true">
                        </button>

                    </div>

                </div>
                {{-- =====================================================
                PDF CONTENT
                ====================================================== --}}

                <div class="modal-body">

                    {{-- PROGRAM SUMMARY --}}

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Aircraft
                                </small>

                                <strong>
                                    Piper PA-28-140
                                </strong>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Duration
                                </small>

                                <strong>
                                    30 Days
                                </strong>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Flight Time
                                </small>

                                <strong>
                                    100 Hours
                                </strong>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Complete Package
                                </small>

                                <strong class="gold-text">
                                    $10,100
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- PROGRAM PURPOSE --}}

                    <h5 class="gold-text">
                        Program Purpose
                    </h5>

                    <p>
                        The Abbyero Aviation LLC 100-Hour CPL Time-Building Program
                        is an accelerated, discounted program exclusively for
                        returning Abbyero Aviation students working toward the
                        250-hour FAA Commercial Pilot Certificate requirement
                        under Part 61.
                    </p>

                    <p>
                        Students receive 100 hours of Piper PA-28-140 aircraft
                        time at a discounted dry rate of only
                        <strong>$80 per hour</strong>.
                    </p>

                    <p>
                        The pilot flies independently as PIC, subject to applicable
                        FAA requirements, aircraft checkout, currency, weather,
                        dispatch and Abbyero Aviation operating policies.
                    </p>


                    {{-- PRICE --}}

                    <div class="alert alert-light border mt-4">

                        <h5 class="gold-text">
                            Exclusive Returning-Student Price
                        </h5>

                        <p class="mb-1">
                            Effective Aircraft Rate:
                        </p>

                        <strong class="fs-5">
                            $80/Hour Dry
                        </strong>

                    </div>


                    {{-- COST TABLE --}}

                    <h5 class="gold-text mt-4">
                        Program Cost
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>
                                    <th>Component</th>
                                    <th>Calculation</th>
                                    <th>Cost</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>PA-28-140 — 100 hours dry</td>
                                    <td>100 × $80</td>
                                    <td>$8,000</td>
                                </tr>

                                <tr>
                                    <td>Accommodation — 30 days</td>
                                    <td>30 × $70</td>
                                    <td>$2,100</td>
                                </tr>

                                <tr class="fw-bold">

                                    <td colspan="2">
                                        Total 30-Day Package
                                    </td>

                                    <td>
                                        $10,100
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <p>
                        Fuel, instructor expenses and other operating expenses not
                        specifically included in the package are separate.
                    </p>


                    {{-- 30 DAY PLAN --}}

                    <h5 class="gold-text mt-4">
                        30-Day Time-Building Plan
                    </h5>

                    <p>
                        The program targets approximately 100 hours within 30 days,
                        with the schedule adjusted for weather, maintenance and
                        aircraft availability.
                    </p>

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>

                                <tr>
                                    <th>Phase</th>
                                    <th>Days</th>
                                    <th>Training / Flight Objective</th>
                                    <th>Target Hours</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>1–3</td>
                                    <td>
                                        Aircraft checkout review & local PIC proficiency
                                    </td>
                                    <td>10</td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>4–10</td>
                                    <td>
                                        Short/intermediate cross-country PIC building
                                    </td>
                                    <td>25</td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>11–17</td>
                                    <td>
                                        Extended cross-country operations
                                    </td>
                                    <td>25</td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>18–23</td>
                                    <td>
                                        Cross-country, navigation & ATC experience
                                    </td>
                                    <td>20</td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>24–28</td>
                                    <td>
                                        CPL-focused PIC experience building
                                    </td>
                                    <td>15</td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>29–30</td>
                                    <td>
                                        Remaining hours / weather & scheduling makeup
                                    </td>
                                    <td>5</td>
                                </tr>

                                <tr class="fw-bold">

                                    <td colspan="3">
                                        Total
                                    </td>

                                    <td>
                                        100 Hours
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    {{-- OBJECTIVES --}}

                    <h5 class="gold-text mt-4">
                        Program Objectives
                    </h5>

                    <p>
                        Students use the 100 hours to strengthen:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>Pilot-in-command experience</li>
                                <li>Total flight time</li>
                                <li>Cross-country PIC experience</li>
                                <li>Flight planning</li>
                                <li>Navigation</li>
                                <li>ATC communications</li>
                                <li>Weather decision-making</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Fuel planning and management</li>
                                <li>Day operations</li>
                                <li>Night operations when properly qualified/current</li>
                                <li>Aeronautical decision-making</li>
                                <li>Long-distance cross-country confidence</li>
                                <li>Professional operating discipline</li>
                            </ul>

                        </div>

                    </div>


                    {{-- CPL EXPERIENCE --}}

                    <h5 class="gold-text mt-4">
                        CPL Experience Building
                    </h5>

                    <p>
                        Under <strong>14 CFR §61.129(a)</strong>, an applicant for
                        Commercial ASEL under Part 61 generally needs at least
                        250 hours total flight time, along with specific PIC,
                        cross-country, training and other aeronautical-experience
                        requirements.
                    </p>

                    <p>
                        The 100-hour package is intended to help qualifying Abbyero
                        students build the necessary experience efficiently.
                    </p>


                    {{-- LOGBOOK REVIEW --}}

                    <h5 class="gold-text mt-4">
                        Logbook Review Recommended
                    </h5>

                    <p>
                        Before starting the program, Abbyero Aviation should review
                        each student's logbook to determine:
                    </p>

                    <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li>Current total time</li>
                                <li>Existing PIC time</li>
                                <li>Cross-country PIC time</li>
                                <li>Night experience</li>
                                <li>Commercial training already completed</li>
                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul>
                                <li>Instrument-training requirements already satisfied</li>
                                <li>Complex/TAA requirements</li>
                                <li>Remaining §61.129 requirements</li>
                            </ul>

                        </div>

                    </div>

                    <p>
                        This allows the student's 100 hours to be planned as
                        efficiently as possible toward commercial eligibility.
                    </p>


                    {{-- ELIGIBILITY --}}

                    <h5 class="gold-text mt-4">
                        Eligibility
                    </h5>

                    <p>
                        This discounted program is only available to students who
                        have undertaken other qualifying programs with
                        Abbyero Aviation LLC.
                    </p>

                    <p>
                        Participants must:
                    </p>

                    <ul>
                        <li>Hold an appropriate pilot certificate</li>
                        <li>
                            Be legally qualified to act as PIC of the PA-28-140
                        </li>
                        <li>Meet applicable FAA currency requirements</li>
                        <li>
                            Successfully complete Abbyero Aviation's aircraft
                            checkout requirements
                        </li>
                        <li>Meet applicable rental/insurance requirements</li>
                        <li>Be working toward CPL certification</li>
                        <li>Receive Abbyero Aviation approval for enrollment</li>
                    </ul>


                    {{-- INCLUDED --}}

                    <h5 class="gold-text mt-4">
                        What's Included
                    </h5>

                    <ul>
                        <li>100 hours PA-28-140 aircraft time</li>
                        <li>$80/hour discounted dry rate</li>
                        <li>30-day accelerated time-building schedule</li>
                        <li>30 days accommodation</li>
                        <li>Flight scheduling support</li>
                        <li>CPL-oriented time-building planning</li>
                    </ul>


                    {{-- PACKAGE SUMMARY --}}

                    <div class="alert alert-light border mt-4 text-center" data-html2canvas-ignore="true">

                        <h5 class="gold-text">
                            ABBYERO AVIATION LLC
                        </h5>

                        <p class="mb-1">
                            Exclusive 100-Hour CPL Time-Building Program
                        </p>

                        <strong>
                            PA-28-140 • 100 Hours • 30 Days
                        </strong>

                        <br>

                        <strong>
                            Aircraft:
                            <span class="gold-text">$8,000</span>
                        </strong>

                        <br>

                        <strong>
                            Accommodation:
                            <span class="gold-text">$2,100</span>
                        </strong>

                        <br>

                        <strong>
                            Complete Package:
                            <span class="gold-text">$10,100</span>
                        </strong>

                    </div>


                    <p class="small text-muted mt-4">
                        Reaching 250 total hours does not by itself guarantee
                        Commercial Pilot practical-test eligibility; all applicable
                        requirements of §61.129 must also be satisfied.
                    </p>

                </div>


                {{-- =====================================================
                MODAL FOOTER - NOT INCLUDED IN PDF
                ====================================================== --}}

                <div class="modal-footer" data-html2canvas-ignore="true">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>

                    <a href="#apply" class="btn btn-primary">

                        <i class="fas fa-paper-plane me-2"></i>
                        Apply / Enquire

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- AIRCRAFT SECTION -->
    <section class="section-padding text-center">
        <div class="container">
            <h2 class="gold-text mb-3">
                Train on Proven, Performance-Driven Aircraft Built for Professional Pilot Development
            </h2>

            <p class="mb-5 fs-5">
                At Abbyero Aviation Flight School, your training takes place on carefully selected,
                high-performance aircraft designed to build confidence, precision, and real-world flying skills.
                From foundational training to complex aircraft endorsement, you gain hands-on experience
                that prepares you for advanced aviation pathways.
            </p>

            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('assets/images/aircraft/pa28-140.jpeg') }}" class="img-fluid rounded mb-3">
                    <h5>
                        <span class="highlight-hours">50 hours</span> in our N547FL model PA28-140 (160 HP)
                    </h5>
                </div>

                <div class="col-md-6">
                    <img src="{{ asset('assets/images/aircraft/pa28r-200.jpeg') }}" class="img-fluid rounded mb-3">
                    <h5>
                        <span class="highlight-hours">10 hours</span> in our N2204T model PA28R-200 with 200 HP
                        <em>(includes retractable landing gear complex endorsement)</em>
                    </h5>
                </div>
            </div>
            <div class="mt-4 text-center">

                <p class="fw-semibold text-success fs-4 fst-italic">
                    ✈ All aircraft are maintained to strict aviation safety standards and inspected regularly to ensure
                    reliability, performance, and student safety.
                </p>

                <a href="#apply" class="btn gold-btn mt-3 px-4">
                    Start Your Pilot Training Today
                </a>

            </div>
        </div>
    </section>

    <!-- ACCOMMODATION SLIDER -->
    <section class="section-padding container text-center">
        <h2 class="gold-text mb-3">
            Comfortable, Private Accommodation Designed for Focused Pilot Training
        </h2>

        <p class="mb-4 fs-5">
            During your 30-day intensive program, you’ll stay at iCare Home in a private bedroom
            with a private bathroom, shower, and toilet — providing the comfort, privacy, and quiet
            environment you need to fully concentrate on your flight training journey.
        </p>

        <div id="houseCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded">

                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/carehomes/001 Nursing Home.jpg') }}" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/020 Nursing Home.jpg') }}" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/boardroom.jpeg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/012 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/013 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/014 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/016 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/018 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/005 Nursing Home.jpg') }}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/carehomes/004 Nursing Home.jpg') }}" class="d-block w-100">
                </div>

            </div>
        </div>
        <div class="mt-4 text-center">

            <p class="fw-semibold text-success fs-4 fst-italic">
                🏡 Safe, secure, and conveniently located — giving you peace of mind while you focus entirely on your flight
                training.
            </p>

        </div>
    </section>

    <!-- APPLY FORM -->
    <section id="apply" class="section-padding container">

    <div class="text-center mb-4">

        <h2 class="gold-text mb-2">
            Apply Now
        </h2>

        <p class="text-muted mb-0">
            Start your aviation training application with Abbyero Aviation LLC.
            After submitting your application, you will receive an email with
            a secure link to complete your onboarding information.
        </p>

    </div>


    <div class="card card-dark p-4 p-md-5">

        <form
            id="flight-apply-form"
            method="POST"
            action="{{ route('application.store') }}"
            novalidate
        >

            @csrf


            {{-- =========================================================
            PERSONAL INFORMATION
            ========================================================== --}}

            <h5 class="gold-text mb-3">
                Applicant Information
            </h5>

            <div class="row">

                {{-- FULL NAME --}}
                <div class="col-md-6 mb-3">

                    <label for="application-name" class="form-label">
                        Full Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="application-name"
                        name="name"
                        class="form-control"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- EMAIL --}}
                <div class="col-md-6 mb-3">

                    <label for="application-email" class="form-label">
                        Email Address
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        id="application-email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email address"
                        autocomplete="email"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- PHONE --}}
                <div class="col-md-6 mb-3">

                    <label for="application-phone" class="form-label">
                        Phone Number
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="tel"
                        id="application-phone"
                        name="phone"
                        class="form-control"
                        placeholder="Enter your phone number"
                        autocomplete="tel"
                        maxlength="50"
                        required
                    >

                </div>


                {{-- PROGRAM --}}
                <div class="col-md-6 mb-3">

                    <label for="application-program" class="form-label">
                        Program of Interest
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        id="application-program"
                        name="program"
                        class="form-select"
                        required
                    >
                        <option value="" selected disabled>
                            Select a training program
                        </option>

                        <option value="30-Day Accelerated Private Pilot (PPL)">
                            30-Day Accelerated Private Pilot (PPL)
                        </option>

                        <option value="30-Day Accelerated Instrument Rating">
                            30-Day Accelerated Instrument Rating Training
                        </option>

                        <option value="30-Day Accelerated Commercial Pilot (CPL)">
                            30-Day Accelerated Commercial Pilot (CPL)
                        </option>

                        <option value="15-Day Accelerated Multi-Engine">
                            15-Day Accelerated Multi-Engine Training
                        </option>

                        <option value="15-Day Accelerated Military Pilot Rotary-Wing to Fixed-Wing">
                            15-Day Accelerated Military Pilot Rotary-Wing to Fixed-Wing
                        </option>

                        <option value="30-Day Accelerated Commercial Pilot 100-Hour Time Building">
                            30-Day Accelerated Commercial Pilot 100-Hour Time Building
                        </option>

                        <option value="Other Aviation Training">
                            Other Aviation Training
                        </option>

                    </select>

                </div>


                {{-- PREFERRED START DATE --}}
                <div class="col-md-6 mb-3">

                    <label
                        for="application-start-date"
                        class="form-label"
                    >
                        Preferred Training Start Date
                    </label>

                    <input
                        type="date"
                        id="application-start-date"
                        name="preferred_start_date"
                        class="form-control"
                    >

                    <small class="text-muted">
                        Preferred date only; final scheduling is subject
                        to availability.
                    </small>

                </div>

            </div>


            <hr class="my-4">


            {{-- =========================================================
            MESSAGE
            ========================================================== --}}

            <h5 class="gold-text mb-3">
                Additional Information
            </h5>

            <div class="mb-3">

                <label for="application-message" class="form-label">
                    Message
                </label>

                <textarea
                    id="application-message"
                    name="message"
                    class="form-control"
                    rows="4"
                    maxlength="5000"
                    placeholder="Tell us about your aviation goals, previous flight experience, or any questions you may have."
                ></textarea>

            </div>


            {{-- =========================================================
            INFORMATION NOTICE
            ========================================================== --}}

            <div class="alert alert-info small mb-4">

                <i class="fas fa-info-circle me-2"></i>

                <strong>What happens next?</strong>

                <p class="mb-0 mt-2">

                    After submitting this application, Abbyero Aviation LLC
                    will send you an email containing a secure onboarding link.
                    Use that link to provide additional personal, aviation,
                    travel, and training information required for your
                    enrollment.

                </p>

            </div>


            {{-- =========================================================
            SUBMIT
            ========================================================== --}}

            <button
                type="submit"
                id="flight-apply-submit"
                class="btn gold-btn w-100"
            >

                <i class="fas fa-paper-plane me-2"></i>

                <span id="flight-apply-submit-text">
                    Submit Application
                </span>

            </button>

        </form>

    </div>

    </section>

    {{-- =========================================================
    SWEETALERT
    ========================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('flight-apply-form');

        if (!form) {
            return;
        }


        const submitButton =
            document.getElementById('flight-apply-submit');

        const submitText =
            document.getElementById('flight-apply-submit-text');


        form.addEventListener('submit', async function (event) {

            event.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | CLIENT-SIDE VALIDATION
            |--------------------------------------------------------------------------
            */

            if (!form.checkValidity()) {

                form.classList.add('was-validated');

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | SAVE ORIGINAL BUTTON
            |--------------------------------------------------------------------------
            */

            const originalText =
                submitText.innerHTML;


            try {

                /*
                |--------------------------------------------------------------------------
                | DISABLE SUBMIT BUTTON
                |--------------------------------------------------------------------------
                */

                submitButton.disabled = true;

                submitText.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true">
                    </span>
                    Submitting Application...
                `;


                /*
                |--------------------------------------------------------------------------
                | FORM DATA
                |--------------------------------------------------------------------------
                */

                const formData = new FormData(form);


                /*
                |--------------------------------------------------------------------------
                | SEND APPLICATION
                |--------------------------------------------------------------------------
                */

                const response = await fetch(
                    form.action,
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                formData.get('_token'),

                            'Accept':
                                'application/json'
                        },

                        body: formData
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | READ RESPONSE
                |--------------------------------------------------------------------------
                */

                const data = await response.json();


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                if (response.ok && data.success) {

                    await Swal.fire({

                        icon: 'success',

                        title: 'Application Submitted',

                        text: data.message ||
                            'Your application has been received. Please check your email for your secure onboarding link.',

                        confirmButtonText: 'Great!',

                        confirmButtonColor: '#b28a2e'

                    });


                    /*
                    |--------------------------------------------------------------------------
                    | RESET FORM
                    |--------------------------------------------------------------------------
                    */

                    form.reset();

                    form.classList.remove('was-validated');


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONAL: SCROLL BACK TO FORM
                    |--------------------------------------------------------------------------
                    */

                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });


                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDATION / SERVER ERROR
                |--------------------------------------------------------------------------
                */

                let errorMessage =
                    data.message ||
                    'Unable to submit your application. Please check your information and try again.';


                if (data.errors) {

                    const errors = Object.values(data.errors)
                        .flat()
                        .filter(Boolean);

                    if (errors.length) {

                        errorMessage = errors.join('<br>');

                    }

                }


                await Swal.fire({

                    icon: 'error',

                    title: 'Unable to Submit',

                    html: errorMessage,

                    confirmButtonText: 'Try Again',

                    confirmButtonColor: '#b28a2e'

                });

            } catch (error) {

                console.error(
                    'Flight application submission failed:',
                    error
                );


                await Swal.fire({

                    icon: 'error',

                    title: 'Something Went Wrong',

                    text:
                        'We were unable to submit your application. Please check your internet connection and try again.',

                    confirmButtonText: 'Try Again',

                    confirmButtonColor: '#b28a2e'

                });

            } finally {

                /*
                |--------------------------------------------------------------------------
                | RESTORE BUTTON
                |--------------------------------------------------------------------------
                */

                submitButton.disabled = false;

                submitText.innerHTML =
                    originalText;

            }

        });

    });

    </script>

    <!-- WHATSAPP FLOAT -->
    <a href="https://wa.me/+19159995352" class="whatsapp-float" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton =
                document.getElementById('downloadPPLProgramPdf');

            const pdfContent =
                document.getElementById('pplProgramPdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML =
                    downloadButton.innerHTML;

                const modalBody =
                    pdfContent.closest('.modal')?.querySelector('.modal-body');

                const modalDialog =
                    pdfContent.closest('.modal-dialog');

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    Generating PDF...
                                                `;

                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE MODAL SCROLLING
                    |--------------------------------------------------------------------------
                    */

                    if (modalBody) {

                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE MODAL DIALOG RESTRICTIONS
                    |--------------------------------------------------------------------------
                    */

                    if (modalDialog) {

                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | PDF OPTIONS
                    |--------------------------------------------------------------------------
                    */

                    const options = {

                        margin: 10,

                        filename:
                            'Abbyero-Aviation-30-Day-Accelerated-PPL-Program.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0

                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true

                        },

                        pagebreak: {

                            mode: [
                                'css',
                                'legacy'
                            ],

                            avoid: [
                                'table',
                                'tr',
                                'h3',
                                'h4'
                            ]

                        }

                    };

                    /*
                    |--------------------------------------------------------------------------
                    | GENERATE PDF
                    |--------------------------------------------------------------------------
                    */

                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();

                } catch (error) {

                    console.error(
                        '30-Day Accelerated PPL PDF generation failed:',
                        error
                    );

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );

                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHTML;

                }

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton = document.getElementById('downloadCplPdf');
            const pdfContent = document.getElementById('commercialPilotPdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML = downloadButton.innerHTML;

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                                                        Generating PDF...
                                                                    `;

                    /*
                    |--------------------------------------------------------------------------
                    | Temporarily remove scroll restrictions
                    |--------------------------------------------------------------------------
                    */

                    const modalBody = pdfContent.querySelector('.modal-body');

                    const originalBodyStyles = {
                        maxHeight: modalBody ? modalBody.style.maxHeight : '',
                        height: modalBody ? modalBody.style.height : '',
                        overflow: modalBody ? modalBody.style.overflow : '',
                        overflowY: modalBody ? modalBody.style.overflowY : ''
                    };

                    if (modalBody) {
                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Temporarily remove modal scrolling
                    |--------------------------------------------------------------------------
                    */

                    const modalDialog = pdfContent.closest('.modal-dialog');

                    const originalDialogStyles = {
                        maxHeight: modalDialog ? modalDialog.style.maxHeight : '',
                        height: modalDialog ? modalDialog.style.height : '',
                        overflow: modalDialog ? modalDialog.style.overflow : ''
                    };

                    if (modalDialog) {
                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PDF options
                    |--------------------------------------------------------------------------
                    */

                    const options = {

                        margin: [
                            10,
                            10,
                            10,
                            10
                        ],

                        filename: 'Abbyero-Aviation-30-Day-CPL-Program.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0,

                            windowWidth: document.documentElement.scrollWidth,

                            windowHeight: document.documentElement.scrollHeight
                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true
                        },

                        pagebreak: {

                            mode: [
                                'css',
                                'legacy'
                            ]

                        }

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | Generate PDF
                    |--------------------------------------------------------------------------
                    */

                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();


                    /*
                    |--------------------------------------------------------------------------
                    | Restore modal styles
                    |--------------------------------------------------------------------------
                    */

                    if (modalBody) {

                        modalBody.style.maxHeight =
                            originalBodyStyles.maxHeight;

                        modalBody.style.height =
                            originalBodyStyles.height;

                        modalBody.style.overflow =
                            originalBodyStyles.overflow;

                        modalBody.style.overflowY =
                            originalBodyStyles.overflowY;
                    }


                    if (modalDialog) {

                        modalDialog.style.maxHeight =
                            originalDialogStyles.maxHeight;

                        modalDialog.style.height =
                            originalDialogStyles.height;

                        modalDialog.style.overflow =
                            originalDialogStyles.overflow;
                    }


                } catch (error) {

                    console.error('PDF generation failed:', error);

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );

                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML = originalButtonHTML;

                }

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton =
                document.getElementById('downloadInstrumentPdf');

            const pdfContent =
                document.getElementById('instrumentRatingPdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML =
                    downloadButton.innerHTML;

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                                    Generating PDF...
                                                                `;

                    /*
                    |--------------------------------------------------------------------------
                    | Temporarily remove modal scrolling
                    |--------------------------------------------------------------------------
                    */

                    const modalBody =
                        pdfContent.querySelector('.modal-body');

                    const originalBodyStyles = {
                        maxHeight: modalBody
                            ? modalBody.style.maxHeight
                            : '',

                        height: modalBody
                            ? modalBody.style.height
                            : '',

                        overflow: modalBody
                            ? modalBody.style.overflow
                            : '',

                        overflowY: modalBody
                            ? modalBody.style.overflowY
                            : ''
                    };

                    if (modalBody) {

                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Remove dialog height restrictions
                    |--------------------------------------------------------------------------
                    */

                    const modalDialog =
                        pdfContent.closest('.modal-dialog');

                    const originalDialogStyles = {
                        maxHeight: modalDialog
                            ? modalDialog.style.maxHeight
                            : '',

                        height: modalDialog
                            ? modalDialog.style.height
                            : '',

                        overflow: modalDialog
                            ? modalDialog.style.overflow
                            : ''
                    };

                    if (modalDialog) {

                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PDF options
                    |--------------------------------------------------------------------------
                    */

                    const options = {

                        margin: [
                            0,
                            10,
                            10,
                            10
                        ],

                        filename:
                            'Abbyero-Aviation-30-Day-Instrument-Rating.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0,

                            windowWidth:
                                document.documentElement.scrollWidth,

                            windowHeight:
                                document.documentElement.scrollHeight

                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true

                        },

                        pagebreak: {

                            mode: [
                                'css',
                                'legacy'
                            ]

                        }

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | Generate PDF
                    |--------------------------------------------------------------------------
                    */

                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();


                    /*
                    |--------------------------------------------------------------------------
                    | Restore modal body
                    |--------------------------------------------------------------------------
                    */

                    if (modalBody) {

                        modalBody.style.maxHeight =
                            originalBodyStyles.maxHeight;

                        modalBody.style.height =
                            originalBodyStyles.height;

                        modalBody.style.overflow =
                            originalBodyStyles.overflow;

                        modalBody.style.overflowY =
                            originalBodyStyles.overflowY;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Restore modal dialog
                    |--------------------------------------------------------------------------
                    */

                    if (modalDialog) {

                        modalDialog.style.maxHeight =
                            originalDialogStyles.maxHeight;

                        modalDialog.style.height =
                            originalDialogStyles.height;

                        modalDialog.style.overflow =
                            originalDialogStyles.overflow;

                    }

                } catch (error) {

                    console.error(
                        'Instrument Rating PDF generation failed:',
                        error
                    );

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );

                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHTML;

                }

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton =
                document.getElementById('downloadMultiEnginePdf');

            const pdfContent =
                document.getElementById('multiEnginePdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML =
                    downloadButton.innerHTML;

                const modalBody =
                    pdfContent.querySelector('.modal-body');

                const modalDialog =
                    pdfContent.closest('.modal-dialog');

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                            <span class="spinner-border spinner-border-sm me-2"></span>
                                                            Generating PDF...
                                                        `;


                    /* Remove modal scrolling */

                    if (modalBody) {

                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';

                    }


                    /* Remove modal dialog restrictions */

                    if (modalDialog) {

                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';

                    }


                    const options = {

                        margin: 10,

                        filename:
                            'Abbyero-Aviation-15-Day-Multi-Engine-Training.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0

                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true

                        },

                        pagebreak: {

                            mode: [
                                'css',
                                'legacy'
                            ]

                        }

                    };


                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();


                } catch (error) {

                    console.error(
                        'Multi-Engine PDF generation failed:',
                        error
                    );

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );

                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHTML;

                }

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton =
                document.getElementById('downloadMilitaryPilotPdf');

            const pdfContent =
                document.getElementById('militaryPilotPdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML =
                    downloadButton.innerHTML;

                const modalBody =
                    pdfContent.closest('.modal')?.querySelector('.modal-body');

                const modalDialog =
                    pdfContent.closest('.modal-dialog');

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    Generating PDF...
                                                `;


                    /* =====================================================
                    REMOVE MODAL SCROLLING
                    ===================================================== */

                    if (modalBody) {

                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';

                    }


                    /* =====================================================
                    REMOVE MODAL DIALOG RESTRICTIONS
                    ===================================================== */

                    if (modalDialog) {

                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';

                    }


                    /* =====================================================
                    PDF OPTIONS
                    ===================================================== */

                    const options = {

                        margin: 10,

                        filename:
                            'Abbyero-Aviation-15-Day-Military-Pilot-Rotary-to-Fixed-Wing-Transition.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0

                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true

                        }

                    };


                    /* =====================================================
                    GENERATE PDF
                    ===================================================== */

                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();


                } catch (error) {

                    console.error(
                        'Military Pilot PDF generation failed:',
                        error
                    );

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );


                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHTML;

                }

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const downloadButton =
                document.getElementById('downloadCplTimeBuildingPdf');

            const pdfContent =
                document.getElementById('cplTimeBuildingPdfContent');

            if (!downloadButton || !pdfContent) {
                return;
            }

            downloadButton.addEventListener('click', async function () {

                const originalButtonHTML =
                    downloadButton.innerHTML;

                const modalBody =
                    pdfContent.closest('.modal')?.querySelector('.modal-body');

                const modalDialog =
                    pdfContent.closest('.modal-dialog');

                try {

                    downloadButton.disabled = true;

                    downloadButton.innerHTML = `
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    Generating PDF...
                                                `;


                    /* Remove modal scrolling */

                    if (modalBody) {

                        modalBody.style.maxHeight = 'none';
                        modalBody.style.height = 'auto';
                        modalBody.style.overflow = 'visible';
                        modalBody.style.overflowY = 'visible';

                    }


                    /* Remove modal dialog restrictions */

                    if (modalDialog) {

                        modalDialog.style.maxHeight = 'none';
                        modalDialog.style.height = 'auto';
                        modalDialog.style.overflow = 'visible';

                    }


                    const options = {

                        margin: 10,

                        filename:
                            'Abbyero-Aviation-30-Day-100-Hour-CPL-Time-Building-Program.pdf',

                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },

                        html2canvas: {

                            scale: 2,

                            useCORS: true,

                            allowTaint: true,

                            backgroundColor: '#ffffff',

                            scrollX: 0,

                            scrollY: 0

                        },

                        jsPDF: {

                            unit: 'mm',

                            format: 'a4',

                            orientation: 'portrait',

                            compress: true

                        }

                    };


                    await html2pdf()
                        .set(options)
                        .from(pdfContent)
                        .save();


                } catch (error) {

                    console.error(
                        '100-Hour CPL Time-Building PDF generation failed:',
                        error
                    );

                    alert(
                        'Unable to generate the PDF. Please try again.'
                    );

                } finally {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHTML;

                }

            });

        });
    </script>

@endsection