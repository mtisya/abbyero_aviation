@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    @if(session('error'))
    <div id="error-alert" class="alert alert-danger position-fixed top-0 end-0 mt-5 me-3 shadow-sm" style="z-index: 1050; width: 300px;">
        {{ session('error') }}
    </div>
    @endif
    <script>
    setTimeout(function () {
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        if (successAlert) {
            successAlert.style.transition = 'opacity 0.5s ease';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }

        if (errorAlert) {
            errorAlert.style.transition = 'opacity 0.5s ease';
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 500);
        }
    }, 5000);
</script>
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <h2 class="mb-4 text-center text-md-start">Flight Details</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $flight->aircraft_model }} ({{ $flight->registration_number }})</h4>
                    <p><strong>From:</strong> {{ $flight->departure_location }} at {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y H:i') }}</p>
                    <p><strong>To:</strong> {{ $flight->arrival_location }} at {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y H:i') }}</p>
                    <p><strong>Price:</strong> ${{ number_format($flight->price, 2) }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($flight->status) }}</span></p>

                    {{-- Show Book button for users only --}}
                    @auth
                        @if(Auth::user()->role === 'user' || Auth::user()->role === 'instructor')
                            <div class="mt-3 text-center">
                                <form action="{{ route('book.flight', $flight->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Book Airplane</button>
                                    <a href="{{ route('flights.available') }}" class="btn btn-secondary btn-sm">← Back</a>
                                </form>
                            </div>
                        @endif
                    @endauth


            </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
