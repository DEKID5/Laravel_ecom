<?php
namespace App\Http\Controllers;

use App\Models\Customer; // Assuming you have a Customer model
use App\Models\Message;  // Assuming you have a Message model
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers and their messages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch customers and their related messages
        $customers = Customer::with('messages')->get();

        return view('customer', compact('customers'));
    }
}
