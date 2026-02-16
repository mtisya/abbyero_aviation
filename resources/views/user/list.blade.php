@extends('layout')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Users</h2>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
    </div>

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
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Email Verified</th>
                    <th>Status</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ ($users->firstItem() ?? 0) + $index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <form action="{{ route('users.approve', $user->id) }}" method="POST" class="me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"
                                        @if($user->status == 'approved') disabled @endif>
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('users.disapprove', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm"
                                        @if($user->status == 'pending') disabled @endif>
                                        Disapprove
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @if ($users->count() == 0)
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    No users found.
                </td>
            </tr>
            @endif

        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
