@extends('layouts.storelayout')

@section('title', 'Your Cart')

@section('header')
    <h1>Shopping Cart</h1>
@endsection

@section('maincontent')
    <div class="cart-wrapper">
        <div class="cart-container">
            <div class="cart-header">
                <h2>Shopping Cart</h2>
                <span class="items-count">{{ isset($cartItems) ? $cartItems->count() : 0 }} items</span>
            </div>

            @if(isset($cartItems) && $cartItems->isNotEmpty())
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        @if(isset($item->product))
                            <div class="cart-item" style="border:1px solid #e0e0e0; border-radius:10px; margin-bottom:18px; padding:16px; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.03); display:flex; align-items:center;">
                                <div class="product-info" style="display:flex; align-items:center; flex:1;">
                                    <div class="product-image" style="margin-right:16px;">
                                        @php
                                            $imagePath = $item->product->image ?? $item->product->image_url ?? null;
                                        @endphp
                                        @if($imagePath)
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product->name }}" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        @else
                                            <img src="{{ asset('images/placeholder.png') }}" alt="No Image" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <div class="product-name" style="font-weight:600;">{{ $item->product->name }}</div>
                                        <div class="product-description" style="font-size:0.95em; color:#888;">{{ $item->product->description ?? '' }}</div>
                                    </div>
                                </div>

                                <div class="quantity-controls" style="margin:0 16px;">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form" style="display:flex; align-items:center;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="action" value="decrement" class="quantity-btn decrement" style="width:32px; height:32px;">−</button>
                                        <span class="quantity" style="margin:0 8px;">{{ $item->quantity }}</span>
                                        <button type="submit" name="action" value="increment" class="quantity-btn increment" style="width:32px; height:32px;">+</button>
                                    </form>
                                </div>

                                <div class="product-price" style="min-width:90px; text-align:right; font-weight:600;">₵{{ number_format($item->product->price, 2) }}</div>

                                <div class="item-actions" style="margin-left:16px;">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn btn btn-danger btn-sm">DELETE</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="cart-item unavailable" style="border:1px solid #f8d7da; border-radius:10px; margin-bottom:18px; padding:16px; background:#fff0f0;">
                                <p>Product not available</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="cart-footer">
                    <a href="{{ route('market.index') }}" class="back-to-shop">← Back to shop</a>
                </div>
            @else
                <div class="empty-cart">
                    <p>Your cart is empty.</p>
                    <a href="{{ route('market.index') }}" class="back-to-shop">Start Shopping</a>
                </div>
            @endif
        </div>

        <div class="summary-container">
            <div class="summary-header">
                <h2>Summary</h2>
            </div>

            <div class="summary-content">
                <div class="summary-item">
                    <span class="summary-label">ITEMS {{ isset($cartItems) ? $cartItems->count() : 0 }}</span>
                    <span class="summary-value"> ₵{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                
                <div class="summary-item shipping">
                    <span class="summary-label">SHIPPING</span>
                    <div class="shipping-selector">
                        <select name="shipping_option" class="shipping-dropdown">
                            <option value="standard">Standard Delivery:  ₵{{ number_format($shippingCost ?? 5.00, 2) }}</option>
                            <option value="express">Express Delivery:  ₵{{ number_format(($shippingCost ?? 5.00) + 5, 2) }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="summary-item total">
                    <span class="summary-label">TOTAL PRICE</span>
                    <span class="summary-value"> ₵{{ number_format($total ?? 0, 2) }}</span>
                </div>
                
                <div class="checkout-button-container">
                    <a href="{{ route('cart.checkout') }}" class="checkout-button">CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="footer-content">
        <p>&copy; {{ date('Y') }}</p>
    </div>
@endsection