@extends('layoutstudent')

@section('content')
    <div class="container mt-5 mb-5">
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'instructor', 'student']))

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
    
                        <!-- Image Placeholder -->
                        @include('components.profile-image')

                        <!-- Title -->
                        <h3 class="mb-0 text-center text-md-start">
                            Student Profile
                        </h3>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-column flex-sm-row gap-2">

                        <a href="{{ route('flight.schedules') }}" class="btn btn-success shadow-sm">
                            <i class="bi bi-calendar-event me-1"></i>
                            Schedule Flight
                        </a>

                        <a href="{{ route('logbooks.index') }}" class="btn btn-primary shadow-sm">
                            <i class="bi bi-journal-text me-1"></i>
                            Logbooks
                        </a>

                    </div>

                </div>

            @endif
        @endauth

        @if(session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <script>
            setTimeout(function () {
                let alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500); // remove after fade
                }
            }, 5000); // 5 seconds
        </script>

        <div class="accordion" id="accountAccordion">

            <div class="row">
                <!-- Account Details -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAccount">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
                                Profile Details
                            </button>
                        </h2>
                        <div id="collapseAccount" class="accordion-collapse collapse" aria-labelledby="headingAccount"
                            data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="{{ ucfirst(Auth::user()->role) }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Joined On</label>
                                        <input type="text" class="form-control"
                                            value="{{ Auth::user()->created_at->format('F j, Y') }}" readonly>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('password.request') }}" class="btn btn-outline-primary">Change
                                        Password</a>
                                    <a href="{{ route('logout') }}" class="btn btn-outline-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructor -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingInstructor">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseInstructor" aria-expanded="false"
                                aria-controls="collapseInstructor">
                                My Instructor
                            </button>
                        </h2>
                        <div id="collapseInstructor" class="accordion-collapse collapse" aria-labelledby="headingInstructor"
                            data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>Your assigned instructor helps track your training progress.</p>

                                @if($student && $student->instructor)
                                    <div class="alert alert-info">
                                        <strong>Name:</strong> {{ $student->instructor->name }} <br>
                                        <strong>Email:</strong> {{ $student->instructor->email }}
                                    </div>

                                    <button class="btn btn-danger">Remove Instructor</button>
                                @else
                                    <p class="text-muted">No instructor assigned yet.</p>

                                    <form method="POST" action="{{ route('student.request.instructor') }}">
                                        @csrf

                                        <input type="email" name="email" placeholder="Enter instructor email"
                                            class="form-control mb-3" required>

                                        <button class="btn btn-primary">Request Instructor</button>
                                    </form>
                                    <div class="mt-3">
                                        <h6>Instructor Requests</h6>

                                        @foreach($requests as $req)
                                            <div class="alert alert-secondary d-flex justify-content-between">
                                                <span>{{ $req->instructor->email }}</span>

                                                @if($req->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($req->status == 'accepted')
                                                    <span class="badge bg-success">Accepted</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Flight School Invitations -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFlightSchool">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFlightSchool" aria-expanded="false"
                                aria-controls="collapseFlightSchool">
                                My Flight Schedules
                            </button>
                        </h2>
                        <div id="collapseFlightSchool" class="accordion-collapse collapse show"
                            aria-labelledby="headingFlightSchool" data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>Track all your scheduled training or travel flights below.</p>

                                @if($schedules->isEmpty())
                                    <p class="text-muted">You haven't scheduled any flights yet.</p>
                                @else
                                    <ul class="list-group">
                                        @foreach($schedules as $schedule)

                                            @php
                                                $flight = $schedule->flight; // from flights table
                                                $start = \Carbon\Carbon::parse($schedule->start_time);
                                                $end = \Carbon\Carbon::parse($schedule->end_time);
                                                $duration = $start->diff($end)->format('%h hrs %i mins');
                                                $isPast = $end->isPast();
                                            @endphp

                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row gap-2 
                                                {{ $isPast ? 'bg-light text-muted' : 'border-start border-4 border-primary' }}">

                                                <div class="flex-fill">
                                                    <strong>
                                                        {{ $flight->aircraft_model ?? 'N/A' }}
                                                        ({{ $flight->registration_number ?? 'N/A' }})
                                                    </strong><br>

                                                    <small>
                                                        {{ $start->format('d M Y H:i') }} - {{ $end->format('H:i') }}
                                                    </small><br>

                                                    <small class="text-muted">
                                                        Duration: {{ $duration }}
                                                    </small><br>

                                                    <small class="text-muted">
                                                        From: {{ $flight->departure_location ?? 'N/A' }}
                                                    </small><br>

                                                    <small>
                                                        Status:
                                                        <span class="badge 
                                                            @if($schedule->status == 'scheduled') bg-primary
                                                            @elseif($schedule->status == 'completed') bg-success
                                                            @elseif($schedule->status == 'cancelled') bg-danger
                                                            @else bg-warning
                                                            @endif">
                                                            {{ ucfirst($schedule->status) }}
                                                        </span>
                                                    </small>
                                                </div>

                                                <div class="d-flex gap-2 mt-2" style="min-width: 140px;">

                                                    {{-- View Flight --}}
                                                    @if($flight)
                                                        <a href="{{ route('flights.show', [$flight->id, 'schedule_id' => $schedule->id]) }}"
                                                            class="btn btn-outline-primary btn-sm w-50">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Cancel --}}
                                                    @if($schedule->status === 'scheduled')
                                                        <form action="{{ route('schedules.cancel', $schedule->id) }}" method="POST"
                                                            class="w-50">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-outline-danger btn-sm w-100">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif


                                                </div>

                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProgress">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseProgress" aria-controls="collapseProgress" aria-expanded="false">
                                Training Progress
                            </button>
                        </h2>
                        <div id="collapseProgress" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                <p class="mb-3">Your real-time training progress overview.</p>

                                {{-- Doughnut Chart: Approved vs Pending --}}
                                <div class="d-flex justify-content-center my-3">
                                    <div class="card shadow-sm text-center p-2" style="width:260px;">
                                        <h6 class="mb-2">Training Progress</h6>
                                        <div style="height:220px;">
                                            <canvas id="hoursChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bar Chart: Scheduled vs Flown --}}
                                <div class="mb-4">
                                    <h6 class="text-center">Scheduled vs Flown Hours per Aircraft</h6>
                                    <canvas id="scheduleChart"></canvas>
                                </div>

                                {{-- Scripts --}}
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    // Doughnut Chart: Approved vs Pending
                                    new Chart(document.getElementById('hoursChart'), {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Approved', 'Pending'],
                                            datasets: [{
                                                label: 'Hours',
                                                data: [{{ $approvedHours }}, {{ $totalHours - $approvedHours }}],
                                                backgroundColor: ['#198754', '#ffc107'],
                                            }]
                                        },
                                        options: {
                                            responsive: true,

                                            // 🔥 KEY SETTINGS
                                            maintainAspectRatio: false,

                                            plugins: {
                                                legend: { position: 'bottom' },
                                                title: {
                                                    display: true,
                                                    text: 'Approved vs Pending Logbook Hours',
                                                    font: { size: 14 } // smaller title
                                                }
                                            }
                                        }
                                    });

                                    // Bar Chart: Scheduled vs Flown
                                    new Chart(document.getElementById('scheduleChart'), {
                                        type: 'bar',
                                        data: {
                                            labels: [
                                                @foreach($schedules as $schedule)
                                                    '{{ $schedule->aircraft }}',
                                                @endforeach
                                                                        ],
                                            datasets: [
                                                {
                                                    label: 'Scheduled (hrs)',
                                                    data: [
                                                        @foreach($schedules as $schedule)
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->start_time)) / 60 }},
                                                        @endforeach
                                                                                ],
                                                    backgroundColor: '#0d6efd',
                                                },
                                                {
                                                    label: 'Flown (hrs)',
                                                    data: [
                                                        @foreach($logs as $log)
                                                            {{ $log->hours }},
                                                        @endforeach
                                                                                ],
                                                    backgroundColor: '#198754',
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: { position: 'bottom' },
                                                title: { display: true, text: 'Scheduled vs Flown Hours' }
                                            },
                                            scales: {
                                                y: { beginAtZero: true, title: { display: true, text: 'Hours' } }
                                            }
                                        }
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingLogbook">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseLogbook">
                                Flight Logbook
                            </button>
                        </h2>
                        <div id="collapseLogbook" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                {{-- 🔹 Flight Statistics --}}
                                <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                    <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                        <h5 class="text-primary mb-3">Flight Summary</h5>

                                        <div class="row text-center g-3">
                                            <div class="col-md-3 col-6">
                                                <div class="p-2 border rounded bg-white shadow-sm">
                                                    <strong>Total Scheduled</strong><br>
                                                    {{ floor($totalScheduledMinutes / 60) }}h
                                                    {{ $totalScheduledMinutes % 60 }}m
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-2 border rounded bg-white shadow-sm">
                                                    <strong>Total Flown</strong><br>
                                                    {{ floor($totalFlownMinutes / 60) }}h {{ $totalFlownMinutes % 60 }}m
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-2 border rounded bg-white shadow-sm">
                                                    <strong>Remaining</strong><br>
                                                    {{ floor($remainingMinutes / 60) }}h {{ $remainingMinutes % 60 }}m
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-2 border rounded bg-white shadow-sm">
                                                    <strong>Total Cost</strong><br>
                                                    ${{ number_format($totalCost, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 🔹 Progress Bar --}}
                                    @php
                                        $progressPercent = $totalScheduledMinutes > 0
                                            ? min(100, ($totalFlownMinutes / $totalScheduledMinutes) * 100)
                                            : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progressPercent }}%" aria-valuenow="{{ $progressPercent }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ round($progressPercent, 1) }}%
                                        </div>
                                    </div>
                                </div>

                                {{-- 🔹 Add Log Entry --}}
                                <!-- <form id="logbookForm" method="POST" action="{{ route('logbook.store') }}"
                                    class="row g-2 mb-4">
                                    @csrf
                                    <input type="hidden" name="schedule_id" id="schedule_id">

                                    <div class="col-md-2">
                                        <input type="date" name="flight_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-2">

                                        @php
                                            $latestLogs = $latestLogPerAircraft ?? [];
                                        @endphp

                                        <select name="aircraft" id="aircraftSelect" class="form-control" required>
                                            <option value="" disabled selected>Select Aircraft</option>

                                            @foreach($schedules as $schedule)

                                                @php
                                                    $reg = $schedule->flight->registration_number ?? null;
                                                    $log = $latestLogs[$reg] ?? null;

                                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                                    $now = \Carbon\Carbon::now();

                                                    $isActive = $now->between($start, $schedule->end_time);
                                                @endphp

                                                @if($reg)
                                                    <option value="{{ $reg }}" data-hobbs="{{ $log->hobbs_end ?? 0 }}"
                                                        data-tach="{{ $log->tach_end ?? 0.3 }}">
                                                        ({{ $reg }}) - {{ $start->format('d M H:i') }}

                                                        @if($isActive)
                                                            🔴 ACTIVE NOW
                                                        @else
                                                            🟢 UPCOMING
                                                        @endif
                                                    </option>
                                                @endif

                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-2">
                                        <input type="text" name="route" placeholder="Route" class="form-control">
                                    </div>

                                    {{-- 🔹 HOBBS START --}}
                                    <div class="col-md-2">
                                        <input type="number" step="0.1" name="hobbs_start" placeholder="Hobbs Start"
                                            class="form-control" required>
                                    </div>

                                    {{-- 🔹 HOBBS END --}}
                                    <div class="col-md-2">
                                        <input type="number" step="0.1" name="hobbs_end" placeholder="Hobbs End"
                                            class="form-control" required>
                                    </div>

                                    {{-- 🔹 TACH START --}}
                                    <div class="col-md-2">
                                        <input type="number" step="0.1" name="tach_start" placeholder="Tach Start"
                                            class="form-control">
                                    </div>

                                    {{-- 🔹 TACH END --}}
                                    <div class="col-md-2">
                                        <input type="number" step="0.1" name="tach_end" placeholder="Tach End"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <select name="type" class="form-control">
                                            <option value="dual">Dual</option>
                                            <option value="solo">Solo</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 pb-4">
                                        <button class="btn btn-success w-100">+</button>
                                    </div>
                                </form> -->

                                {{-- 🔹 Logbook Table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Aircraft</th>
                                                <th>Route</th>
                                                <th>Type</th>
                                                <th>HOBBS</th>
                                                <th>TACH</th>
                                                <th>Flight Time</th>
                                                <th>Remaining Minutes</th>
                                                <th>Cost (USD)</th>
                                                <th>Status</th>
                                                <th>AI Analysis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logs as $log)
                                                @php
                                                    $flownMinutes = $log->hours * 60;
                                                @endphp

                                                <tr>

                                                    {{-- Flight Date --}}
                                                    <td>{{ $log->flight_date }}</td>

                                                    {{-- Aircraft --}}
                                                    <td>{{ $log->aircraft }}</td>

                                                    {{-- Route --}}
                                                    <td>{{ $log->route }}</td>

                                                    {{-- Flight Type --}}
                                                    <td>{{ ucfirst($log->type) }}</td>

                                                    {{-- HOBBS --}}
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-primary">
                                                                <i class="bi bi-speedometer2 me-1"></i>
                                                                {{ number_format($log->hobbs_start,1) }}
                                                                →
                                                                {{ number_format($log->hobbs_end,1) }}
                                                            </span>

                                                            <small class="text-muted">
                                                                Total:
                                                                {{ number_format($log->hobbs_time,1) }} hrs
                                                            </small>
                                                        </div>
                                                    </td>

                                                    {{-- TACH --}}
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-warning">
                                                                <i class="bi bi-tools me-1"></i>
                                                                {{ number_format($log->tach_start,1) }}
                                                                →
                                                                {{ number_format($log->tach_end,1) }}
                                                            </span>

                                                            <small class="text-muted">
                                                                Engine:
                                                                {{ number_format($log->tach_time,1) }} hrs
                                                            </small>
                                                        </div>
                                                    </td>

                                                    {{-- Flight Time --}}
                                                    <td>
                                                        {{ $flownMinutes }} min
                                                    </td>

                                                    {{-- Remaining Time --}}
                                                    <td>
                                                        {{ floor($log->remaining_minutes / 60) }}h
                                                        {{ $log->remaining_minutes % 60 }} min

                                                        @if($log->block_time)
                                                            <br>
                                                            <small class="badge bg-info">
                                                                Block Time
                                                            </small>
                                                        @endif
                                                    </td>

                                                    {{-- Flight Cost --}}
                                                    <td>
                                                        <strong>
                                                            {{ number_format($log->flight_cost,2) }}
                                                        </strong>
                                                    </td>

                                                    {{-- Status --}}
                                                    <td>
                                                        @if($log->approved)
                                                            <span class="badge bg-success">
                                                                Approved
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                Pending
                                                            </span>

                                                            @if(auth()->user()->role === 'instructor')
                                                                <form method="POST"
                                                                    action="{{ route('logbook.approve', $log->id) }}"
                                                                    class="mt-1">
                                                                    @csrf

                                                                    <button class="btn btn-sm btn-success">
                                                                        Approve
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    {{-- AI Analysis --}}
                                                    <td>

                                                        <button class="btn btn-sm btn-primary"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#ai-{{ $log->id }}">
                                                            View
                                                        </button>

                                                        <div id="ai-{{ $log->id }}"
                                                            class="collapse mt-2 text-start">

                                                            @if($log->aiAnalysis)

                                                                <div class="card p-2 bg-light">

                                                                    <strong>Summary</strong>
                                                                    <p class="mb-2">
                                                                        {{ $log->aiAnalysis->summary }}
                                                                    </p>

                                                                    <strong>Feedback</strong>
                                                                    <p class="mb-2">
                                                                        {{ $log->aiAnalysis->feedback }}
                                                                    </p>

                                                                    <strong>Flags</strong>

                                                                    @foreach(json_decode($log->aiAnalysis->flags, true) as $flag)
                                                                        <span class="badge bg-danger d-block mb-1">
                                                                            ⚠️ {{ $flag }}
                                                                        </span>
                                                                    @endforeach

                                                                </div>

                                                            @else

                                                                <span class="text-muted">
                                                                    No AI analysis available.
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </td>

                                                </tr>

                                            @endforeach
                                        </tbody>                                    
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#calendarSection">
                                Training Calendar
                            </button>
                        </h2>

                        <div id="calendarSection" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                {{-- HEADER --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>✈️ My Flight Intelligence Dashboard</h5>
                                    <span class="badge bg-dark">Student View</span>
                                </div>

                                <div class="row">

                                    {{-- ================= LEFT PANEL ================= --}}
                                    <div class="col-md-4">

                                        {{-- 🔥 UTILIZATION HEATMAP --}}
                                        <div class="card shadow-sm mb-3">
                                            <div class="card-body">
                                                <h6>📊 Weekly Activity</h6>
                                                <div id="heatmap" style="height:150px;"></div>
                                            </div>
                                        </div>

                                        {{-- ⏱ AIRCRAFT USAGE --}}
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h6>⏱ Aircraft Usage</h6>
                                                <canvas id="usageChart"></canvas>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- ================= RIGHT PANEL ================= --}}
                                    <div class="col-md-8">

                                        <div class="card shadow-sm">
                                            <div class="card-body">

                                                {{-- LEGEND --}}
                                                <div class="mb-2 d-flex gap-3 flex-wrap small">
                                                    <span><span class="legend student"></span> My Flights</span>
                                                    <span><span class="legend completed"></span> Completed</span>
                                                    <span><span class="legend cancelled"></span> Cancelled</span>
                                                </div>

                                                <div id="calendar" style="height:500px;"></div>
                                                <div class="modal fade" id="aircraftCalendarModal" tabindex="-1">
                                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="aircraftTitle">
                                                                    Aircraft Calendar
                                                                </h5>

                                                                <button type="button" class="btn btn-primary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div id="aircraftCalendar" style="height:70vh;"></div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="eventModal" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="eventModalTitle">
                                                                    Flight Details
                                                                </h5>

                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal">
                                                                </button>
                                                            </div>

                                                            <div class="modal-body" id="eventModalBody"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                {{-- LIBRARIES --}}
                                <link href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.8/index.global.min.css"
                                    rel="stylesheet">
                                <script
                                    src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.8/index.global.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




                                {{-- STYLES --}}
                                <style>
                                    .legend {
                                        width: 12px;
                                        height: 12px;
                                        display: inline-block;
                                        border-radius: 3px;
                                        margin-right: 5px;
                                    }

                                    .student {
                                        background: #0d6efd;
                                    }

                                    .completed {
                                        background: #2ecc71;
                                    }

                                    .cancelled {
                                        background: #e74c3c;
                                    }
                                </style>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dialogs -->
                <dialog id="invite-modal" style="padding:30px;border:none;border-radius:25px">
                    <h3>Do you accept or reject this invitation?</h3>
                    <p id="invite-statement"></p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-primary" id="accept-invite" onclick="acceptInvite()">Accept</button>
                        <button class="btn btn-danger" id="reject-invite" onclick="rejectInvite()">Reject</button>
                    </div>
                </dialog>

                <dialog id="attach-modal" style="padding:30px;border:none;border-radius:25px">
                    <h3><span id="instructor_name"></span> has multiple organizations.</h3>
                    <h5>Select which to be attached to below</h5>
                    <h6 class="small">If you are not sure, please contact your instructor.</h6>
                    <table class="table" id="multiple_org_table">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <button class="btn btn-secondary mt-3" id="closeAttachModal">Cancel</button>
                </dialog>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {

                        const calendarEl = document.getElementById('calendar');
                        let aircraftCalendar = null;

                        let calendar = new FullCalendar.Calendar(calendarEl, {

                            initialView: 'resourceTimelineDay',

                            // 🔥 REQUIRED FOR AIRCRAFT ROWS
                            resources: '/student/aircraft-resources',

                            resourceAreaHeaderContent: 'Aircraft',

                            slotMinTime: "06:00:00",
                            slotMaxTime: "20:00:00",

                            nowIndicator: true,
                            eventDisplay: 'block',
                            height: "auto",
                            editable: true,

                            // 🔥 EVENTS
                            events: '/student/calendar-events',

                            resourceLabelDidMount: function (info) {

                                info.el.style.cursor = 'pointer';

                                info.el.onclick = function () {

                                    openAircraftCalendar(
                                        info.resource.id,
                                        info.resource.title
                                    );

                                };
                            },

                            eventClick: function (info) {

                                const event = info.event;

                                document.getElementById('eventModalTitle').innerText =
                                    event.title;

                                document.getElementById('eventModalBody').innerHTML = `
                                        <div class="mb-2">
                                            <strong>Flight:</strong> ${event.title}
                                        </div>

                                        <div class="mb-2">
                                            <strong>Start:</strong>
                                            ${event.start.toLocaleString()}
                                        </div>

                                        <div class="mb-2">
                                            <strong>End:</strong>
                                            ${event.end ? event.end.toLocaleString() : 'N/A'}
                                        </div>

                                        <div class="mb-2">
                                            <strong>Status:</strong>
                                            ${event.extendedProps.status || 'scheduled'}
                                        </div>
                                    `;

                                const modal = bootstrap.Modal.getOrCreateInstance(
                                    document.getElementById('eventModal')
                                );

                                modal.show();
                            },

                            eventDidMount: function (info) {
                                const status = info.event.extendedProps.status;

                                const colors = {
                                    scheduled: '#0d6efd',
                                    completed: '#198754',
                                    cancelled: '#dc3545',
                                    pending: '#ffc107'
                                };

                                info.el.style.backgroundColor = colors[status] || '#6c757d';
                                info.el.style.borderColor = colors[status] || '#6c757d';

                                if (status === 'completed') {
                                    info.el.style.opacity = 0.7;
                                }
                            },

                            eventContent: function (arg) {
                                return {
                                    html: `
                                            <div style="font-size:11px;">
                                                <strong>${arg.event.title}</strong><br>
                                                <span>${arg.event.extendedProps.status}</span>
                                            </div>
                                        `
                                };
                            },

                            // ✈️ DRAG & DROP
                            eventDrop: function (info) {

                                fetch(`/student/schedule/${info.event.id}/reschedule`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        start: info.event.start.toISOString(),
                                        end: info.event.end.toISOString()
                                    })
                                })
                                    .then(res => res.json())
                                    .then(data => {

                                        if (!data.success) {
                                            showToast('⚠️ Conflict detected', 'danger');
                                            info.revert();
                                        } else {
                                            showToast('✅ Updated', 'success');
                                        }

                                    });
                            }

                        });

                        calendar.render();

                        window.openAircraftCalendar = function (flightId, title) {

                            document.getElementById('aircraftTitle').innerText =
                                title + ' Calendar';

                            const modalEl =
                                document.getElementById('aircraftCalendarModal');

                            const modal =
                                bootstrap.Modal.getOrCreateInstance(modalEl);

                            modal.show();

                            modalEl.addEventListener('hidden.bs.modal', function () {

                                if (aircraftCalendar) {
                                    aircraftCalendar.destroy();
                                    aircraftCalendar = null;
                                }

                                // Cleanup Bootstrap modal state
                                document.body.classList.remove('modal-open');

                                document.querySelectorAll('.modal-backdrop')
                                    .forEach(el => el.remove());

                                document.body.style.overflow = '';
                                document.body.style.paddingRight = '';
                            });

                            setTimeout(() => {

                                if (aircraftCalendar) {
                                    aircraftCalendar.destroy();
                                }

                                aircraftCalendar = new FullCalendar.Calendar(
                                    document.getElementById('aircraftCalendar'),
                                    {

                                        initialView: 'timeGridWeek',

                                        selectable: false,

                                        editable: false,

                                        height: 'auto',

                                        nowIndicator: true,

                                        eventDisplay: 'block',

                                        // Aircraft-specific schedules
                                        events: function (fetchInfo, successCallback, failureCallback) {

                                            fetch(`/student/calendar-events?flight_id=${flightId}`)
                                                .then(res => res.json())
                                                .then(data => successCallback(data))
                                                .catch(failureCallback);

                                        },

                                        eventDidMount: function (info) {

                                            const status =
                                                info.event.extendedProps.status;

                                            const colors = {
                                                scheduled: '#0d6efd',
                                                completed: '#198754',
                                                cancelled: '#dc3545',
                                                pending: '#ffc107'
                                            };

                                            info.el.style.backgroundColor =
                                                colors[status] || '#6c757d';

                                            info.el.style.borderColor =
                                                colors[status] || '#6c757d';
                                        },

                                        eventClick(info) {

                                            Swal.fire({
                                                title: info.event.title,

                                                html: `
                                        <div style="text-align:left">

                                            <p>
                                                <b>Start:</b>
                                                ${info.event.start.toLocaleString()}
                                            </p>

                                            <p>
                                                <b>End:</b>
                                                ${info.event.end
                                                        ? info.event.end.toLocaleString()
                                                        : 'N/A'}
                                            </p>

                                            <p>
                                                <b>Status:</b>
                                                ${info.event.extendedProps.status || 'scheduled'}
                                            </p>

                                        </div>
                                    `,

                                                icon: 'info'
                                            });

                                        }

                                    }
                                );

                                aircraftCalendar.render();

                            }, 300);

                        };

                        // 🔥 FIX: re-render when accordion opens
                        document.getElementById('calendarSection')
                            .addEventListener('shown.bs.collapse', function () {
                                calendar.updateSize();
                            });

                        // 🔔 TOAST FUNCTION (NO ALERTS)
                        function showToast(message, type = 'info') {
                            let toast = document.createElement('div');
                            toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;
                            toast.style.zIndex = 9999;
                            toast.innerText = message;

                            document.body.appendChild(toast);

                            setTimeout(() => {
                                toast.remove();
                            }, 3000);
                        }

                    });
                </script>

                {{-- ================= AIRCRAFT USAGE CHART ================= --}}
                <script>
                    console.log('Logbook script loaded');
                    new Chart(document.getElementById('usageChart'), {
                        type: 'bar',
                        data: {
                            labels: [
                                @foreach($schedules->groupBy('flight.registration_number') as $aircraft => $group)
                                    '{{ $aircraft }}',
                                @endforeach
                        ],
                            datasets: [{
                                label: 'Aircraft Usage (Hours)',
                                data: [
                                    @foreach($schedules->groupBy('flight.registration_number') as $group)
                                        {{ round($group->sum(fn($s) => \Carbon\Carbon::parse($s->end_time)->diffInMinutes($s->start_time) / 60), 2) }},
                                    @endforeach
                            ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Aircraft Utilization'
                                }
                            }
                        }
                    });
                    const heatmapEl = document.getElementById('heatmap');

                    const usageByDay = {
                        0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0
                    };

                    @foreach($schedules as $s)
                        usageByDay[{{ \Carbon\Carbon::parse($s->start_time)->dayOfWeek }}]++;
                    @endforeach

                    heatmapEl.innerHTML = Object.keys(usageByDay).map(day => {

                        let intensity = usageByDay[day] / 5; // normalize
                        if (intensity > 1) intensity = 1;

                        return `
                            <div style="
                                display:inline-block;
                                width:30px;
                                height:30px;
                                margin:3px;
                                border-radius:4px;
                                background:rgba(13,110,253,${intensity});
                                text-align:center;
                                color:white;
                                font-size:10px;
                                line-height:30px;
                            ">
                                ${usageByDay[day]}
                            </div>
                        `;
                    }).join('');

                    document.getElementById('aircraftSelect').addEventListener('change', function () {

                        const opt = this.options[this.selectedIndex];

                        const hobbs = parseFloat(opt.dataset.hobbs || 0);
                        const tach = parseFloat(opt.dataset.tach || 0.3);

                        const hobbsStart = document.querySelector('[name="hobbs_start"]');
                        const tachStart = document.querySelector('[name="tach_start"]');

                        if (hobbs) {
                            hobbsStart.value = hobbs;
                            hobbsStart.readOnly = true;
                        }

                        if (tach) {
                            tachStart.value = tach;
                            tachStart.readOnly = true;
                        }
                    });

                    function autoCalculate() {

                        const hobbsStart = parseFloat(document.querySelector('[name="hobbs_start"]').value || 0);
                        const hobbsEnd = parseFloat(document.querySelector('[name="hobbs_end"]').value || 0);

                        const tachStart = parseFloat(document.querySelector('[name="tach_start"]').value || 0);
                        const tachEnd = parseFloat(document.querySelector('[name="tach_end"]').value || 0);

                        const hobbsDiff = hobbsEnd - hobbsStart;
                        const tachDiff = tachEnd - tachStart;

                        // attach preview fields (optional)
                        console.log('Hobbs Hours:', hobbsDiff.toFixed(2));
                        console.log('Tach Hours:', tachDiff.toFixed(2));

                        return {
                            hobbsDiff,
                            tachDiff
                        };
                    }
                </script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


                <script>
                    document.addEventListener('DOMContentLoaded', function () {

                        const form = document.getElementById('logbookForm');
                        const aircraftSelect = document.getElementById('aircraftSelect');

                        if (!form || !aircraftSelect) return;

                        /* =========================
                         AUTO FILL START FROM LAST LOG END
                        ========================= */
                        aircraftSelect.addEventListener('change', function () {

                            const opt = this.selectedOptions[0];

                            const hobbs = parseFloat(opt.dataset.hobbs || 0);
                            const tach = parseFloat(opt.dataset.tach || 0.3);

                            const hobbsStart = document.querySelector('[name="hobbs_start"]');
                            const tachStart = document.querySelector('[name="tach_start"]');

                            if (hobbsStart) {
                                hobbsStart.value = hobbs.toFixed(1);
                                hobbsStart.readOnly = true;
                            }

                            if (tachStart) {
                                tachStart.value = tach.toFixed(1);
                                tachStart.readOnly = true;
                            }
                        });

                        /* =========================
                         CALCULATE
                        ========================= */
                        function calc() {

                            const hs = parseFloat(document.querySelector('[name="hobbs_start"]')?.value || 0);
                            const he = parseFloat(document.querySelector('[name="hobbs_end"]')?.value || 0);

                            const ts = parseFloat(document.querySelector('[name="tach_start"]')?.value || 0.3);
                            const te = parseFloat(document.querySelector('[name="tach_end"]')?.value || 0);

                            return {
                                hobbs: he - hs,
                                tach: te - ts
                            };
                        }

                        /* =========================
                         VALIDATION
                        ========================= */
                        form.addEventListener('submit', function (e) {

                            const { hobbs, tach } = calc();

                            if (hobbs < 0 || tach < 0) {
                                e.preventDefault();
                                return Swal.fire('Invalid Entry', 'End must be greater than start', 'error');
                            }

                            if (Math.abs(hobbs - tach) < 0.2) {
                                e.preventDefault();
                                return Swal.fire({
                                    icon: 'warning',
                                    title: 'Time mismatch, Tach and Hobbs differ by less than 0.2 hours',
                                    html: `Hobbs: ${hobbs.toFixed(2)}<br>Tach: ${tach.toFixed(2)}`
                                });
                            }

                        });

                    });
                </script>
@endsection