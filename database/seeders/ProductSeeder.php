<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Pro X1',
                'sku' => 'LPX1-2023',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'stock' => 50,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Wireless Mouse M1',
                'sku' => 'WM1-2023',
                'description' => 'Ergonomic wireless mouse with long battery life',
                'price' => 29.99,
                'stock' => 200,
                'low_stock_threshold' => 30,
            ],
            [
                'name' => 'USB-C Dock Hub',
                'sku' => 'UCH1-2023',
                'description' => '7-in-1 USB-C hub with HDMI and power delivery',
                'price' => 79.99,
                'stock' => 150,
                'low_stock_threshold' => 25,
            ],
            [
                'name' => 'Mechanical Keyboard K1',
                'sku' => 'MK1-2023',
                'description' => 'RGB mechanical keyboard with Cherry MX switches',
                'price' => 129.99,
                'stock' => 75,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => '27" 4K Monitor',
                'sku' => 'M4K-2023',
                'description' => '27-inch 4K IPS monitor with HDR support',
                'price' => 499.99,
                'stock' => 30,
                'low_stock_threshold' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}