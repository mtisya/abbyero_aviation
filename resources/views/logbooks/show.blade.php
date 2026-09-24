<div class="row g-3">

    <div class="col-md-6">
        <strong>Date:</strong> {{ $log->flight_date }}
    </div>

    <div class="col-md-6">
        <strong>Aircraft:</strong> {{ $log->aircraft }}
    </div>

    <div class="col-md-6">
        <strong>Route:</strong> {{ $log->route }}
    </div>

    <div class="col-md-6">
        <strong>Type:</strong>
        <span class="badge bg-primary">{{ ucfirst($log->type) }}</span>
    </div>

    <div class="col-md-6">
        <strong>HOBBS:</strong>
        {{ $log->hobbs_start }} → {{ $log->hobbs_end }}
    </div>

    <div class="col-md-6">
        <strong>TACH:</strong>
        {{ $log->tach_start }} → {{ $log->tach_end }}
    </div>

    <div class="col-md-6">
        <strong>Flight Time:</strong> {{ $log->hours }} hrs
    </div>

    <div class="col-md-6">
        <strong>Status:</strong>
        @if($log->approved)
            <span class="badge bg-success">Approved</span>
        @else
            <span class="badge bg-warning">Pending</span>
        @endif
    </div>

</div>

<hr>

{{-- 🧠 AI SECTION --}}
@if($log->aiAnalysis)
<div class="card bg-light p-3 mt-3">

    <h6>🧠 AI Analysis</h6>

    <p><strong>Summary:</strong> {{ $log->aiAnalysis->summary }}</p>
    <p><strong>Feedback:</strong> {{ $log->aiAnalysis->feedback }}</p>

    <strong>Flags:</strong>
    @foreach(json_decode($log->aiAnalysis->flags) as $flag)
        <span class="badge bg-danger">⚠ {{ $flag }}</span>
    @endforeach

</div>
@endif

<hr>

{{-- 📊 QUICK STATS --}}
<div class="row text-center">

    <div class="col-md-4">
        <div class="card p-2">
            <strong>HOBBS</strong>
            <div>{{ $log->hobbs_time }} hrs</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-2">
            <strong>TACH</strong>
            <div>{{ $log->tach_time }} hrs</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-2">
            <strong>Cost</strong>
            <div>${{ number_format($log->hours * 200, 2) }}</div>
        </div>
    </div>

</div>