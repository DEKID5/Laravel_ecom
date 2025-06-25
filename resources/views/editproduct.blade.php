@extends('layouts.storelayout')
<link rel="stylesheet" href="{{ asset('css/editproduct.css') }}">

@section('maincontent')
<div class="container-fluid">
    <div class="row">


 <!-- Sidebar -->
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


<div class="container">
    <h2>Edit Product</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('updateProduct', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="accessory" {{ $product->category == 'accessory' ? 'selected' : '' }}>Accessory</option>
                <option value="laptop" {{ $product->category == 'laptop' ? 'selected' : '' }}>Laptop</option>
            </select>
        </div>

        <!-- Product Name -->
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required>{{ $product->description }}</textarea>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
            <small>Leave empty if you don't want to change the image.</small>
            <br>
            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100">
        </div>

        <!-- Laptop-Specific Fields -->
        <div id="laptopFields" style="display: {{ $product->category == 'laptop' ? 'block' : 'none' }}">
            <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-control" value="{{ $product->model }}">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>

    <!-- Delete Product Form -->
    <form action="{{ route('deleteProduct', $product->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        @method('DELETE') <!-- DELETE method to prevent GET request issues -->
        <button type="submit" class="btn btn-danger">Delete Product</button>
    </form>

</div>
@endsection
<!-- Add FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Bootstrap JS -->