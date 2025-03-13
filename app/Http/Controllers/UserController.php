<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index()
    {
        return view("Signin");
    }

    public function sellerSignup()
    {
        return view("Signup");
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'store_name' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location' => $request->location ?? null,
            'phone' => $request->phone ?? null,
            'store_name' => $request->store_name ?? null,
        ]);

        return redirect()->route('Signin')->with('success', '<script>alert("Signup successful! You are now a verified Buyer & Seller.");</script>');
    }

    public function signinUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Determine redirect route
            $redirectUrl = route($user->store_name ? 'store.dashboard' : 'market');

            return redirect($redirectUrl)->with('success', 'Sign in successful! Welcome ' . ($user->store_name ? 'Seller' : 'Buyer') . ' 🎉');
        }

        return back()->withErrors(['email' => 'Invalid credentials. Please try again.'])
                     ->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Signin')->with('success', 'You have been logged out.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent! Check your email.')
            : back()->withErrors(['email' => __($status)])->with('error', 'Failed to send reset link.');
    }

    public function customerMarket()
    {
        $items = Item::all();
        return view('market', compact('items'));
    }

    public function storeDashboard()
    {
        return view('store.dashboard');
    }
}