<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesOrderRequest;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sales-orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales-orders.create', compact('products'));
    }

    public function store(StoreSalesOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $salesOrder = new SalesOrder([
                'user_id' => auth()->id(),
                'order_date' => now(),
                'total_amount' => 0
            ]);
            $salesOrder->save();

            $total = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $salesOrder->update(['total_amount' => $total]);

            DB::commit();

            return redirect()
                ->route('sales-orders.show', $salesOrder)
                ->with('success', 'Sales order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error creating sales order: ' . $e->getMessage());
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['user', 'items.product']);
        return view('sales-orders.show', compact('salesOrder'));
    }

    public function downloadPdf(SalesOrder $salesOrder)
    {
        $salesOrder->load(['user', 'items.product']);
        
        $pdf = PDF::loadView('sales-orders.pdf', compact('salesOrder'));
        
        return $pdf->download("order-{$salesOrder->id}.pdf");
    }
}
