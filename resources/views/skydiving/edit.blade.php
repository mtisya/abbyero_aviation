{{-- resources/views/skydivings/edit.blade.php --}}
@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="mb-4">Edit Skydiving Event</h2>

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

    <form action="{{ route('skydiving.update', $skydiving->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Event Title</label>
                <input type="text" name="title" class="form-control" 
                       value="{{ old('title', $skydiving->title) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" 
                       value="{{ old('location', $skydiving->location) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Price (USD)</label>
                <input type="number" step="0.01" name="price" class="form-control" 
                       value="{{ old('price', $skydiving->price) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Available Slots</label>
                <input type="number" name="available_slots" class="form-control" 
                       value="{{ old('available_slots', $skydiving->available_slots) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" 
                       value="{{ old('date', $skydiving->date) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Time</label>
                <input type="time" name="time" class="form-control" 
                       value="{{ old('time', $skydiving->time) }}" required>
            </div>
        </div>

        {{-- Description + Image Upload side by side --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $skydiving->description) }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Upload Image</label>
                <input type="file" name="image" class="form-control">

                @if($skydiving->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$skydiving->image) }}" alt="Current Image" class="img-fluid rounded shadow-sm">
                        <p class="text-muted mt-1">Current Image</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Buttons inline on the right --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('skydiving.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </div>
    </form>
</div>
@endsection
