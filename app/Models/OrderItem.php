<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Optional: Access store through product
    public function store()
    {
        return $this->product ? $this->product->store : null;
    }
}
