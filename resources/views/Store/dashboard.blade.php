@extends('layouts.storelayout')

@section('title', 'Store Dashboard')

@section('maincontent')
<div class="container-fluid" style="padding:0;">
    <div class="row flex-nowrap" style="margin:0;">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100" style="position: sticky; left: 0; z-index: 2;">
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

        <!-- Main Content: Only this area is scrollable (both x and y) -->
        <main class="col-md-10 ms-sm-auto px-md-4"
              style="min-width:1200px; height:100vh; overflow:auto; background:#f8f9fd;">
            <div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" id="panel">
                <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl loopple-navbar-empty">
                    <div class="navbar-add">
                        <svg width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 114 114">
                            <rect style="fill:#cacfda;" x="0.5" y="44.5" width="113" height="24" rx="9.94"/>
                            <rect style="fill:#cacfda;" x="45.5" y="0.5" width="24" height="113" rx="9.94"/>
                        </svg>
                    </div>
                </nav>

                <div class="container-fluid pt-4">
                    <!-- Navigation Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <!-- Income: scrolls to income trends section -->
                            <a href="#income-section" class="text-decoration-none">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body">
                                        <i class="fas fa-wallet fa-2x text-primary mb-2"></i>
                                        <h6>Income</h6>
                                        <p class="mb-0 text-muted">₵{{ number_format($dailyIncome, 2) }}</p>
                                        <small class="text-success">+{{ $completedPercent }}%</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <!-- Inventory: goes to product page -->
                            <a href="{{ route('productpage') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body">
                                        <i class="fas fa-boxes fa-2x text-warning mb-2"></i>
                                        <h6>Inventory</h6>
                                        <p class="mb-0 text-muted">{{ $inventoryCount }} Products</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <!-- Completed Orders: goes to completed orders page -->
                            <a href="{{ route('orders.completed') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <h6>Completed Orders</h6>
                                        <p class="mb-0 text-muted">{{ $completedOrders }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <!-- Pending Orders: goes to pending orders page -->
                            <a href="{{ route('orders.pending') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body">
                                        <i class="fas fa-hourglass-half fa-2x text-danger mb-2"></i>
                                        <h6>Pending Orders</h6>
                                        <p class="mb-0 text-muted">{{ $pendingOrders }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <!-- New Customers: goes to customers page -->
                            <a href="{{ route('customers.index') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-2x text-info mb-2"></i>
                                        <h6>New Customers</h6>
                                        <p class="mb-0 text-muted">{{ $newClients }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Income Section -->
                    <div id="income-section" class="mt-5">
                        <h3>Income & Sales Trends</h3>
                        <canvas id="incomeChart" height="100"></canvas>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        const ctx = document.getElementById('incomeChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chartData->pluck('date')) !!},
                                datasets: [
                                    {
                                        label: 'Income (₵)',
                                        data: {!! json_encode($chartData->pluck('income')) !!},
                                        borderColor: '#4e73df',
                                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                                        fill: true,
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Sales',
                                        data: {!! json_encode($chartData->pluck('sales')) !!},
                                        borderColor: '#1cc88a',
                                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                                        fill: true,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>

                    <!-- Inventory Section -->
                    <div id="inventory-section" class="mb-5">
                        <h5>Inventory Overview</h5>
                        <p>Total Available Products: <strong>{{ $inventoryCount }}</strong></p>
                    </div>

                    <!-- Orders Section -->
                    <div id="orders-section" class="mb-5">
                        <h5>Order Summary</h5>
                        <p>Completed: {{ $completedOrders }} ({{ $completedPercent }}%)</p>
                        <p>Pending: {{ $pendingOrders }} ({{ $pendingPercent }}%)</p>
                    </div>

                    <!-- Customers Section -->
                    <div id="customers-section">
                        <h5>New Customers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newCustomerDetails as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->location }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection