@extends('layout')

@section('content')
    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- HEADER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h3 class="mb-0">Maintenance History</h3>
                <small class="text-muted">
                    All completed maintenance records across fleet
                </small>
            </div>

            <a href="{{ route('aircraftmaintenance.dashboard') }}" class="btn btn-outline-secondary w-30 w-md-auto">
                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>

        </div>

        {{-- FILTER (optional reuse AMOS style) --}}
        <form method="GET" class="d-flex flex-column flex-md-row justify-content-md-end gap-2 mb-4">

            <select name="type" class="form-select w-100 w-md-auto">
                <option value="">All Maintenance Types</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
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

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 text-nowrap">

                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Aircraft</th>
                                <th>Type</th>
                                <th>Tach</th>
                                <th>Hobbs</th>
                                <th>Engineer</th>
                                <th>Cost</th>
                                <th>Work Performed</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($history as $item)

                                <tr>

                                    {{-- DATE --}}
                                    <td>
                                        {{ $item->performed_date->format('d M Y') }}
                                    </td>

                                    {{-- AIRCRAFT --}}
                                    <td class="text-nowrap">
                                        <strong>
                                            {{ $item->aircraft->registration_number ?? '-' }}
                                        </strong>
                                    </td>

                                    {{-- TYPE --}}
                                    <td class="text-nowrap">
                                        {{ $item->maintenanceSchedule->maintenanceType->name ?? '-' }}
                                    </td>

                                    {{-- TACH --}}
                                    <td>
                                        {{ $item->performed_tach }}
                                    </td>

                                    {{-- HOBBS --}}
                                    <td>
                                        {{ $item->performed_hobbs ?? '-' }}
                                    </td>

                                    {{-- ENGINEER --}}
                                    <td>
                                        {{ $item->performed_by ?? '-' }}
                                    </td>

                                    {{-- COST --}}
                                    <td>
                                        {{ number_format($item->cost ?? 0, 2) }}
                                    </td>

                                    {{-- WORK --}}
                                    <td class="text-wrap" style="min-width:220px; max-width:300px;">
                                        <small class="text-muted">
                                            {{ Str::limit($item->work_performed, 80) }}
                                        </small>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                            No maintenance history found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- PAGINATION --}}
            <div class="card-footer bg-white overflow-auto">
                {{ $history->links() }}
            </div>

        </div>

    </div>
@endsection