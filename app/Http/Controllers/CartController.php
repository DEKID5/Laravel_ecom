<?php

namespace App\Http\Controllers;

use App\Models\{Cart, CartItem, Product, Order, OrderItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\OrderNeedsUserConfirmation;

class CartController extends Controller
{
    // Show the cart
    public function index()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(fn($item) => $item->product ? $item->product->price * $item->quantity : 0);
        $shippingCost = $subtotal > 0 ? 5.00 : 0.00;
        $total = $subtotal + $shippingCost;

        return view('cart', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    // Add product to cart
    public function addToCart(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
            ]);
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItems = Session::get('cart', []);
            $productId = $product->id;

            if (isset($cartItems[$productId])) {
                $cartItems[$productId]['quantity'] += $quantity;
            } else {
                $cartItems[$productId] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image, // <-- use 'image' instead of 'image_url'
                ];
            }

            Session::put('cart', $cartItems);
        }

        return back()->with('success', 'Product added to cart!');
    }

    // Update cart item quantity
    public function update(Request $request, $cartItemId)
    {
        $action = $request->input('action');

        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($cartItemId);

            if ($cartItem->cart->user_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized');
            }

            $cartItem->quantity = $action === 'increment'
                ? $cartItem->quantity + 1
                : max(1, $cartItem->quantity - 1);
            $cartItem->save();
        } else {
            $cartItems = Session::get('cart', []);
            if (isset($cartItems[$cartItemId])) {
                $cartItems[$cartItemId]['quantity'] = $action === 'increment'
                    ? $cartItems[$cartItemId]['quantity'] + 1
                    : max(1, $cartItems[$cartItemId]['quantity'] - 1);
                Session::put('cart', $cartItems);
            }
        }

        return back()->with('success', 'Cart updated!');
    }

    // Remove item from cart
    public function remove($cartItemId)
    {
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($cartItemId);
            if ($cartItem->cart->user_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized');
            }
            $cartItem->delete();
        } else {
            $cartItems = Session::get('cart', []);
            unset($cartItems[$cartItemId]);
            Session::put('cart', $cartItems);
        }

        return back()->with('success', 'Item removed from cart!');
    }

    // Clear all items in cart
    public function clear()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }
        } else {
            Session::forget('cart');
        }

        return back()->with('success', 'Cart cleared!');
    }

    // Checkout view
    public function checkout()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product ? $item->product->price * $item->quantity : 0);
        $shippingCost = 5.00;
        $total = $subtotal + $shippingCost;

        return view('checkout', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    // Place an order
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:pay_now,payment_on_delivery',
            'total_amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        $orders = []; 

        foreach ($cartItems as $cartItem) {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->name = $validated['name'];
            $order->email = $validated['email'];
            $order->phone_number = $validated['phone_number'];
            $order->address = $validated['address'];
            $order->payment_method = $validated['payment_method'];
            $order->status = 'pending';
            $order->order_date = now();
            $order->total_amount = $cartItem->product->price * $cartItem->quantity;
            $order->notes = $validated['notes'];
            $order->save();

            // Save order item
            $order->orderItems()->create([
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);

            $orders[] = $order; // Add to orders array
        }

        $this->clear();

        session()->flash('success', 'Orders placed successfully!');
        // After placing orders, pass all new orders to the view
        return view('order.confirmation', ['orders' => $orders]);
    }

    public function showOrderConfirmation($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        return view('order.confirmation', compact('order'))->with('success', 'Order placed successfully!');
    }

    public function showOrders()
    {
        return view('orders.index');
    }

    public function showPendingOrders()
    {
        $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'processing', 'user_confirm', 'confirmed_by_user'])->get();
        return view('order.pending', compact('pendingOrders'));
    }

    public function showCompletedOrders()
    {
        // Fetch orders with status 'completed', newest first
        $completedOrders = \App\Models\Order::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('order.completed', compact('completedOrders'));
    }

    // Method to get cart items
    private function getCartItems()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            return CartItem::where('cart_id', $cart->id)->with('product')->get();
        } else {
            $sessionCartItems = Session::get('cart', []);
            $cartItems = collect();

            foreach ($sessionCartItems as $id => $item) {
                $product = Product::find($item['product_id']);

                if ($product) {
                    $cartItem = new \stdClass();
                    $cartItem->id = $id;
                    $cartItem->quantity = $item['quantity'];
                    $cartItem->product = $product;
                    // Optionally, override the image if you want to use the session value:
                    // $cartItem->product->image = $item['image'] ?? $product->image;

                    $cartItems->push($cartItem);
                }
            }

            return $cartItems;
        }
    }

    public function markOrderComplete($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        if ($order->status == 'confirmed_by_user') {
            $order->status = 'completed';
            $order->save();
        }
        return back()->with('success', 'Order marked as completed!');
    }

    public function requestUserConfirm($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        if ($order->status == 'pending' || $order->status == 'processing') {
            $order->status = 'user_confirm';
            $order->save();

            // Send notification to user
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderNeedsUserConfirmation($order));
            }
        }
        return back()->with('success', 'User has been notified to confirm the order.');
    }

    // User confirmation endpoint
    public function userConfirmOrder($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        if ($order->status == 'user_confirm') {
            $order->status = 'confirmed_by_user';
            $order->save();
        }
        return redirect()->route('orders.pending')->with('success', 'Order confirmed by user! Store can now complete the order.');
    }

    public function confirm($orderId, Request $request)
    {
        $order = \App\Models\Order::findOrFail($orderId);

        // Only allow the user who owns the order to confirm
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        // Allow confirmation if the order is in any of these states
        if (in_array($order->status, ['pending', 'shipped', 'user_confirm', 'confirmed_by_store'])) {
            $order->status = 'completed'; // Mark as completed
            $order->save();

            // Mark related notification as read
            $user = auth()->user();
            $notification = $user->notifications()
                ->where('data->order_id', $orderId)
                ->whereNull('read_at')
                ->first();
            if ($notification) {
                $notification->markAsRead();
            }

            // Stay on the same page, show success message
            return back()->with('success', 'Order marked as completed!');
        }

        return back()->with('error', 'Order cannot be confirmed in its current state.');
    }
}
