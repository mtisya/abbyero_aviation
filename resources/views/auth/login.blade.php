@extends('layout') {{-- Use your actual layout file name --}}

@section('content')
<div class="container mt-5 mb-5">
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
    <div class="row mb-4">
        <div class="col-md-6 mx-auto text-center">
            <h2>Sign In</h2>
        </div>
    </div>

    @if(session('status'))
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6 mx-auto">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mx-auto">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mx-auto d-flex justify-content-between">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary me-2">Login</button>
            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        </div>
    </form>
</div>
@endsection
