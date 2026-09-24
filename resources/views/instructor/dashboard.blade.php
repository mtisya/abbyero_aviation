@extends('layoutinstructor')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3 mb-3">
    
                <!-- Profile image -->
                @include('components.profile-image')

                <!-- Title -->
                <h3 class="mb-0 text-center text-md-start">
                    Instructor Profile
                </h3>

            </div>

           {{-- Action Buttons --}}
           <div class="d-flex flex-column flex-sm-row gap-2 w-100 justify-content-center justify-content-sm-end">

                <!-- Flight Schedules -->
                <a href="{{ route('flight.schedules') }}"
                class="btn shadow-sm btn-info flex-fill flex-sm-auto">
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
                <a href="{{ route('logbooks.index') }}"
                class="btn shadow-sm btn-success flex-fill flex-sm-auto">
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
                <a href="{{ route('users.list') }}"
                class="btn shadow-sm btn-dark flex-fill flex-sm-auto">
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

            <div class="row">
                <!-- Account Details -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAccount">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
                                Account Details
                            </button>
                        </h2>
                        <div id="collapseAccount" class="accordion-collapse collapse show" aria-labelledby="headingAccount"
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

                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#assignedStudents">
                                My Students
                            </button>
                        </h2>

                        <div id="assignedStudents" class="accordion-collapse collapse">
                            <div class="accordion-body">

                                @if($students->isEmpty())
                                    <p class="text-muted">No students assigned yet.</p>
                                @else
                                    <ul class="list-group">

                                        @foreach($students as $student)
                                            <li class="list-group-item">

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $student->user->name }}</strong><br>
                                                        <small>{{ $student->user->email }}</small>
                                                    </div>

                                                    {{-- 🔥 VIEW PDF --}}
                                                    <a href="{{ route('student.profile.pdf', $student->id) }}" 
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                        View Student Profile in PDF
                                                    </a>
                                                </div>

                                                {{-- 🔥 STUDENT LOGS --}}
                                                <div class="mt-3">
                                                    <h6 class="text-muted">Logbook Entries</h6>
                                                    <!-- <form method="GET" action="{{ route('student.profile.pdf', $student->id) }}" target="_blank">
                                                        <input type="date" name="from">
                                                        <input type="date" name="to">
                                                        <button class="btn btn-sm btn-primary">Filter PDF</button>
                                                    </form> -->

                                                    @forelse($student->logbooks as $log)
                                                        <div class="border rounded p-2 mb-2">

                                                            <div class="d-flex justify-content-between">
                                                                <div>
                                                                    <strong>{{ $log->flight_date }}</strong> |
                                                                    {{ $log->aircraft }} |
                                                                    {{ $log->hours }} hrs
                                                                </div>

                                                                <div>
                                                                    @if($log->approved)
                                                                        <span class="badge bg-success">Approved</span>
                                                                    @else
                                                                        <span class="badge bg-warning">Pending</span>

                                                                        {{-- ✅ APPROVE BUTTON --}}
                                                                        <form method="POST"
                                                                            action="{{ route('logbook.approve', $log->id) }}"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            <button class="btn btn-sm btn-success">
                                                                                Approve
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @empty
                                                        <p class="text-muted">No logs yet.</p>
                                                    @endforelse
                                                </div>

                                            </li>
                                        @endforeach

                                    </ul>
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
                                Flight Rental Details
                            </button>
                        </h2>
                        <div id="collapseFlightSchool" class="accordion-collapse collapse"
                            aria-labelledby="headingFlightSchool" data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>As a client, you can book multiple flight trips. Listed below are your bookings.</p>

                                @if($bookedFlights->isEmpty())
                                    <p class="text-muted">You haven't booked any flights yet.</p>
                                @else
                                    <ul class="list-group">
                                        @foreach($bookedFlights as $booking)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row gap-2">
                                                <div class="flex-fill">
                                                    <strong>{{ $booking->flight->aircraft_model }}
                                                        ({{ $booking->flight->registration_number }})</strong><br>
                                                    <small>
                                                        {{ \Carbon\Carbon::parse($booking->flight->departure_time)->format('d M Y H:i') }}
                                                        from {{ $booking->flight->departure_location }}
                                                    </small>
                                                </div>

                                                <div class="d-flex flex-wrap gap-2 mt-2">

                                                    {{-- View Flight --}}
                                                    <a href="{{ route('flights.show', $booking->flight->id) }}"
                                                        class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    {{-- Download Ticket --}}
                                                    <a href="{{ route('booking.download', $booking->id) }}"
                                                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip" title="Download Ticket PDF">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </a>

                                                    {{-- Cancel Booking --}}
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
                                                        onclick="confirmCancel({{ $booking->id }})" data-bs-toggle="tooltip"
                                                        title="Cancel Booking">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>

                                                    {{-- Hidden Cancel Form --}}
                                                    <form id="cancel-form-{{ $booking->id }}"
                                                        action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

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
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#pendingRequests">
                                Pending Requests
                            </button>
                        </h2>

                        <div id="pendingRequests" class="accordion-collapse collapse">
                            <div class="accordion-body">

                                @foreach($requests as $req)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>{{ $req->student->user->name }}</span>

                                        <div>
                                            <a href="{{ route('instructor.request.accept', $req->id) }}"
                                                class="btn btn-success btn-sm">Accept</a>

                                            <a href="{{ route('instructor.request.reject', $req->id) }}"
                                                class="btn btn-danger btn-sm">Reject</a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div> <!-- End of accordion -->

    </div>

    <script>
        const notifications = @json(auth()->user()->unreadNotifications);
    </script>

@endsection