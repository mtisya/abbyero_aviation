@extends('layout')

@section('content')
<div class="container mt-5 mb-5">

    {{-- ================= ALERTS ================= --}}
    @if(session('success'))
    <div id="success-alert"
         class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm"
         style="z-index:1050;width:300px">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div id="error-alert"
         class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm"
         style="z-index:1050;width:300px">
        {{ session('error') }}
    </div>
    @endif

    <script>
        setTimeout(() => {
            document.getElementById('success-alert')?.remove();
            document.getElementById('error-alert')?.remove();
        }, 5000);
    </script>

    <a href="{{ route('calendar') }}" class="btn btn-primary mb-3">📅 Open Calendar View</a>

    <h2 class="mb-4">Flight Scheduling</h2>

  
        {{-- RIGHT COLUMN → SCHEDULED FLIGHTS TABLE --}}
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Scheduled Flights</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Aircraft</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                    <tr>
                                        <td>{{ $s->flight->registration_number }}</td>
                                        <td>{{ $s->start_time }}</td>
                                        <td>{{ $s->end_time }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ match($s->status){
                                                'scheduled'=>'#0d6efd',
                                                'completed'=>'#198754',
                                                'cancelled'=>'#dc3545',
                                                default=>'#6c757d'
                                            } }}">{{ ucfirst($s->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('flights.schedule.edit',$s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('flights.schedule.cancel',$s->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No schedules yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

