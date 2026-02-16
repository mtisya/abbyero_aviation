@extends('layout')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0 rounded-lg">

                    {{-- Image --}}
                    @if($skydiving->image)
                        <img src="{{ asset('storage/' . $skydiving->image) }}" class="card-img-top img-fluid"
                            alt="{{ $skydiving->title }}" style="max-height: 400px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <h2 class="card-title mb-3">{{ $skydiving->title }}</h2>

                        {{-- Description --}}
                        <p class="card-text text-muted mb-4">
                            {{ $skydiving->description ?? 'No description available.' }}
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Price:</strong>
                                ${{ number_format($skydiving->price, 2) }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Location:</strong>
                                {{ $skydiving->location }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Date:</strong>
                                {{ \Carbon\Carbon::parse($skydiving->date)->format('F j, Y') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Time:</strong>
                                {{ \Carbon\Carbon::parse($skydiving->time)->format('g:i A') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Available Slots:</strong>
                                {{ $skydiving->available_slots }}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('skydiving.index') }}" class="btn btn-secondary">
                            ← Back to List
                        </a>
                        <div>
                            <form action="{{ route('skydiving.book', $skydiving->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg mt-2 mb-2">
                                    Book Now
                                </button>
                            </form>



                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection