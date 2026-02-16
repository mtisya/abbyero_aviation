@extends('layoutadmin')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

    {{-- Title --}}
    <h2 class="mb-3 mb-md-0 text-center text-md-start">
        Admin Account
    </h2>

    {{-- Action Buttons --}}
    <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">

        <a href="{{ route('admin.bookings') }}"
           class="btn btn-secondary w-100 w-sm-auto">
            + Booked Flights
        </a>

        <a href="{{ route('aircraft_parts.list') }}"
           class="btn btn-primary w-100 w-sm-auto">
            + Manage Parts
        </a>

        <a href="{{ route('users.list') }}"
           class="btn btn-dark w-100 w-sm-auto">
            + Manage Users
        </a>

    </div>
</div>


        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                                <p>You can add your instructor's email address here. Adding an instructor will allow them to
                                    monitor your course progress.</p>
                                <input id="cfi_email_address" type="email" class="form-control mb-3">
                                <div id="added_instructor"></div>
                                <div id="student-invitations" class="d-flex flex-column gap-2 mb-3"></div>
                                <button class="btn btn-primary" id="save-instructor" style="display:none">Add
                                    Instructor</button>
                                <button class="btn btn-danger" id="remove-instructor" style="display:none">Remove
                                    Instructor</button>
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

                                                <div
                                                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 mt-2 mt-md-0">
                                                    {{-- View Button --}}
                                                    <a href="{{ route('flights.show', $booking->flight->id) }}"
                                                        class="btn btn-sm btn-outline-primary w-100 w-md-auto text-center">
                                                        View
                                                    </a>

                                                    {{-- Cancel Button with SweetAlert --}}
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger w-100 w-md-auto text-center"
                                                        onclick="confirmCancel({{ $booking->id }})">
                                                        Cancel
                                                    </button>

                                                    {{-- Hidden Cancel Form --}}
                                                    <form id="cancel-form-{{ $booking->id }}"
                                                        action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                                                        style="display: none;">
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
                        <h2 class="accordion-header" id="headingMaintenance">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseMaintenance" aria-expanded="false"
                                aria-controls="collapseMaintenance">
                                Maintenance Details
                            </button>
                        </h2>
                        <div id="collapseMaintenance" class="accordion-collapse collapse"
                            aria-labelledby="headingMaintenance" data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>Below is a summary of your aircraft maintenance history.</p>

                                @if($maintenances->isEmpty())
                                    <p class="text-muted">No maintenance records found.</p>
                                @else
                                    <ul class="list-group">
                                        @foreach($maintenances as $m)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row gap-2">
                                                <div class="flex-fill">
                                                    <strong>{{ $m->aircraft_model }} ({{ $m->registration_number }})</strong><br>
                                                    <small>
                                                        {{ \Carbon\Carbon::parse($m->maintenance_date)->format('d M Y') }}
                                                        — {{ \Illuminate\Support\Str::limit($m->issue_description, 40) }}
                                                    </small>
                                                </div>

                                                <div
                                                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 mt-2 mt-md-0">
                                                    {{-- View Button --}}
                                                    <a href="{{ route('maintenances.maintenance_show', $m->id) }}"
                                                        class="btn btn-sm btn-outline-primary w-100 w-md-auto text-center">
                                                        View
                                                    </a>

                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('maintenances.edit', $m->id) }}"
                                                        class="btn btn-sm btn-outline-warning w-100 w-md-auto text-center">
                                                        Edit
                                                    </a>

                                                    {{-- Delete Button with confirmation --}}
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger w-100 w-md-auto text-center"
                                                        onclick="if(confirm('Are you sure you want to delete this record?')) { document.getElementById('delete-form-{{ $m->id }}').submit(); }">
                                                        Delete
                                                    </button>

                                                    {{-- Hidden Delete Form --}}
                                                    <form id="delete-form-{{ $m->id }}"
                                                        action="{{ route('maintenances.destroy', $m->id) }}" method="POST"
                                                        style="display: none;">
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



                <!-- Marketing -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMarketing">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseMarketing" aria-expanded="false" aria-controls="collapseMarketing">
                                Marketing
                            </button>
                        </h2>
                        <div id="collapseMarketing" class="accordion-collapse collapse" aria-labelledby="headingMarketing"
                            data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>If you do not wish to receive marketing emails, please click below. Saves your preference
                                    immediately.</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="marketingConsent"
                                        onclick="setMarketingConsent()">
                                    <label class="form-check-label" for="marketingConsent">
                                        Unsubscribe from future marketing emails
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Cookie Consent -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCookie">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCookie" aria-expanded="false" aria-controls="collapseCookie">
                                Cookie Consent
                            </button>
                        </h2>
                        <div id="collapseCookie" class="accordion-collapse collapse" aria-labelledby="headingCookie"
                            data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>Manage your cookie consent settings. We use cookies to personalize content and to analyze
                                    our traffic.
                                    We will never sell customer information to any third-party service. This consent is
                                    browser specific.
                                    Saves your preference immediately.</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cookieConsent"
                                        onclick="setCookieConsent()">
                                    <label class="form-check-label" for="cookieConsent">
                                        Allow Third-Party Cookies
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="col-md-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTransaction">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTransaction" aria-expanded="false"
                                aria-controls="collapseTransaction">
                                Transaction History
                            </button>
                        </h2>
                        <div id="collapseTransaction" class="accordion-collapse collapse"
                            aria-labelledby="headingTransaction" data-bs-parent="#accountAccordion">
                            <div class="accordion-body">
                                <p>Below is your payment history. Click on any transaction below to download the receipt for
                                    that transaction.</p>
                                <ul class="list-group" id="payment_history_list">
                                    <li class="list-group-item text-center">No Payment History</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End of accordion -->

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

@endsection