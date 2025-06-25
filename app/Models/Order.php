<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'store_name',
        'name',
        'email',
        'address',
        'phone_number', 
        'total_amount',
        'payment_method',
        'status',
        'completed_at',
    ];

    // Casting fields for proper format handling (like dates)
    protected $casts = [
        'completed_at' => 'datetime', // Ensure this field is treated as a DateTime instance
    ];

    // Each order has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Each order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each order belongs to a store (through store_name)
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_name', 'store_name');
    }

    // Optional: Define a scope for fetching pending orders
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Optional: Define a scope for fetching completed orders
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Optional: Define a scope to get orders for a specific store
    public function scopeForStore($query, $storeName)
    {
        return $query->where('store_name', $storeName);
    }
}
