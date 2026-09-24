@extends('layout')

@section('content')
<div class="container-fluid container-lg mt-4 px-3">

    {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">
                Maintenance Types
            </h3>
            <small class="text-muted">
                Manage aircraft maintenance categories and default maintenance intervals.
            </small>
        </div>

       <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
            <button class="btn btn-primary flex-fill"
                data-bs-toggle="modal"
                data-bs-target="#createTypeModal">

            <i class="bi bi-plus-circle"></i>
            Add Maintenance Type

            </button>
            <a href="{{ route('aircraftmaintenance.dashboard') }}"
            class="btn btn-outline-secondary flex-fill">

                <i class="bi bi-arrow-left"></i>
                Back to Dashboard

            </a>

            

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif

<div class="card shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Name</th>

                        <th>Code</th>

                        <th>Category</th>

                        <th>Hours</th>

                        <th>Days</th>

                        <th>Status</th>

                        <th width="170">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($types as $type)

                    <tr>

                        <td>
                            {{ $type->name }}
                        </td>

                        <td>
                            {{ $type->code }}
                        </td>

                        <td>
                            {{ $type->category }}
                        </td>

                        <td>

                            {{ $type->default_interval_hours ?? '-' }}

                        </td>

                        <td>

                            {{ $type->default_interval_days ?? '-' }}

                        </td>

                        <td>

                            @if($type->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="text-nowrap">

                            <button
                                class="btn btn-sm btn-warning mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editTypeModal"
                                onclick='fillEditModal(@json($type))'>

                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="{{ route('maintenance-types.destroy',$type) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-danger mb-1"
                                    onclick="return confirm('Delete this maintenance type?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center text-muted py-4">

                            No maintenance types found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>
            </div>  

        </div>

        <div class="card-footer bg-white overflow-auto">

            {{ $types->links() }}

        </div>

    </div>

</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createTypeModal">

<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('maintenance-types.store') }}"
              method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Maintenance Type

                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Name</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Code</label>

                            <input
                                type="text"
                                name="code"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Category</label>

                            <input
                                type="text"
                                name="category"
                                class="form-control">

                        </div>

                        <div class="col-md-3 mb-3">

                            <label>Hours</label>

                            <input
                                type="number"
                                step="0.1"
                                name="default_interval_hours"
                                class="form-control">

                        </div>

                        <div class="col-md-3 mb-3">

                            <label>Days</label>

                            <input
                                type="number"
                                name="default_interval_days"
                                class="form-control">

                        </div>

                        <div class="col-md-12 mb-3">

                            <label>Description</label>

                            <textarea
                                name="description"
                                rows="3"
                                class="form-control"></textarea>

                        </div>

                        <div class="col-md-4">

                            <label>Status</label>

                            <select
                                name="is_active"
                                class="form-select">

                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary">

                        Save Maintenance Type

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="modal fade" id="editTypeModal">

    <div class="modal-dialog modal-lg">

        <form id="editTypeForm" method="POST">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Maintenance Type
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Code</label>
                            <input type="text" name="code" id="edit_code" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <input type="text" name="category" id="edit_category" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Hours</label>
                            <input type="number" step="0.1" name="default_interval_hours" id="edit_hours" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Days</label>
                            <input type="number" name="default_interval_days" id="edit_days" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Update Maintenance Type
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<script>
function fillEditModal(type) {

    // Set form action dynamically
    let url = "/maintenance-types/" + type.id;

    document.getElementById('editTypeForm').action = url;

    // Fill fields
    document.getElementById('edit_name').value = type.name ?? '';
    document.getElementById('edit_code').value = type.code ?? '';
    document.getElementById('edit_category').value = type.category ?? '';
    document.getElementById('edit_hours').value = type.default_interval_hours ?? '';
    document.getElementById('edit_days').value = type.default_interval_days ?? '';
    document.getElementById('edit_description').value = type.description ?? '';
    document.getElementById('edit_is_active').value = type.is_active ? 1 : 0;
}
</script>

@endsection