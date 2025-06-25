<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Show messages in the customer blade
    public function index()
    {
        $messages = Message::with(['store', 'product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customers.index', compact('messages'));
    }

    // Send message to a store about a product
    public function send(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|max:1000',
        ]);

        // Try to send the message and handle any exceptions
        try {
            Message::create([
                'user_id' => Auth::id(),
                'store_id' => $request->store_id,
                'product_id' => $request->product_id,
                'message' => $request->message,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Message sent successfully.'], 200);
        } catch (\Exception $e) {
            // Log the exception and return an error response
            \Log::error('Message send failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send message. Please try again later.'], 500);
        }
    }
}
