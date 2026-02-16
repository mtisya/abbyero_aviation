<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AircraftPart;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    // Show cart
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Add to cart
    public function add(Request $request, $id)
    {
        // Fetch the part
        $part = AircraftPart::findOrFail($id);

        // Get the current cart from session
        $cart = session()->get('cart', []);

        // If item already in cart, increase quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            // Add new item
            $cart[$id] = [
                "name" => $part->name,
                "price" => $part->price,
                "image" => $part->image,
                "quantity" => 1
            ];
        }

        // Save cart back into session
        session()->put('cart', $cart);

        // Redirect to cart index page with success message
        return redirect()->route('cart.index')->with('success', 'Part added to cart successfully!');
    }


    // Update quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed!');
    }

    public function checkout()
    {
        // Fetch current cart items
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Example: pass cart to checkout view
        return view('cart.checkout', compact('cart'));
    }
    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate total
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Save order
        $order = Order::create([
            'user_id' => auth()->id(), // or null if guests are allowed
            'total' => $total,
            'status' => 'pending',
        ]);

        // Save order items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Checkout successful! Your order has been placed.');
    }



}
