@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <h2>Your Cart</h2>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    let alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.transition = "opacity 0.5s ease";
                        alertBox.style.opacity = "0";
                        setTimeout(() => alertBox.remove(), 500); // Remove from DOM after fade out
                    }
                }, 5000);
            </script>
        @endif

    @if(empty($cart))
        <p>Your cart is empty.</p>
        <a href="{{ route('aircraft_parts.partsSale') }}" class="btn btn-primary mt-3">
            ← Back to Aircraft Parts
        </a>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Part</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $total = $item['price'] * $item['quantity']; $grandTotal += $total; @endphp
                    <tr>
                        <td><img src="{{ $item['image'] ? asset('storage/'.$item['image']) : asset('assets/images/index/default.jpg') }}" width="50"></td>
                        <td>{{ $item['name'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" class="form-control form-control-sm w-50 me-2" min="1">
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($total, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="mt-3">Grand Total: ${{ number_format($grandTotal, 2) }}</h4>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('aircraft_parts.partsSale') }}" class="btn btn-secondary">
                ← Back to Aircraft Parts
            </a>
            <a href="{{ route('checkout') }}" class="btn btn-primary">
                Proceed to Checkout →
            </a>
        </div>
    @endif
</div>
@endsection
