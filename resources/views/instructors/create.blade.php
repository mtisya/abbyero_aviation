@extends('layout') {{-- use your actual layout name, e.g., layouts.app --}}

@section('content')
<div class="container mt-5 mb-5">
    <h2>Add New Instructor</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('instructors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">License Number</label>
                <input type="text" name="license_number" class="form-control" value="{{ old('license_number') }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Specialization</label>
                <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="e.g. Aerobatics, Gliders">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Biography</label>
            <textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Active Status</label>
            <select name="active" class="form-select">
                <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-success me-2">Save Instructor</button>
            <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
