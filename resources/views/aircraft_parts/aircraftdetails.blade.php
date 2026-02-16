@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Parts</h2>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
    </div>

    <a href="{{ route('aircraft_parts.create') }}" class="btn btn-primary mb-3">+ Add Part</a>

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
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Part Number</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parts as $index => $part)
                    <tr>
                        <td>{{ $parts->firstItem() + $index }}</td>
                        <td>{{ $part->part_number }}</td>
                        <td>{{ $part->name }}</td>
                        <td>{{ $part->category }}</td>
                        <td>{{ $part->quantity }}</td>
                        <td>${{ number_format($part->price, 2) }}</td>
                        <td>{{ ucfirst($part->status) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('aircraft_parts.show', $part->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('aircraft_parts.edit', $part->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('aircraft_parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Delete this part?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-start">
                                        Del
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $parts->links() }}
</div>
@endsection
