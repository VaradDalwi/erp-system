@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Order #{{ $salesOrder->id }}</h3>
                    <div>
                        <a href="{{ route('sales-orders.download-pdf', $salesOrder) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-file-pdf me-1"></i> Download PDF
                        </a>
                        <a href="{{ route('sales-orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Details</h5>
                            <p class="mb-1"><strong>Date:</strong> {{ $salesOrder->created_at->format('M d, Y H:i') }}</p>
                            <p class="mb-1"><strong>Salesperson:</strong> {{ $salesOrder->user->name }}</p>
                            <p class="mb-1"><strong>Order ID:</strong> #{{ $salesOrder->id }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesOrder->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->sku }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>₹{{ number_format($salesOrder->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush