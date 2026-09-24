@extends('layoutadmin')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

            <div class="d-flex align-items-center gap-3 mb-3">

                <!-- Profile Image -->
                @include('components.profile-image')

                <!-- Title -->
                <h3 class="mb-0 text-center text-md-start">
                    Admin Profile
                </h3>

            </div>

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 justify-content-center justify-content-sm-end">

                <!-- Flight Schedules -->
                <a href="{{ route('flight.schedules') }}" class="btn shadow-sm btn-info flex-fill flex-sm-auto">
                    <i class="bi bi-airplane-engines me-1"></i>
                    Flight Schedules
                </a>

                <!-- Aircraft Maintenance -->
                <a href="{{ route('aircraftmaintenance.dashboard') }}"
                    class="btn shadow-sm btn-primary flex-fill flex-sm-auto">
                    <i class="bi bi-tools me-1"></i>
                    Aircraft Maintenance
                </a>

                <!-- Logbooks -->
                <a href="{{ route('logbooks.index') }}" class="btn shadow-sm btn-success flex-fill flex-sm-auto">
                    <i class="bi bi-journal-text me-1"></i>
                    Logbooks
                </a>

                <!-- Aircraft Parts -->
                <a href="{{ route('aircraft_parts.list') }}"
                    class="btn shadow-sm btn-warning text-dark flex-fill flex-sm-auto">
                    <i class="bi bi-gear-wide-connected me-1"></i>
                    Aircraft Parts
                </a>

                <!-- User Management -->
                <a href="{{ route('users.list') }}" class="btn shadow-sm btn-dark flex-fill flex-sm-auto">
                    <i class="bi bi-people-fill me-1"></i>
                    Manage Users
                </a>

            </div>

        </div>


        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

            <div class="row mb-4">
                <!-- Account Details -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAccount">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                                Account Details
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
                                Instructor
                            </button>
                        </h2>

                        <div id="collapseInstructor" class="accordion-collapse collapse" aria-labelledby="headingInstructor"
                            data-bs-parent="#accountAccordion">

                            <div class="accordion-body">

                                <!-- 🔹 Instructor Management -->
                                <p class="mb-3">
                                    Manage instructors and monitor their performance across student approvals.
                                </p>

                                <input id="cfi_email_address" type="email" class="form-control mb-3"
                                    placeholder="Enter instructor email">

                                <div id="added_instructor" class="mb-2"></div>
                                <div id="student-invitations" class="d-flex flex-column gap-2 mb-3"></div>

                                <div class="d-flex gap-2 mb-4">
                                    <button class="btn btn-primary" id="save-instructor" style="display:none">
                                        Add Instructor
                                    </button>

                                    <button class="btn btn-danger" id="remove-instructor" style="display:none">
                                        Remove
                                    </button>
                                </div>

                                <hr>

                                <!-- 🔥 Instructor Performance Section -->
                                <h6 class="mb-3">Instructor Performance</h6>

                                @if(isset($instructorStats) && $instructorStats->count())
                                    @foreach($instructorStats as $inst)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                                            <div>
                                                <strong>{{ $inst->name }}</strong>
                                                <br>
                                                <small class="text-muted">Approvals handled</small>
                                            </div>

                                            <span class="badge bg-primary rounded-pill px-3">
                                                {{ $inst->approvals }}
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: {{ min($inst->approvals * 10, 100) }}%"></div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">No instructor performance data available.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-3">
                    <div class="card border-0 shadow-lg rounded-4 p-3">
                        <small class="text-muted">Total Flight Hours</small>
                        <h2 class="fw-bold text-primary">{{ number_format($totalHours, 1) }}</h2>
                        <span class="text-muted">hrs logged</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-lg rounded-4 p-3">
                        <small class="text-muted">Approved Hours</small>
                        <h2 class="fw-bold text-success">{{ number_format($approvedHours, 1) }}</h2>
                        <span class="text-muted">verified</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-lg rounded-4 p-3">
                        <small class="text-muted">Total Revenue</small>
                        <h2 class="fw-bold text-dark">${{ number_format($totalRevenue, 2) }}</h2>
                        <span class="text-muted">generated</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-lg rounded-4 p-3">
                        <small class="text-muted">Total Students</small>
                        <h2 class="fw-bold text-warning">{{ $studentsCount }}</h2>
                        <span class="text-muted">active users</span>
                    </div>
                </div>

            </div>
            <div class="row g-4">

                <!-- Left Column: Charts -->
                <div class="col-lg-8">
                    <div class="row g-4">

                        <!-- Aircraft Usage -->
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-4 p-3 h-100">
                                <h6 class="mb-3">Aircraft Usage (Hours)</h6>
                                <canvas id="aircraftHoursChart"></canvas>
                            </div>
                        </div>

                        <!-- Flight Status -->
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-4 p-3 h-100">
                                <h6 class="mb-3">Flight Status</h6>
                                <canvas id="flightStatusChart"></canvas>
                            </div>
                        </div>

                        <!-- Monthly Trend (Full Width) -->
                        <div class="col-12">
                            <div class="card shadow-sm rounded-4 p-3 h-100">
                                <h6 class="mb-3">Monthly Flight Hours</h6>
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Aircraft Availability -->
                <div class="col-lg-4">
                    <div class="card shadow-sm rounded-4 p-3 h-100">
                        <h6>Aircraft Availability</h6>

                        @foreach($aircraftHours as $aircraft)
                            @php
                                $hours = $aircraft->total_hours;
                                $color = $hours > 95 ? 'danger' : ($hours > 80 ? 'warning' : 'success');
                            @endphp

                            <div class="mb-3">
                                <strong>{{ $aircraft->aircraft }}</strong>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                        style="width: {{ min($hours, 100) }}%">
                                        {{ $hours }} hrs
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // Aircraft Hours
                new Chart(document.getElementById('aircraftHoursChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($aircraftHours->pluck('aircraft')),
                        datasets: [{
                            label: 'Hours',
                            data: @json($aircraftHours->pluck('total_hours'))
                        }]
                    }
                });

                // Flight Status
                new Chart(document.getElementById('flightStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($flightStats->keys()),
                        datasets: [{
                            data: @json($flightStats->values())
                        }]
                    }
                });

                // Monthly Hours
                new Chart(document.getElementById('monthlyChart'), {
                    type: 'line',
                    data: {
                        labels: @json($monthlyHours->pluck('month')),
                        datasets: [{
                            label: 'Hours',
                            data: @json($monthlyHours->pluck('total_hours'))
                        }]
                    }
                });
            </script>
            <script>
                document.getElementById('profile_image').addEventListener('change', function () {

                    if (this.files.length > 0) {
                        document.getElementById('profileImageForm').submit();
                    }

                });
            </script>
            <script>
                const notifications = @json(auth()->user()->unreadNotifications);
            </script>

@endsection