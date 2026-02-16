@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Add Aircraft Part</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('aircraft_parts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Left column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Part Number</label>
                    <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', 0) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="available" {{ old('status')=='available' ? 'selected' : '' }}>Available</option>
                        <option value="reserved" {{ old('status')=='reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="installed" {{ old('status')=='installed' ? 'selected' : '' }}>Installed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Part Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Part</button>
            <a href="{{ route('aircraft_parts.list') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
