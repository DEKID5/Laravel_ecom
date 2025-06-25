@extends('layouts.storelayout')

@section('title', 'Order Confirmation')

@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="order-confirmation-container py-4">
                <h2 class="order-title">Order Confirmation</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <p class="order-success-message">Your order has been placed successfully! Here are the details:</p>

                @if(isset($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                        <div class="order-details mb-4" style="border:1px solid #e0e0e0; border-radius:10px; padding:18px;">
                            <h3>Order #{{ $order->id }}</h3>
                            <p><strong>Name:</strong> {{ $order->name }}</p>
                            <p><strong>Email:</strong> {{ $order->email }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->country }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone_number }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>

                            <h4>Order Items:</h4>
                            <ul class="order-items-list">
                                @if (!empty($order->orderItems) && count($order->orderItems) > 0)
                                    @foreach ($order->orderItems as $item)
                                        <li class="order-item">
                                            <span class="item-name">{{ $item->product->name ?? 'Product unavailable' }}</span> - 
                                            <span class="item-quantity">Quantity: {{ $item->quantity }}</span> - 
                                            <span class="item-price">Price: ${{ $item->price }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li>No items found in this order.</li>
                                @endif
                            </ul>
                        </div>
                    @endforeach
                    <div class="text-center mt-4">
                        <a href="{{ route('market.index') }}" class="btn btn-primary">OK</a>
                    </div>
                @else
                    <div class="alert alert-info">No orders found.</div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection

<a class="nav-link {{ request()->routeIs('store.dashboard') ? 'active' : '' }} text-white" href="{{ route('store.dashboard') }}">
    <i class="fas fa-home me-2"></i> Overview
</a>
