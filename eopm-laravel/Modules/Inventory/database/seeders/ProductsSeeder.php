<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Laptop Dell XPS 15', 'price' => 1299.99, 'stock_qty' => 15],
            ['name' => 'iPhone 15 Pro', 'price' => 999.00, 'stock_qty' => 30],
            ['name' => 'Samsung Galaxy S24', 'price' => 899.50, 'stock_qty' => 25],
            ['name' => 'iPad Air M2', 'price' => 599.99, 'stock_qty' => 20],
            ['name' => 'MacBook Pro 16"', 'price' => 2499.00, 'stock_qty' => 10],
            ['name' => 'Sony WH-1000XM5 Headphones', 'price' => 349.99, 'stock_qty' => 50],
            ['name' => 'Logitech MX Master 3S', 'price' => 99.99, 'stock_qty' => 40],
            ['name' => 'Mechanical Keyboard RGB', 'price' => 129.99, 'stock_qty' => 35],
            ['name' => 'LG 27" 4K Monitor', 'price' => 449.00, 'stock_qty' => 18],
            ['name' => 'Canon EOS R6', 'price' => 2499.99, 'stock_qty' => 8],
            ['name' => 'Nintendo Switch OLED', 'price' => 349.99, 'stock_qty' => 45],
            ['name' => 'PlayStation 5', 'price' => 499.99, 'stock_qty' => 12],
            ['name' => 'Xbox Series X', 'price' => 499.00, 'stock_qty' => 14],
            ['name' => 'Apple Watch Series 9', 'price' => 399.00, 'stock_qty' => 28],
            ['name' => 'AirPods Pro 2', 'price' => 249.00, 'stock_qty' => 60],
            ['name' => 'Samsung 55" QLED TV', 'price' => 899.99, 'stock_qty' => 10],
            ['name' => 'Dyson V15 Vacuum', 'price' => 649.99, 'stock_qty' => 15],
            ['name' => 'KitchenAid Stand Mixer', 'price' => 379.99, 'stock_qty' => 22],
            ['name' => 'Instant Pot Duo Plus', 'price' => 119.99, 'stock_qty' => 55],
            ['name' => 'Bose SoundLink Speaker', 'price' => 149.00, 'stock_qty' => 38],
            ['name' => 'GoPro Hero 12', 'price' => 399.99, 'stock_qty' => 25],
            ['name' => 'DJI Mini 3 Drone', 'price' => 759.00, 'stock_qty' => 12],
            ['name' => 'Fitbit Charge 6', 'price' => 159.99, 'stock_qty' => 42],
            ['name' => 'Ring Video Doorbell', 'price' => 99.99, 'stock_qty' => 48],
            ['name' => 'Kindle Paperwhite', 'price' => 139.99, 'stock_qty' => 65],
        ];

        foreach ($products as $item) {
            Product::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'stock_qty' => $item['stock_qty'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
