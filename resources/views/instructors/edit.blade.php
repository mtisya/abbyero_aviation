@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4">Edit Instructor</h1>

    <form action="{{ route('instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control" 
                    value="{{ old('name', $instructor->name) }}" 
                    required
                >
            </div>
            <div class="col-md-6">
                <label for="license_number" class="form-label">License Number</label>
                <input 
                    type="text" 
                    name="license_number" 
                    id="license_number" 
                    class="form-control" 
                    value="{{ old('license_number', $instructor->license_number) }}" 
                    required
                >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="specialization" class="form-label">Specialization</label>
                <input 
                    type="text" 
                    name="specialization" 
                    id="specialization" 
                    class="form-control" 
                    value="{{ old('specialization', $instructor->specialization) }}"
                >
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    class="form-control" 
                    value="{{ old('phone', $instructor->phone) }}"
                >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control" 
                    value="{{ old('email', $instructor->email) }}"
                >
            </div>
            <div class="col-md-6">
                <label for="active" class="form-label">Active</label>
                <select name="active" id="active" class="form-select">
                    <option value="1" {{ old('active', $instructor->active) ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !old('active', $instructor->active) ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Biography</label>
            <textarea 
                name="bio" 
                id="bio" 
                class="form-control" 
                rows="4">{{ old('bio', $instructor->bio) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            @if ($instructor->photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $instructor->photo) }}" 
                         alt="Instructor Photo" 
                         class="img-thumbnail" 
                         width="150">
                </div>
            @endif
            <input 
                type="file" 
                name="photo" 
                id="photo" 
                class="form-control"
            >
        </div>

        <div class="d-flex justify-content-start mt-4">
            <button type="submit" class="btn btn-primary me-2">Update Instructor</button>
            <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
