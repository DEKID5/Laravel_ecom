@extends('layouts.storelayout')

@section('title', 'Checkout')

@section('maincontent')
<div class="checkout-container">
    <h2>Checkout</h2>

    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
        @csrf

        <!-- User Info (Name, Email, Phone) -->
        <div class="user-info">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ Auth::user()->phone }}" required>
        </div>

        <!-- Location Input (Manual Entry) -->
        <div class="location">
            <label for="address">Delivery Address</label>
            <input type="text" name="address" id="address" placeholder="Enter your delivery address" required>
        </div>

        <!-- Cart Items (Display Cart Details) -->
        <div class="cart-items">
            <h3>Items in Cart</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $cartItem)
                        <tr>
                            <td>{{ $cartItem->product->name }}</td>
                            <td>{{ $cartItem->quantity }}</td>
                            <td>${{ number_format($cartItem->product->price, 2) }}</td>
                            <td>${{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</td>
                            <input type="hidden" name="items[{{ $cartItem->product->id }}][product_id]" value="{{ $cartItem->product->id }}">
                            <input type="hidden" name="items[{{ $cartItem->product->id }}][quantity]" value="{{ $cartItem->quantity }}">
                            <input type="hidden" name="items[{{ $cartItem->product->id }}][price]" value="{{ $cartItem->product->price }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <h4>Order Summary</h4>
                <p>Subtotal: ₵{{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</p>
                @php
                    $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
                    $total = $subtotal; // Add tax or shipping if needed
                @endphp
                <p><strong>Total: ₵{{ number_format($total, 2) }}</strong></p>
                <input type="hidden" name="total_amount" value="{{ $total }}">
            </div>
        </div>

        <!-- Payment Options -->`
        <div class="payment-methods">
            <label>
                <input type="radio" name="payment_method" value="pay_now" required>
                Pay Now
            </label>
            <label>
                <input type="radio" name="payment_method" value="payment_on_delivery">
                Payment on Delivery
            </label>
        </div>

        <!-- Order Notes -->
        <div class="order-notes">
            <label for="notes">Order Notes (Optional)</label>
            <textarea name="notes" id="notes" rows="3" placeholder="Special instructions for delivery"></textarea>
        </div>

        <input type="hidden" name="status" value="pending">
        <input type="hidden" name="order_date" value="{{ date('Y-m-d H:i:s') }}">
        
        <button type="submit">Place Order</button>
    </form>
</div>
@endsection