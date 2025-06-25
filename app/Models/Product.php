<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'image',
        // add any other fields you use
    ];

    /**
     * The user (seller) that owns the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The store this product belongs to.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Order items this product is part of (optional).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
