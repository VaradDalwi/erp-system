<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SalesOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = SalesOrder::sum('total_amount');
        $totalOrders = SalesOrder::count();
        $lowStockProducts = Product::whereRaw('stock <= low_stock_threshold')->get();

        return view('dashboard.index', compact('totalSales', 'totalOrders', 'lowStockProducts'));
    }
}
