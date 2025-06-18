<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SalesOrderSeeder extends Seeder
{
    public function run(): void
    {
        $salesPerson = User::where('role', 'salesperson')->first();
        if (!$salesPerson) {
            $this->command->warn('Salesperson not found. Skipping sales order creation.');
            return;
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping sales order creation.');
            return;
        }

        // Check if orders already exist
        if (SalesOrder::count() > 0) {
            $this->command->info('Sales orders already exist. Skipping sales order creation.');
            return;
        }

        DB::beginTransaction();
        try {
            // Create 10 sample orders
            for ($i = 0; $i < 10; $i++) {
                $order = SalesOrder::create([
                    'user_id' => $salesPerson->id,
                    'order_date' => now()->subDays(rand(1, 30)),
                    'total_amount' => 0, // Will be calculated based on items
                ]);

                // Add 1-3 random products to each order
                $numItems = rand(1, 3);
                $totalAmount = 0;

                for ($j = 0; $j < $numItems; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);

                    // Check if we have enough stock
                    if ($product->stock < $quantity) {
                        continue; // Skip this item if not enough stock
                    }

                    $subtotal = $product->price * $quantity;
                    $totalAmount += $subtotal;

                    SalesOrderItem::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    // Update product stock
                    $product->stock -= $quantity;
                    $product->save();
                }

                // Update order total
                $order->total_amount = $totalAmount;
                $order->save();
            }

            DB::commit();
            $this->command->info('Sample sales orders created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error creating sales orders: ' . $e->getMessage());
        }
    }
}