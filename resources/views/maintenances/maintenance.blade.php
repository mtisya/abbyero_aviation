@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2>Aircraft Maintenance Records</h2>

    <a href="{{ route('maintenances.create') }}" class="btn btn-primary mb-3">Add Maintenance</a>

    @if (session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                let alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";
                    setTimeout(() => alertBox.remove(), 500); // Remove from DOM after fade out
                }
            }, 5000);
        </script>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Aircraft</th>
                <th>Registration</th>
                <th>Issue</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($maintenances as $m)
                <tr>
                    <td>{{ $loop->iteration + ($maintenances->currentPage() - 1) * $maintenances->perPage() }}</td>
                    <td>{{ $m->aircraft_model }}</td>
                    <td>{{ $m->registration_number }}</td>
                    <td>{{ $m->issue_description }}</td>
                    <td>{{ $m->maintenance_date }}</td>
                    <td>{{ $m->status }}</td>
                    <td>{{ $m->remarks }}</td>
                    <td>
                        <a href="{{ route('maintenances.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('maintenances.destroy', $m->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No maintenance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


</div>
@endsection
