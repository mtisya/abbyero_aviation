@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Edit Aircraft Part</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('aircraft_parts.update', $aircraftPart->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Part Number</label>
                    <input type="text" name="part_number" class="form-control" 
                           value="{{ old('part_number', $aircraftPart->part_number) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $aircraftPart->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" 
                           value="{{ old('category', $aircraftPart->category) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $aircraftPart->description) }}</textarea>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" 
                           value="{{ old('quantity', $aircraftPart->quantity) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" 
                           value="{{ old('price', $aircraftPart->price) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="available" {{ old('status', $aircraftPart->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="reserved" {{ old('status', $aircraftPart->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="installed" {{ old('status', $aircraftPart->status) == 'installed' ? 'selected' : '' }}>Installed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Part Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($aircraftPart->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$aircraftPart->image) }}" alt="Part Image" 
                                 class="img-thumbnail" style="width: 120px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Part</button>
            <a href="{{ route('aircraft_parts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
