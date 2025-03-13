<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        Item::insert([
            [
                'name' => 'Product 1',
                'description' => 'This is a sample item.',
                'original_price' => 100.00,
                'discounted_price' => 80.00,
                'image' => 'images/sample1.jpg',
            ],
            [
                'name' => 'Product 2',
                'description' => 'This is another item.',
                'original_price' => 120.00,
                'discounted_price' => 90.00,
                'image' => 'images/sample2.jpg',
            ],
        ]);
    }
}
