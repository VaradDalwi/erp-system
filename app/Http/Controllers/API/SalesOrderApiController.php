<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSalesOrderApiRequest;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesOrderApiController extends Controller
{
    public function store(StoreSalesOrderApiRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $total = 0;
            $itemsData = [];

            foreach ($validated['products'] as $productItem) {
                $product = Product::findOrFail($productItem['id']);

                if ($product->quantity < $productItem['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $subtotal = $product->price * $productItem['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $productItem['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                $product->decrement('quantity', $productItem['quantity']);
            }

            $order = SalesOrder::create([
                'user_id' => Auth::id(),
                'total' => $total,
            ]);

            foreach ($itemsData as $item) {
                $item['sales_order_id'] = $order->id;
                SalesOrderItem::create($item);
            }

            DB::commit();

            return response()->json(['message' => 'Sales order created.', 'order_id' => $order->id], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $order = SalesOrder::with(['items.product', 'user'])->findOrFail($id);

        return response()->json([
            'order_id' => $order->id,
            'salesperson' => $order->user->name,
            'total' => $order->total,
            'products' => $order->items->map(function ($item) {
                return [
                    'product' => $item->product->name,
                    'sku' => $item->product->sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ];
            }),
        ]);
    }
}
