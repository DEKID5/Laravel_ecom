@extends('layouts.layoutmarket')

@section('title', 'Market Dashboard')

@section('maincontent')

<!-- Notification Bell in Top Right Corner (fixed, modern look) -->
<div class="notification-menu store-notification-menu">
    @if(Auth::check())
        @php
            $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->take(10)->get();
            $unreadCount = $notifications->whereNull('read_at')->count();
        @endphp
        <button id="notificationBtn"
            class="btn store-notification-btn position-relative"
            aria-label="Notifications">
            <img src="{{ asset('img/notification1.svg') }}" alt="Notifications" class="notification-bell-img">
            @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </button>
        <div id="notificationDropdown" class="dropdown-menu store-notification-dropdown">
            <h6 class="dropdown-header">Notifications</h6>
            @php
                // Only show unread notifications, newest first
                $unreadNotifications = $notifications->whereNull('read_at')->sortByDesc('created_at');
            @endphp
            @forelse($unreadNotifications as $notification)
                <div class="dropdown-item fw-bold"
                    style="
                        background: #ffe5e5;
                        border-left: 5px solid #dc3545;
                        margin-bottom: 6px;
                        border-radius: 7px;
                        transition: background 0.2s;
                    ">
                    {{ $notification->data['message'] ?? 'No details' }}
                    <br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i') }}</small>
                    @if(isset($notification->data['order_id']))
                        <form action="{{ route('orders.confirm', $notification->data['order_id']) }}" method="POST" style="margin-top: 8px;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Confirm Completed</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="dropdown-item text-muted">No new notifications</div>
            @endforelse
        </div>
    @endif
</div>

<div class="market-main-content">
    <div class="header-modern">
        <h1>Shop Now</h1>
        @if (Auth::check())
            <a class="welcome-message" href="#">Welcome, {{ Auth::user()->name }}</a>
        @else
            <a class="welcome-message" href="{{ route('login') }}">Welcome, Guest. Log in to start shopping.</a>
        @endif
    </div>

    <nav class="market-nav">
        <a href="#All-products">All Products</a>
        <a href="#">Accessories</a>
        <a href="#">Laptops and Computers</a>
        <a href="{{ route('cart.index') }}" class="proceed-btn">Go to Cart</a>
        <a href="#your-history-section">Your History</a>
    </nav>

    <div class="search-container-modern">
        <form action="{{ route('products.search') }}" method="GET">
            <input type="text" name="query" placeholder="Search products...">
            <button type="submit">Search</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success modern-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger modern-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="product-section-header">
        <div>
            <h2 class="section-title">All products.</h2>
            <p class="section-subtitle">Take your pick.</p>
        </div>
    </div>

    <div class="market-container" id="product-list">
        @foreach ($products as $product)
            <div class="market-item" data-name="{{ strtolower($product->name) }}">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <div class="info">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <span class="price">GH₵{{ number_format($product->price, 2) }}</span>
                    
                    @if (Auth::check())
                        <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    @else
                        <p><a href="{{ route('login') }}" class="login-link">Log in to add to cart</a></p>
                    @endif
                    
                    <a href="{{ route('products.show', $product->id) }}" class="details-btn">View Details</a>
                </div>
            </div>
        @endforeach

        @if ($products->isEmpty())
            <div class="market-item empty">
                <h3>No products available yet</h3>
            </div>
        @endif
    </div>

    <hr class="section-divider">

    <!-- Order History Section -->
    @if(Auth::check())
    @php
        $historyOrders = \App\Models\Order::where('user_id', Auth::id())
            ->where('status', 'received') // or 'completed'
            ->orderBy('order_date', 'desc')
            ->with('orderItems.product')
            ->get();
    @endphp

    <div class="history-section" id="your-history-section">
        <h2>Your History</h2>
        @forelse($historyOrders as $order)
            @foreach($order->orderItems as $item)
                <div class="history-item" style="display: flex; align-items: center; gap: 18px;">
                    <img src="{{ asset('storage/' . $item->product->image) }}"
                         alt="{{ $item->product->name }}"
                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
                    <div style="flex:1;">
                        <div style="font-weight:600;">{{ $item->product->name }}</div>
                        <div style="font-size: 0.97em; color: #666;">
                            Order #{{ $order->id }} &middot; {{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                        </div>
                    </div>
                    <span style="font-size: 0.98em;">Qty: {{ $item->quantity }}</span>
                    <span style="font-size: 0.98em;">GH₵{{ number_format($item->price * $item->quantity, 2) }}</span>
                    <span class="badge bg-success" style="font-size: 0.95em;">{{ ucfirst($order->status) }}</span>
                </div>
            @endforeach
        @empty
            <p>No completed orders yet.</p>
        @endforelse
    </div>
    @endif
</div>

<style>
/* Notification Bell Modern Styles */
.store-notification-menu {
    position: fixed;
    top: 24px;
    right: 32px;
    z-index: 2000;
    display: flex;
    align-items: center;
}
.store-notification-btn {
    background: none;
    border: none;
    outline: none;
    box-shadow: none;
    padding: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    position: relative;
}
.notification-bell-img {
    width: 44px;
    height: 44px;
    object-fit: contain;
    display: block;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.10));
    transition: filter 0.2s;
}
.store-notification-btn:hover .notification-bell-img,
.store-notification-btn:focus .notification-bell-img {
    filter: brightness(1.2) drop-shadow(0 2px 8px rgba(255,152,0,0.15));
}
.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    min-width: 22px;
    height: 22px;
    background: #dc3545;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: bold;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(220,53,69,0.15);
}
.store-notification-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 2.8rem;
    min-width: 320px;
    max-width: 370px;
    max-height: 370px;
    overflow-y: auto;
    z-index: 1000;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
    animation: fadeIn 0.2s;
}
.store-notification-dropdown .dropdown-header {
    font-weight: 600;
    background: #f5f5f5;
    border-bottom: 1px solid #eee;
}
.store-notification-dropdown .dropdown-item {
    padding: 12px 18px;
    border-bottom: 1px solid #f1f1f1;
    font-size: 1.02em;
}
.store-notification-dropdown .dropdown-item:last-child {
    border-bottom: none;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px);}
    to { opacity: 1; transform: translateY(0);}
}

/* Modern Main Content Styles */
.market-main-content {
    max-width: 1200px;
    margin: 48px auto 32px auto;
    padding: 0 24px;
}
.header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.market-nav {
    display: flex;
    gap: 2rem;
    margin-bottom: 24px;
    font-weight: 600;
    font-size: 1.1rem;
}
.market-nav a {
    color: #222;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    transition: background 0.15s;
}
.market-nav a:hover {
    background: #f2f2f2;
}
.search-container-modern {
    margin-bottom: 32px;
    display: flex;
    justify-content: center;
}
.search-container-modern input[type="text"] {
    width: 320px;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px 0 0 6px;
    outline: none;
}
.search-container-modern button {
    padding: 8px 18px;
    border: none;
    background: #2563eb;
    color: #fff;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    font-weight: 600;
}
.modern-alert {
    margin-bottom: 18px;
    border-radius: 8px;
    font-size: 1.05em;
}
.cart-section-modern {
    margin-top: 48px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.04);
    padding: 32px 24px;
}
.history-section {
    margin-top: 48px;
}
.history-item {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}
.history-item:last-child {
    border-bottom: none;
}
</style>

@if(Auth::check())
<script>
    // Notification dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('notificationBtn');
        const dropdown = document.getElementById('notificationDropdown');
        if(btn && dropdown) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }
    });
</script>
@endif

@endsection

