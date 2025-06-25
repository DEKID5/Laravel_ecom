<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Order;

class ProductController extends Controller
{
    // Display products for customers
    public function market()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('market', compact('products'));
    }

    // Dashboard
    public function storeDashboard()
    {
        // Only fetch orders for this store/user if needed
        $chartData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as income, COUNT(*) as sales')
            ->where('status', 'completed')
            // ->where('user_id', Auth::id()) // Uncomment if you want per-user
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ...other dashboard data...

        return view('store.dashboard', compact('chartData', /* other variables */));
    }

    // Search products for market
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('market', compact('products'));
    }

    // Show individual product details
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('productdetails', compact('product'));
    }

    // Show seller product page
    public function productPage()
    {
        $products = Product::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('productpage', compact('products'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required|in:accessory,laptop',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // ...other rules
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Save product
        Product::create($data);

        return redirect()->route('productpage')->with('success', 'Product added!');
    }

    // Edit product form
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            return redirect()->route('productpage')->with('error', 'Unauthorized access.');
        }

        return view('editproduct', compact('product'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            return redirect()->route('productpage')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'category' => 'required|in:accessory,laptop',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'cpu' => 'nullable|string|max:255',
            'cpu_generation' => 'nullable|string|max:50',
            'storage_size' => 'nullable|integer|min:1',
            'storage_type' => 'nullable|in:SSD,HDD',
            'ram_size' => 'nullable|integer|min:1',
            'cpu_type' => 'nullable|in:Intel,AMD,Apple Silicon',
            'gpu' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->fill([
            'category' => $request->category,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->category === 'laptop') {
            $product->fill($request->only([
                'brand', 'model', 'cpu', 'cpu_generation',
                'storage_size', 'storage_type', 'ram_size',
                'cpu_type', 'gpu'
            ]));
        }

        $product->save();
        return redirect()->route('productpage')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();
        return redirect()->route('productpage')->with('success', 'Product deleted successfully.');
    }

  


    // Seller dashboard overview
    public function overview()
    {
        // Always redirect to the store dashboard
        return redirect()->route('store.dashboard');
    }

    // Default index for all products (fallback)
    public function index()
    {
        $products = Product::all();
        return view('market', compact('products'));

    }

    // Example for controller
    public function chartData()
    {
        $chartData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as income, COUNT(*) as sales')
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('store.dashboard', compact('chartData'));
    }
}
