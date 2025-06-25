<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'message',
    ];

    // Relationship with the user who sent the message
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the store receiving the message
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Relationship with the product associated with the message
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
