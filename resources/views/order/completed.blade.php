@extends('layouts.storelayout')

@section('title', 'Completed Orders')

@section('header')
    <h1>Completed Orders</h1>
@endsection

@section('maincontent')
<div class="container-fluid" style="padding:0;">
    <div class="row flex-nowrap" style="margin:0;">
    
<nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100">
            <div class="position-sticky pt-3">
                <div class="sidebar-brand mb-4">
                    <div class="ps-3">
                        <i class="fas fa-store text-white me-2"></i>
                        <h6 class="text-white">{{ auth()->user()->store_name ?? 'Store Name' }}</h6>
                    </div>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.dashboard') ? 'active' : '' }} text-white" href="{{ route('store.dashboard') }}">
                            <i class="fas fa-home me-2"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('productpage') ? 'active' : '' }} text-white" href="{{ route('productpage') }}">
                            <i class="fas fa-box me-2"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.pending') ? 'active' : '' }} text-white" href="{{ route('orders.pending') }}">
                            <i class="fas fa-clock me-2"></i> Pending Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.completed') ? 'active' : '' }} text-white" href="{{ route('orders.completed') }}">
                            <i class="fas fa-check-circle me-2"></i> Completed Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }} text-white" href="{{ route('customers.index') }}">
                            <i class="fas fa-users me-2"></i> Customers
                        </a>
                    </li>
                </ul>
                <div class="sidebar-bottom mt-5">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100 text-white">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4"
              style="min-width:1300px; height:100vh; overflow:auto; background:#f8f9fd; white-space:nowrap;">
            <div style="min-width:1100px;">
                <h2 class="mt-4">Completed Orders</h2>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Product</th>
                                    <th>Location</th>
                                    <th>Total Price</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Completed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($completedOrders->count())
                                    @foreach($completedOrders as $order)
                                        <tr>
                                            <td>{{ $order->name }}</td>
                                            <td>
                                                @php
                                                    $firstItem = $order->orderItems->first();
                                                @endphp
                                                @if($firstItem && $firstItem->product)
                                                    <img src="{{ asset('storage/' . $firstItem->product->image) }}" alt="Product Image" width="40" height="40" style="object-fit:cover; border-radius:6px;">
                                                    <span>{{ $firstItem->product->name }}</span>
                                                @else
                                                    <span>No product</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->address }}</td>
                                            <td>₵{{ number_format($order->total_amount, 2) }}</td>
                                            <td>{{ $order->phone_number }}</td>
                                            <td>{{ $order->email }}</td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No completed orders found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
