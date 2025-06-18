@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Dashboard</h2>

    <div class="row mt-4">
        <!-- Summary Cards -->
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>Total Sales</h4>
                    <h2 class="mb-0">₹{{ number_format($totalSales, 2) }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('sales-orders.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>Total Orders</h4>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('sales-orders.index') }}">View Orders</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>Low Stock Alerts</h4>
                    <h2 class="mb-0">{{ $lowStockProducts->count() }}</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#lowStockModal" data-bs-toggle="modal">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('sales-orders.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>New Sales Order
                            </a>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('products.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-box me-2"></i>Add Product
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('products.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-boxes me-2"></i>Manage Inventory
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Modal -->
<div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lowStockModalLabel">Low Stock Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                @if(auth()->user()->isAdmin())
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td class="text-danger">{{ $product->quantity }}</td>
                                @if(auth()->user()->isAdmin())
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        Update Stock
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
