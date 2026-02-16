@extends('layout')

@section('content')
<div class="container mt-5">
    <h2>Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <h4>Your Order</h4>
            <ul class="list-group mb-4">
                @foreach($cart as $id => $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item['name'] }} (x{{ $item['quantity'] }})
                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Order Summary</h4>
            <p><strong>Total:</strong> 
                ${{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2) }}
            </p>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    Confirm & Pay
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
