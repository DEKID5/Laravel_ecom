<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product; // ✅ Add this line
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('range', 'daily'); // default to daily

        if ($filter === 'weekly') {
            $start = Carbon::now()->startOfWeek();
        } elseif ($filter === 'monthly') {
            $start = Carbon::now()->startOfMonth();
        } else {
            $start = Carbon::today();
        }

        $end = Carbon::now();

        $dailyIncome = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total_amount');

        $dailySales = Order::whereBetween('created_at', [$start, $end])->count();
        $newClients = User::whereBetween('created_at', [$start, $end])->count();
        $customers = User::count();

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $pendingPercent = $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100) : 0;
        $completedPercent = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;

        $inventoryCount = Product::count(); // ✅ Count all available products

        // Chart data (basic)
        $chartData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as income')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
 $newCustomerDetails = User::whereBetween('created_at', [$start, $end])
    ->select('name', 'phone', 'location', 'created_at')
    ->latest()
    ->take(10)
    ->get();
return view('store.dashboard', compact(
    'dailyIncome',
    'dailySales',
    'newClients',
    'customers',
    'pendingPercent',
    'completedPercent',
    'completedOrders',
    'pendingOrders',
    'chartData',
    'inventoryCount',
    'filter',
    'newCustomerDetails' 
));

    

    }
}
