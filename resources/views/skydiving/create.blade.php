{{-- resources/views/skydivings/create.blade.php --}}
@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4 mt-5">Add New Skydiving Event</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('skydiving.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Event Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Price (USD)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Available Slots</label>
                <input type="number" name="available_slots" class="form-control" value="{{ old('available_slots') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Time</label>
                <input type="time" name="time" class="form-control" value="{{ old('time') }}" required>
            </div>
        </div>

        {{-- Description + Image Upload side by side --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Upload Image</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>

        {{-- Buttons inline on the right --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('skydiving.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Event</button>
        </div>
    </form>
</div>
@endsection
