@extends('layouts.storelayout')

@section('title', 'Product Management')

@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky">
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
                        <a class="nav-link {{ request()->routeIs('productpage') ? 'active' : '' }}" href="{{ route('productpage') }}">
                            <i class="fas fa-box me-2"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.pending') ? 'active' : '' }}" href="{{ route('orders.pending') }}">
                            <i class="fas fa-clock me-2"></i> Pending Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.completed') ? 'active' : '' }}" href="{{ route('orders.completed') }}">
                            <i class="fas fa-check-circle me-2"></i> Completed Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}" href="{{ route('customers.index') }}">
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center py-3 flex-wrap">
                <div class="welcome-text mb-2">
                    <a class="nav-link" href="#">Welcome, {{ Auth::user()->name }}</a>
                </div>
                <button class="btn btn-success" id="toggleAddProduct">
                    <i class="fa fa-plus"></i> Add New Product
                </button>
            </div>

            <h2>Manage Products</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add Product Form Container -->
            <div class="card mb-4" id="addProductForm" style="display: none;">
                <div class="card-body">
                    <h4 class="card-title">Add New Product</h4>
                    <div class="mb-3">
                        <button class="btn btn-outline-primary me-2" onclick="toggleForm('accessoryFields')">Computer Accessories</button>
                        <button class="btn btn-outline-secondary" onclick="toggleForm('laptopFields')">Laptops & Computers</button>
                    </div>

                    <!-- Accessory Form -->
                    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" id="accessoryForm">
                        @csrf
                        <div id="accessoryFields" class="product-fields" style="display: none;">
                            <h5>Computer Accessories</h5>
                            <input type="file" class="form-control mb-2" name="image" required>
                            <input type="text" class="form-control mb-2" name="name" placeholder="Product Name" required>
                            <textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea>
                            <input type="hidden" name="category" value="accessory">
                            <input type="number" class="form-control mb-2" name="price" placeholder="Price (GHS)" required>
                            <button type="submit" class="btn btn-success">Add Accessory</button>
                        </div>
                    </form>

                    <!-- Laptop Form -->
                    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" id="laptopForm">
                        @csrf
                        <div id="laptopFields" class="product-fields" style="display: none;">
                            <h5>Laptops & Computers</h5>
                            <input type="text" class="form-control mb-2" name="name" placeholder="Product Name" required>
                            <input type="file" class="form-control mb-2" name="image" required>
                            <input type="text" class="form-control mb-2" name="brand" placeholder="Brand" required>
                            <input type="text" class="form-control mb-2" name="model" placeholder="Model" required>
                            <textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea>
                            <input type="hidden" name="category" value="laptop">
                            <input type="text" class="form-control mb-2" name="cpu" placeholder="CPU" required>
                            <input type="text" class="form-control mb-2" name="cpu_generation" placeholder="CPU Generation" required>
                            <input type="number" class="form-control mb-2" name="storage_size" placeholder="Storage Size (e.g., 512)" required>
                            <select class="form-control mb-2" name="storage_type" required>
                                <option value="" disabled selected>Storage Type</option>
                                <option value="SSD">SSD</option>
                                <option value="HDD">HDD</option>
                            </select>
                            <input type="number" class="form-control mb-2" name="ram_size" placeholder="RAM Size (e.g., 16)" required>
                            <select class="form-control mb-2" name="cpu_type" required>
                                <option value="" disabled selected>CPU Type</option>
                                <option value="Intel">Intel</option>
                                <option value="AMD">AMD</option>
                                <option value="Apple Silicon">Apple Silicon</option>
                            </select>
                            <input type="number" class="form-control mb-2" name="price" placeholder="Price (GHS)" required>
                            <button type="submit" class="btn btn-success">Add Laptop</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Displayed Products -->
            <div>
                <h2>Displayed Products</h2>

                <!-- Laptops -->
                <h4 class="mt-4">Laptops & Computers</h4>
                <div class="row">
                    @foreach ($products as $product)
                        @if ($product->category === 'laptop')
                            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->brand }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $product->brand }} {{ $product->model }}</h5>
                                        <p class="card-text">GHS {{ number_format($product->price, 2) }}</p>
                                        <a href="{{ route('editProduct', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('deleteProduct', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mt-1">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Accessories -->
                <h4 class="mt-4">Computer Accessories</h4>
                <div class="row">
                    @foreach ($products as $product)
                        @if ($product->category === 'accessory')
                            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="card h-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No Image">
                                    @endif
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">GHS {{ number_format($product->price, 2) }}</p>
                                        <a href="{{ route('editProduct', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('deleteProduct', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mt-1">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const toggleForm = (formId) => {
        document.getElementById('accessoryFields').style.display = 'none';
        document.getElementById('laptopFields').style.display = 'none';
        document.getElementById(formId).style.display = 'block';
    }

    document.getElementById('toggleAddProduct').addEventListener('click', () => {
        const formCard = document.getElementById('addProductForm');
        formCard.style.display = formCard.style.display === 'none' ? 'block' : 'none';
    });
</script>
@endsection
