<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    // Display the sign-in page
    public function index()
    {
        return view("Signin");
    }

    // Display the seller sign-up page
    public function sellerSignup()
    {
        return view("Signup");
    }

    // Handle user sign-up
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // Added confirmation
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'store_name' => 'nullable|string|max:255',
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location' => $request->location ?? null,
            'phone' => $request->phone ?? null,
            'store_name' => $request->store_name ?? null,
        ]);

        return redirect()->route('Signin')->with('success', 'Signup successful! You are now a verified Buyer & Seller.');
    }

    // Handle user sign-in
    public function signinUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt user login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect based on user type (Seller/Buyer)
            $redirectUrl = $user->store_name ? route('store.dashboard') : route('market.index');  // Fixed redirection

            return redirect($redirectUrl)->with('success', 'Sign in successful! Welcome ' . ($user->store_name ? 'Enteprenuer' : 'Customer') . ' 🎉');
        }

        return back()->withErrors(['email' => 'Invalid credentials. Please try again.'])
                     ->with('error', 'Invalid credentials. Please try again.');
    }

    // Handle user logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Signin')->with('success', 'You have been logged out.');
    }

    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent! Check your email.')
            : back()->withErrors(['email' => __($status)])->with('error', 'Failed to send reset link.');
    }

    // Show the market page with products
    public function customerMarket()
    {
        // Fetch products with their respective store data
        $products = Product::with('user')->latest()->get(); // Fetch products with store info

        // Return the market view with the products data
        return view('market', compact('products'));
    }

    // Show the store dashboard
    public function storeDashboard()
    {
        return view('store.dashboard');
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Ensure the user is the product owner before deleting
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('productpage')->with('error', 'Unauthorized access.');
        }

        // Delete the product
        $product->delete();

        return redirect()->route('productpage')->with('success', 'Product deleted successfully!');
    }
}
