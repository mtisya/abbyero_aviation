@extends('layout') {{-- Use your actual layout file name --}}

@section('content')
<div class="container mt-5 mb-5">

    {{-- Floating success alert --}}
    @if(session('success'))
        <div id="success-alert" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3 shadow-sm" style="z-index: 1050; width: 300px;">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function () {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    {{-- Page Title --}}
    <div class="row mb-4">
        <div class="col-md-6 mx-auto text-center">
            <h2>Forgot Password</h2>
            <p class="text-muted">Enter your email to receive a password reset link</p>
        </div>
    </div>

    {{-- Status Message --}}
    @if (session('status'))
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    {{-- Errors --}}
    @if ($errors->any())
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6 mx-auto">
                <label class="form-label">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    value="{{ old('email') }}" 
                    placeholder="Enter your email"
                    required 
                    autofocus
                >
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary me-2">
                Send Reset Link
            </button>

            <a href="{{ route('login') }}" class="btn btn-secondary">
                Back to Login
            </a>
        </div>
    </form>
</div>
@endsection