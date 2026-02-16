@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4">Add New Glider</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('gliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-6">
                        {{-- Model --}}
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" id="model" 
                                   class="form-control @error('model') is-invalid @enderror" 
                                   value="{{ old('model') }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Registration --}}
                        <div class="mb-3">
                            <label for="registration" class="form-label">Registration</label>
                            <input type="text" name="registration" id="registration" 
                                   class="form-control @error('registration') is-invalid @enderror" 
                                   value="{{ old('registration') }}" required>
                            @error('registration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Capacity --}}
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity (seats)</label>
                            <input type="number" name="capacity" id="capacity" min="1" 
                                   class="form-control @error('capacity') is-invalid @enderror" 
                                   value="{{ old('capacity', 1) }}" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Rental Price --}}
                        <div class="mb-3">
                            <label for="rental_price" class="form-label">Rental Price (per hour)</label>
                            <input type="number" step="0.01" name="rental_price" id="rental_price" 
                                   class="form-control @error('rental_price') is-invalid @enderror" 
                                   value="{{ old('rental_price') }}" required>
                            @error('rental_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-6">
                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" 
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" name="image" id="image" 
                                   class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('gliders.index') }}" class="btn btn-secondary">⬅ Back</a>
                    <button type="submit" class="btn btn-success">✅ Save Glider</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
