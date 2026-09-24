@extends('layout')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">

        <div>
            <h3 class="mb-0">
                {{ $aircraft->registration_number }} - Maintenance History
            </h3>

            <small class="text-muted">
                Full maintenance record timeline
            </small>
        </div>

        <a href="{{ route('aircraftmaintenance.dashboard') }}"
           class="btn btn-outline-secondary w-50 w-md-auto">
            Back Dashboard
        </a>

    </div>

<form method="GET"
      class="d-flex flex-column flex-md-row justify-content-md-end gap-2 mb-4">

    <select name="type"
        class="form-select w-100 w-md-auto">
        <option value="">All Maintenance Types</option>
        @foreach($types as $type)
            <option value="{{ $type->id }}"
                {{ request('type') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>

    <button class="btn btn-primary d-flex justify-content-center align-items-center gap-1">
        <i class="bi bi-funnel"></i>
        Filter

    </button>

   <a href="{{ url()->current() }}"
   class="btn btn-outline-secondary d-flex justify-content-center align-items-center gap-1">

        <i class="bi bi-arrow-counterclockwise"></i>
        Reset

    </a>

</form>
    {{-- TIMELINE --}}
    <div class="card shadow-sm">

        <div class="card-body">

            @forelse($history as $item)

                <div class="border-start border-4 ps-3 mb-4
                    {{ $loop->first ? 'border-primary' : 'border-light' }}">

                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">

                        <div>
                            <h6 class="mb-1">
                                {{ $item->maintenanceSchedule->maintenanceType->name ?? '-' }}
                            </h6>

                            <small class="text-muted">
                                {{ $item->performed_date->format('d M Y') }}
                            </small>
                        </div>

                        <span class="badge bg-info align-self-start">
                            Tach: {{ $item->performed_tach }}
                        </span>

                    </div>

                    <p class="mb-1 mt-2">
                        {{ $item->work_performed }}
                    </p>

                    <small class="text-muted">
                        Engineer: {{ $item->performed_by ?? '-' }}
                        |
                        License: {{ $item->engineer_license ?? '-' }}
                    </small>

                </div>

            @empty
                <div class="text-center text-muted py-5">
                    No maintenance history found
                </div>
            @endforelse

        </div>

        <div class="card-footer bg-white">
            {{ $history->links() }}
        </div>

    </div>

</div>
@endsection