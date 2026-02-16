@extends('layout')

@section('content')
<div class="container mt-5">
    <h3>Email Verification Required</h3>

    <p>Please check your email and click the verification link.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to your email.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn btn-primary">Resend Verification Email</button>
    </form>
</div>
@endsection
