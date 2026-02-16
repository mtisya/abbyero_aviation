@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    @if(session('error'))
    <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm" style="z-index: 1050; width: 300px;">
        {{ session('error') }}
    </div>
    @endif

    <script>
    setTimeout(function () {
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.transition = 'opacity 0.5s ease';
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 500);
        }
    }, 5000);
    </script>

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <h2 class="mb-4 text-center text-md-start">Maintenance Details</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $maintenance->aircraft_model }} ({{ $maintenance->registration_number }})</h4>
                    <p><strong>Manufacturer:</strong> {{ $maintenance->manufacturer ?? 'N/A' }}</p>
                    <p><strong>Serial Number:</strong> {{ $maintenance->serial_number ?? 'N/A' }}</p>
                    <p><strong>Engine Type:</strong> {{ $maintenance->engine_type ?? 'N/A' }}</p>
                    <p><strong>Last Maintenance Hours:</strong> {{ $maintenance->last_maintenance_hours ?? 'N/A' }}</p>
                    <p><strong>Maintenance Date:</strong> {{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d M Y') }}</p>
                    <p><strong>Next Due Date:</strong> 
                        {{ $maintenance->next_due_date ? \Carbon\Carbon::parse($maintenance->next_due_date)->format('d M Y') : 'N/A' }}
                    </p>
                    <p><strong>Issue Description:</strong> {{ $maintenance->issue_description }}</p>
                    <p><strong>Remarks:</strong> {{ $maintenance->remarks ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($maintenance->status == 'Pending') bg-warning
                            @elseif($maintenance->status == 'In Progress') bg-info
                            @else bg-success @endif">
                            {{ ucfirst($maintenance->status) }}
                        </span>
                    </p>

                    <div class="mt-3 text-center">
                        <a href="{{ url('/dashboard') }}" class="btn btn-secondary btn-sm">← Back</a>
                        @auth
                            @if(Auth::id() === $maintenance->user_id)
                                <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
